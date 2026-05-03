<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // static:: بترجع للـ Model اللي بيستخدم الـ Trait
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}