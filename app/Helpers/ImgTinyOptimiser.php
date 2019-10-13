<?php

namespace App\Helpers;

use App\Jobs\ImgOptimiser;
use Illuminate\Database\Eloquent\Model;

class ImgTinyOptimiser {

    public static function optimiseImg($filepath) {
        \Tinify\setKey(env('TINIFY_API_KEY'));
        \Tinify\validate();
        $img_lim = \Tinify\getCompressionCount();
        if($img_lim < env('TINIFY_IMG_LIMIT')) {
            ImgOptimiser::dispatch($filepath);
        } else {
            ImgOptimiser::dispatch($filepath)->delay(now()->addMonth());
        }
    }

    public static function getOptimisedImg($filepath) {
        $img_path = env('IMG_OPTIMISED_PATH') . basename($filepath);
        return file_exists(public_path($img_path)) ? $img_path : $filepath;
    }

    public static function getOptimisedThumb($filepath) {
        $thumb_path = env('IMG_THUMB_PATH') . basename($filepath);
        return file_exists(public_path($thumb_path)) ? $thumb_path : self::getOptimisedImg($filepath);
    }

}
