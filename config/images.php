<?php

return [
    'default' => [
        'crop' => env('IMG_DEFAULT_CROP'),
        'optimize' => env('IMG_DEFAULT_OPTIMISE'),
        'cropMethod' => env('IMG_DEFAULT_CROP_METHOD')
    ],
    'original' => [
        'crop' => false,
        'optimize' => false
    ],
    'category_thumb' => [
        'width' => 328,
        'height' => 246,
        'crop' => env('IMG_DEFAULT_CROP'),
        'cropMethod' => 'thumb',
    ],
    'product_thumb' => [
        'width' => 300,
        'crop' => env('IMG_DEFAULT_CROP'),
        'cropMethod' => 'thumb',
    ],
    'optimized' => [
        'optimize' => env('IMG_DEFAULT_OPTIMISE'),
    ],
    'optimize_crop' => [
        'optimize' => true,
        'crop' => env('IMG_DEFAULT_CROP'),
    ],
];
