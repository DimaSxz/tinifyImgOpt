<?php

use App\Jobs\ImgOptimiser;
use Illuminate\Support\Facades\Redis;

//TODO: поправить работу redis

function imageHelper($imagesConfigSet, $filepath) {
    $calcProportion = false;
    $config = config('images.' . $imagesConfigSet);

    if (isset($config['width']) || isset($config['height'])) {
        $config['width'] = isset($config['width']) && is_int($config['width']) ? $config['width'] : null;
        $config['height'] = isset($config['height']) && is_int($config['height']) ? $config['height'] : null;
        if ($config['width'] === null && $config['height'] === null) {
            throw new \Exception('config was\'t set correctly. Check width/height');
        } elseif ($config['width'] === null || $config['height'] === null) {
            $calcProportion = true;
        }

        if (isset($config['crop']) && (!is_string($config['crop']) && !is_bool($config['crop']) || $config['crop'] === '')) {
            throw new \Exception('config was\'t set correctly. Check crop');
        }
        $config['crop'] = isset($config['crop']) ? $config['crop'] : env('IMG_DEFAULT_CROP');

        if (isset($config['cropMethod']) && !in_array($config['cropMethod'], ['scale', 'fit', 'cover', 'thumb'], true)) {
            throw new \Exception('config was\'t set correctly. Check cropMethod');
        }
        $config['cropMethod'] = isset($config['cropMethod']) ? $config['cropMethod'] : env('IMG_DEFAULT_CROP_METHOD');
    } elseif (isset($config['crop']) && ($config['crop'] === true || $config['crop'] !== '')) {
        $config['width'] = env('IMG_DEFAULT_WIDTH') ? (int) env('IMG_DEFAULT_WIDTH') : null;
        $config['height'] = env('IMG_DEFAULT_HEIGHT') ? (int) env('IMG_DEFAULT_HEIGHT') : null;
        if ($config['width'] === null && $config['height'] === null) {
            throw new \Exception('config was\'t set correctly. Check width/height');
        } elseif ($config['width'] === null || $config['height'] === null) {
            $calcProportion = true;
        }

        if (isset($config['cropMethod']) && !in_array($config['cropMethod'], ['scale', 'fit', 'cover', 'thumb'], true)) {
            throw new \Exception('config was\'t set correctly. Check cropMethod');
        }
        $config['cropMethod'] = isset($config['cropMethod']) ? $config['cropMethod'] : env('IMG_DEFAULT_CROP_METHOD');
    }

    if (!isset($config['crop'])) {
        $config['crop'] = false;
    }

    if (isset($config['optimize']) && (!is_string($config['optimize']) && !is_bool($config['optimize']) || $config['optimize'] === '')) {
        throw new \Exception('config was\'t set correctly. Check optimize');
    }
    $config['optimize'] = isset($config['optimize']) ? $config['optimize'] : false;

    if(!$config['crop'] && !$config['optimize']) {
        return $filepath;
    } elseif($config['crop'] === true && $config['optimize'] === true) {
        throw new \Exception('config was\'t set correctly. Check crop/optimize');
    }

    if(isset($config['embeddedPath']) && !is_bool($config['embeddedPath'])) {
        throw new \Exception('config was\'t set correctly. Check embeddedPath');
    }
    $config['embeddedPath'] = isset($config['embeddedPath']) ? $config['embeddedPath'] : false;

    if($calcProportion) {
        list($width, $height) = getimagesize(public_path($filepath));
        if ($config['width'] === null) $config['width'] = (int) round(($config['height'] * $width / $height));
        else $config['height'] = (int) round(($config['width'] * $height / $width));
    }

    if($config['optimize'] === true && $config['crop'] === true) {
        throw new \Exception('config was\'t set correctly. Check optimize/crop');
    }

    $jobsCount = 0;
    $optimize = $config['optimize'];
    $crop = $config['crop'];
    $optimizedPath = null;
    $croppedPath = null;

    if ($optimize) {
        $optimizedPath = preg_replace('/\/\/+/', '/',
            $optimize === true
                ? $filepath
                : ('/' . $optimize . '/'
                . ($config['embeddedPath'] ? '/' . $imagesConfigSet . '/' : '')
                . basename($filepath))
        );
        if($optimize === true && Redis::sismember('optimized_images', $filepath)) {
            $config['optimize'] = false;
        } elseif(is_string($optimize) && !file_exists(public_path($optimizedPath))) {
            $config['optimizedPath'] = $optimizedPath;
            $jobsCount++;
            $optimizedPath = $filepath;
        } else {
            $config['optimize'] = false;
        }
    }

    if($crop) {
        $croppedPath = preg_replace('/\/\/+/', '/',
            $crop === true
            ? $filepath
            : ('/' . $crop . '/'
                . ($config['embeddedPath'] ? '/' . $imagesConfigSet . '/' : '')
                . basename($filepath))
        );

        if($crop === true && Redis::sismember('optimized_images', $filepath)) {
            $config['crop'] = false;
        } elseif(is_string($crop) && !file_exists(public_path($croppedPath))){
            $config['croppedPath'] = $croppedPath;
            $jobsCount++;
            $croppedPath = $filepath;
        } else {
            $config['crop'] = false;
        }
    }

    if ($config['crop'] || $config['optimize']) {
        \Tinify\setKey(env('TINIFY_API_KEY'));
        \Tinify\validate();
        $img_lim = \Tinify\getCompressionCount();
        if($img_lim + $jobsCount <= env('TINIFY_IMG_LIMIT')) {
            ImgOptimiser::dispatch($filepath, $config);
        } else {
            ImgOptimiser::dispatch($filepath, $config)->delay(now()->addMonth());
        }
    }

    $result = null;



    if($optimize && $crop) {
        $result = [
            $optimizedPath, $croppedPath,
            'optimized' => $optimizedPath,
            'cropped' => $croppedPath
        ];
    } elseif ($optimize) {
        $result = $optimizedPath;
    } else $result = $croppedPath;

    return $result;
}
