<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ImgOptimiser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filepath;
    protected $options;

    public function __construct($filepath, $options = [
        'width' => null,
        'height' => null,
        'optimizedPath' => null,
        'croppedPath' => null,
        'crop' => false,
        'optimize' => false,
    ])
    {
        $this->filepath = $filepath;
        $this->options = $options;
    }

    public function handle()
    {
        $crop = isset($this->options['crop']) ? $this->options['crop'] : false;
        $optimize = isset($this->options['optimize']) ? $this->options['optimize'] : false;

        if ($crop || $optimize) {

            $img_limit = env('TINIFY_IMG_LIMIT');
            \Tinify\setKey(env('TINIFY_API_KEY'));
            \Tinify\validate();
            $img_count = \Tinify\getCompressionCount();

            $pubFile = public_path($this->filepath);

            if($optimize) {
                $pubOptPath = public_path($this->options['optimizedPath']);
                if (!file_exists($pubOptPath)) {
                    $this->createPath($this->options['optimizedPath']);
                    if(++$img_count <= $img_limit) {
                        $source = \Tinify\fromFile($pubFile);
                        $source->toFile($pubOptPath); //optimized file
                    }
                }
            }

            if($crop) {
                $pubCrpPath = public_path($this->options['croppedPath']);
                if (!file_exists($pubCrpPath)) {
                    $this->createPath($this->options['croppedPath']);
                    if(++$img_count <= $img_limit) {
                        $source = \Tinify\fromFile($pubFile);
                        $resized = $source->resize([
                            'method' => $this->options['cropMethod'],
                            'width' => $this->options['width'],
                            'height' => $this->options['height']
                        ]);
                        $resized->toFile($pubCrpPath); //resized file
                    } elseif($optimize && public_path($this->options['optimizedPath'])) {
                        $this->options['optimize'] = false;
                    }
                }
            }

            $redis = Redis::connection();

            if($img_count > $img_limit) {
                self::dispatch($this->filepath, $this->options)->delay(now()->addDay());
            } elseif($this->filepath == $this->options['optimizedPath'] || $this->filepath == $this->options['croppedPath']) {
                $redis->sadd('optimized_images', $this->filepath);
            }
        }
    }

    private function createPath($filepath) {
        $pathWithoutFile = public_path(pathinfo($filepath)['dirname']);
        if (!file_exists($pathWithoutFile))
            mkdir($pathWithoutFile, 0777, true);
    }
}
