<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ImageService
{
    /**
     * Upload a single optimized image to the given folder.
     */
    public function upload(UploadedFile $file, string $folder = 'uploads', array $options = []): string
    {
        $image = $this->createImageFromFile($file);

        if (! $image instanceof GdImage) {
            return $this->storeOriginal($file, $folder);
        }

        $image = $this->orientImage($image, $file);
        $path = $this->storeImageResource($image, $folder, $options);
        imagedestroy($image);

        return $path;
    }

    /**
     * Delete an image from storage.
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Upload multiple images and return array of paths.
     */
    public function uploadMany(array $files, string $folder = 'uploads', array $options = []): array
    {
        $paths = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->upload($file, $folder, $options);
            }
        }
        return $paths;
    }

    /**
     * Replace an existing image (delete old, upload new).
     */
    public function replace(?string $oldPath, UploadedFile $newFile, string $folder = 'uploads', array $options = []): string
    {
        $this->delete($oldPath);
        return $this->upload($newFile, $folder, $options);
    }

    /**
     * Store an inline base64 image from the editor and return its storage path.
     */
    public function storeDataUri(string $dataUri, string $folder = 'uploads', array $options = []): ?string
    {
        if (! preg_match('/^data:(image\/(?:jpe?g|png|webp));base64,(.+)$/i', $dataUri, $matches)) {
            return null;
        }

        $binary = base64_decode(preg_replace('/\s+/', '', $matches[2]), true);

        if ($binary === false || strlen($binary) > ($options['max_bytes'] ?? 8 * 1024 * 1024)) {
            return null;
        }

        $image = @imagecreatefromstring($binary);

        if (! $image instanceof GdImage) {
            return null;
        }

        $path = $this->storeImageResource($image, $folder, $options);
        imagedestroy($image);

        return $path;
    }

    /**
     * Normalize editor HTML so pasted images become optimized files and render responsively.
     */
    public function normalizeHtmlImages(string $html, string $folder = 'uploads', array $options = []): string
    {
        if (trim($html) === '') {
            return $html;
        }

        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument('1.0', 'UTF-8');

        $loaded = $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="post-body-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return $html;
        }

        $root = $document->getElementById('post-body-root');

        if (! $root) {
            return $html;
        }

        foreach ($document->getElementsByTagName('img') as $image) {
            $this->normalizeHtmlImage($image, $folder, $options);
        }

        foreach ($document->getElementsByTagName('*') as $element) {
            if ($element instanceof DOMElement && $element->hasAttribute('style')) {
                $this->keepAllowedInlineStyles($element);
            }
        }

        return trim($this->innerHtml($root));
    }

    private function createImageFromFile(UploadedFile $file): ?GdImage
    {
        return match ($file->getMimeType()) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($file->getRealPath()) ?: null,
            'image/png' => @imagecreatefrompng($file->getRealPath()) ?: null,
            'image/webp' => function_exists('imagecreatefromwebp')
                ? (@imagecreatefromwebp($file->getRealPath()) ?: null)
                : null,
            default => null,
        };
    }

    private function orientImage(GdImage $image, UploadedFile $file): GdImage
    {
        if (! function_exists('exif_read_data') || ! in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'], true)) {
            return $image;
        }

        try {
            $exif = @exif_read_data($file->getRealPath());
            $orientation = (int) ($exif['Orientation'] ?? 1);
        } catch (Throwable) {
            return $image;
        }

        $rotated = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, 270, 0),
            8 => imagerotate($image, 90, 0),
            default => false,
        };

        if ($rotated instanceof GdImage) {
            imagedestroy($image);
            return $rotated;
        }

        return $image;
    }

    private function storeOriginal(UploadedFile $file, string $folder): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::uuid().'.'.$extension;

        return $file->storeAs(trim($folder, '/'), $filename, 'public');
    }

    private function storeImageResource(GdImage $source, string $folder, array $options = []): string
    {
        $canvas = $this->resizeImage($source, $options);
        $format = $this->outputFormat($options);
        $filename = Str::uuid().'.'.$format;
        $path = trim($folder, '/').'/'.$filename;

        Storage::disk('public')->put($path, $this->encodeImage($canvas, $format, (int) ($options['quality'] ?? 82)));

        if ($canvas !== $source) {
            imagedestroy($canvas);
        }

        return $path;
    }

    private function resizeImage(GdImage $source, array $options = []): GdImage
    {
        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);

        if (($options['mode'] ?? null) === 'cover' && isset($options['width'], $options['height'])) {
            return $this->cropCover($source, $sourceWidth, $sourceHeight, (int) $options['width'], (int) $options['height']);
        }

        $maxWidth = (int) ($options['max_width'] ?? 1600);
        $maxHeight = (int) ($options['max_height'] ?? 1200);
        $scale = min($maxWidth / $sourceWidth, $maxHeight / $sourceHeight, 1);
        $targetWidth = max(1, (int) round($sourceWidth * $scale));
        $targetHeight = max(1, (int) round($sourceHeight * $scale));

        if ($targetWidth === $sourceWidth && $targetHeight === $sourceHeight) {
            return $source;
        }

        $canvas = $this->blankCanvas($targetWidth, $targetHeight);
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

        return $canvas;
    }

    private function cropCover(GdImage $source, int $sourceWidth, int $sourceHeight, int $targetWidth, int $targetHeight): GdImage
    {
        $sourceRatio = $sourceWidth / $sourceHeight;
        $targetRatio = $targetWidth / $targetHeight;

        if ($sourceRatio > $targetRatio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) round($sourceHeight * $targetRatio);
            $sourceX = (int) round(($sourceWidth - $cropWidth) / 2);
            $sourceY = 0;
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($sourceWidth / $targetRatio);
            $sourceX = 0;
            $sourceY = (int) round(($sourceHeight - $cropHeight) / 2);
        }

        $canvas = $this->blankCanvas($targetWidth, $targetHeight);
        imagecopyresampled($canvas, $source, 0, 0, $sourceX, $sourceY, $targetWidth, $targetHeight, $cropWidth, $cropHeight);

        return $canvas;
    }

    private function blankCanvas(int $width, int $height): GdImage
    {
        $canvas = imagecreatetruecolor($width, $height);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);

        return $canvas;
    }

    private function outputFormat(array $options): string
    {
        $requested = strtolower((string) ($options['format'] ?? 'webp'));

        if ($requested === 'webp' && function_exists('imagewebp')) {
            return 'webp';
        }

        return in_array($requested, ['jpg', 'jpeg', 'png'], true)
            ? ($requested === 'jpeg' ? 'jpg' : $requested)
            : 'jpg';
    }

    private function encodeImage(GdImage $image, string $format, int $quality): string
    {
        ob_start();

        match ($format) {
            'webp' => imagewebp($image, null, max(0, min(100, $quality))),
            'png' => imagepng($image, null, 6),
            default => imagejpeg($image, null, max(0, min(100, $quality))),
        };

        return (string) ob_get_clean();
    }

    private function normalizeHtmlImage(DOMElement $image, string $folder, array $options): void
    {
        $src = $image->getAttribute('src');

        if (str_starts_with($src, 'data:image/')) {
            $path = $this->storeDataUri($src, $folder, $options);

            if ($path) {
                $image->setAttribute('src', Storage::url($path));
                $image->setAttribute('data-storage-path', $path);
            }
        }

        $image->removeAttribute('width');
        $image->removeAttribute('height');
        $image->removeAttribute('style');
        $image->setAttribute('loading', 'lazy');
        $image->setAttribute('decoding', 'async');
        $image->setAttribute('class', trim($image->getAttribute('class').' post-content-image'));
    }

    private function keepAllowedInlineStyles(DOMElement $element): void
    {
        $allowed = [];

        foreach (explode(';', $element->getAttribute('style')) as $declaration) {
            [$property, $value] = array_pad(explode(':', $declaration, 2), 2, null);
            $property = strtolower(trim((string) $property));
            $value = trim((string) $value);

            if ($property === 'text-align' && in_array($value, ['left', 'center', 'right', 'justify'], true)) {
                $allowed[] = $property.': '.$value;
            }
        }

        if ($allowed) {
            $element->setAttribute('style', implode('; ', $allowed));
        } else {
            $element->removeAttribute('style');
        }
    }

    private function innerHtml(DOMNode $node): string
    {
        $html = '';

        foreach ($node->childNodes as $child) {
            $html .= $node->ownerDocument->saveHTML($child);
        }

        return $html;
    }
}
