<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class SlugService
{
    /**
     * Generate a unique slug for a given model.
     */
    public function generate(string $title, string $modelClass, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while ($this->slugExists($slug, $modelClass, $ignoreId)) {
            $slug = "{$original}-{$count}";
            $count++;
        }

        return $slug;
    }

    private function slugExists(string $slug, string $modelClass, ?int $ignoreId): bool
    {
        $query = $modelClass::withTrashed()->where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
