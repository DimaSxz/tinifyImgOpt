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

    protected $filepath;
    protected $method;
    protected $width;
    protected $height;
    protected $thumb_path;
    protected $optimised_path;

    public function __construct($filepath)
    {
        $this->filepath = $filepath;
        $this->width = (int) env('IMG_THUMB_WIDTH');
        $this->height = (int) env('IMG_THUMB_HEIGHT');
        $this->method = env('IMG_THUMB_METHOD');
        $this->thumb_path = env('IMG_THUMB_PATH') . basename($filepath);
        $this->optimised_path = env('IMG_OPTIMISED_PATH') . basename($filepath);
    }

    public function handle()
    {
        \Tinify\setKey(env('TINIFY_API_KEY'));
        \Tinify\validate();
        $img_lim = \Tinify\getCompressionCount();
        if($img_lim < env('TINIFY_IMG_LIMIT')) {
            if (!file_exists(public_path($this->optimised_path))) {
                $source = \Tinify\fromFile(public_path($this->filepath));
                $source->toFile(public_path($this->optimised_path)); //optimisation file
            }

            if (!file_exists(public_path($this->thumb_path))) {
                $resized = \Tinify\fromFile(public_path($this->optimised_path))
                    ->resize([
                        'method' => $this->method,
                        'width' => $this->width,
                        'height' => $this->height
                    ]);
                $resized->toFile(public_path($this->thumb_path)); //resize optimised file
            }
        } else {
            self::dispatch($this->filepath)->delay(now()->addDay()); //restart job if it's can't be proceed
        }
    }
}
