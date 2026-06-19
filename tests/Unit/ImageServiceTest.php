<?php

namespace Tests\Unit;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    public function test_uploaded_thumbnail_is_cropped_and_optimized(): void
    {
        Storage::fake('public');

        $path = app(ImageService::class)->upload(
            UploadedFile::fake()->image('thumbnail.jpg', 2400, 1600),
            'testing/thumbnails',
            [
                'width' => 800,
                'height' => 500,
                'mode' => 'cover',
                'quality' => 82,
                'format' => 'webp',
            ]
        );

        Storage::disk('public')->assertExists($path);

        [$width, $height] = getimagesize(Storage::disk('public')->path($path));

        $this->assertSame(800, $width);
        $this->assertSame(500, $height);
    }

    public function test_editor_data_uri_images_are_stored_and_normalized(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('body.png', 1800, 1200);
        $dataUri = 'data:image/png;base64,'.base64_encode(file_get_contents($file->getRealPath()));
        $html = '<p style="font-size: 48px; text-align: center">Isi berita</p><p><img src="'.$dataUri.'" width="1800" height="1200" style="width: 1800px"></p>';

        $result = app(ImageService::class)->normalizeHtmlImages($html, 'testing/body', [
            'max_width' => 1200,
            'max_height' => 900,
            'quality' => 82,
            'format' => 'webp',
        ]);

        $files = Storage::disk('public')->allFiles('testing/body');

        $this->assertCount(1, $files);
        $this->assertStringNotContainsString('data:image', $result);
        $this->assertStringContainsString('/storage/testing/body/', $result);
        $this->assertStringContainsString('post-content-image', $result);
        $this->assertStringNotContainsString('width="1800"', $result);
        $this->assertStringNotContainsString('font-size', $result);

        [$width, $height] = getimagesize(Storage::disk('public')->path($files[0]));

        $this->assertLessThanOrEqual(1200, $width);
        $this->assertLessThanOrEqual(900, $height);
    }
}
