<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImgOptimiser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $available_mods = ['optimize', 'crop'];
    private $available_crop_methods = ['scale', 'fit', 'cover', 'thumb'];

    private $api_key;
    private $img_limit;

    protected $mode;
    protected $source_path;
    protected $target_path;
    protected $width;
    protected $height;
    protected $crop_method;
    protected $options;

    public function __construct($options = [
        'mode' => null,
        'source_path' => null,
        'target_path' => null,
        'width' => null,
        'height' => null,
        'crop_method' => null,
    ])
    {
        $this->api_key = env('TINIFY_API_KEY');
        $this->img_limit = env('TINIFY_IMG_LIMIT');
        $this->options = $options;

        $this->mode = isset($options['mode']) && in_array($options['mode'], $this->available_mods)
            ? $options['mode']
            : null;

        $this->target_path = isset($options['target_path']) && is_string($options['target_path'])
            ? $options['target_path']
            : null;

        $this->source_path = isset($options['source_path']) && is_string($options['source_path'])
            ? $options['source_path']
            : null;

        $this->width = isset($options['width']) && is_int($options['width'])
            ? (int) $options['width']
            : null;

        $this->height = isset($options['height']) && is_int($options['height'])
            ? (int) $options['height']
            : null;

        $this->crop_method = isset($options['crop_method']) && in_array($options['crop_method'], $this->available_crop_methods, true)
            ? $options['crop_method']
            : null;
    }

    public function handle() {
        if($this->mode && $this->target_path && $this->source_path) {
            if(!file_exists($this->target_path)) {
                \Tinify\setKey(env('TINIFY_API_KEY'));
                \Tinify\validate();
                $img_count = \Tinify\getCompressionCount();
                if($img_count < $this->img_limit) {
                    $this->createPath($this->target_path);
                    switch($this->mode) {
                        case 'optimize':
                            $source = \Tinify\fromFile($this->source_path);
                            $source->toFile($this->target_path); //optimized file
                            break;
                        case 'crop':
                            if($this->width && $this->height && $this->crop_method) {
                                $source = \Tinify\fromFile($this->source_path);
                                $resized = $source->resize([
                                    'method' => $this->crop_method,
                                    'width' => $this->width,
                                    'height' => $this->height
                                ]);
                                $resized->toFile($this->target_path); //resized file
                            } else {
                                throw new \Exception('Sizes of jov is incorrect!');
                            }
                            break;
                        default:
                            throw new \Exception('Mode of job is incorrect!');
                    }
                } else {
                    self::dispatch($this->options)->delay(now()->addMonth());
                }
            }
        } else throw new \Exception('Options of job is incorrect!');
    }

    private function createPath($filepath) {
        $pathWithoutFile = substr($filepath, 0, strrpos($filepath, '/') + 1);
        if (!file_exists($pathWithoutFile))
            mkdir($pathWithoutFile, 0777, true);
    }
}
