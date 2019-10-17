<?php

use App\Jobs\ImgOptimiser;

/* TODO:
 * 1. Прочитать конфиг:
 * - optimize: (bool/string) {false, true - положить файл в тойже директории, pathString} - default false
 * - crop: (bool/string) {false, true - положить файл в тойже директории, pathString}, needle: {width/height}, relation: {width, height, cropMethod} - default false | if(width/height) true
 * - cropMethod: {scale, fit, cover, thumb} - default thumb
 * - width: (int) {1...MAX_INT} - default null
 * - height: (int) {1...MAX_INT} - default null
 * 2. Сгенерировать пути:
 * - optimize {$filepath_o.jpg}
 * - crop {$filepath_thumb.jpg}
 * 3. Постановка задачи:
 * - Если по сгенерированному пути файла не существует - ставим задачу в очередь.
 * - Параметры задачи:
 * -- mode: (string) {optimize, crop}
 * -- path: (string)
 * -- width: (int) - required if mode == crop
 * -- height: (int) - required if mode == crop
 * 4. Возвращаемые значения:
 * - Если optimize != false && crop != false:
 * -- Возвращаем [optimizedPath, croppedPath, 'optimized' => optimized, 'cropped' => cropped]
 * - Если optimize == false && crop == false
 * - Возвращаем исходный $filepath
 * ПРИЧЁМ:
 * - Если была выставлена отложенная задача:
 * -- Возвращаем исходный $filepath
 * - Иначе (файл уже существует):
 * -- Возвращаем сгенерированный путь
 *
 */

function imageHelper($imagesConfigSet, $filepath) {

    $config = config('images.' . $imagesConfigSet);

    $optimize = isset($config['optimize']) && (is_bool($config['optimize']) || (is_string($config['optimize']) && $config['optimize'] !== ''))
        ? $config['optimize']
        : false;

    $crop_width = isset($config['width']) && is_int($config['width'])
        ? (int) $config['width']
        : null;
    $crop_height = isset($config['height']) && is_int($config['height'])
        ? (int) $config['height']
        : null;

    $crop = isset($config['crop']) && (is_bool($config['crop']) || (is_string($config['crop']) && $config['crop'] !== ''))
        ? $config['crop']
        : $crop_width || $crop_height;

    if (!$optimize && !$crop) {
        return $filepath;
    }

    $embeddedPath = isset($config['embeddedPath']) && is_bool($config['embeddedPath'])
        ? $config['embeddedPath']
        : false;

    $result = $optimize && $crop ? ['optimized' => null,'cropped' => null] : null;

    if ($optimize) {
        $target_path = is_string($optimize)
            ? $optimize . '/'
            : substr($filepath, 0, strrpos($filepath, '/') + 1);

        if ($embeddedPath) {
            $target_path .= $imagesConfigSet . '/';
        }

        $target_path .= preg_replace('/\.(.+$)/','_o_.$1', basename($filepath));

        $target_path = preg_replace('/\/\/+/', '/', $target_path);
        if (!file_exists(public_path($target_path))) {
            ImgOptimiser::dispatch([
                'mode' => 'optimize',
                'source_path' => public_path($filepath),
                'target_path' => public_path($target_path),
            ]);
            $target_path = $filepath;
        }

        if ($crop) {
            $result[] = $target_path;
            $result['optimized'] = $target_path;
        } else {
            $result = $target_path;
        }
    }

    if ($crop) {
        if (!$crop_width || !$crop_height) {
            list($width, $height) = getimagesize(public_path($filepath));
            if (!$crop_width) $crop_width = (int) round(($crop_height * $width / $height));
            else $crop_height = (int) round(($crop_width * $height / $width));
        }

        $crop_method = isset($config['cropMethod']) && in_array($config['cropMethod'], ['scale', 'fit', 'cover', 'thumb'], true)
            ? $config['cropMethod']
            : 'thumb';

        $target_path = is_string($crop)
            ? $crop . '/'
            : substr($filepath, 0, strrpos($filepath, '/') + 1);

        if ($embeddedPath) {
            $target_path .= $imagesConfigSet . '/';
        }

        $target_path .= preg_replace('/\.(.+$)/','_thumb_.$1', basename($filepath));

        $target_path = preg_replace('/\/\/+/', '/', $target_path);

        if (!file_exists(public_path($target_path))) {
            ImgOptimiser::dispatch([
                'mode' => 'crop',
                'source_path' => public_path($filepath),
                'target_path' => public_path($target_path),
                'width' => $crop_width,
                'height' => $crop_height,
                'crop_method' => $crop_method,
            ]);
            $target_path = $filepath;
        }

        if ($optimize) {
            $result[] = $target_path;
            $result['cropped'] = $target_path;
        } else {
            $result = $target_path;
        }
    }

    return $result;
}
