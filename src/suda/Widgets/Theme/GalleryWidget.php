<?php

namespace Gtd\Suda\Widgets\Theme;
use \Illuminate\Database\Eloquent\Collection;
use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;
use Gtd\Suda\Models\Media;

class GalleryWidget extends Widget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $params = [];
        $params = ['config' => $this->config];
        $content = [];
        if(array_key_exists('content',$this->config))
        {
            $content = $this->config['content'];
            unset($this->config['content']);
            $params['content'] = $content;

            //获取图片
            if(array_key_exists('images',$content) && count($content['images'])>0)
            {
                $medias = [];
                foreach($content['images'] as $image){
                    $media = Media::where('id',$image)->first();
                    if($media){
                        $medias[] = $media;
                    }
                }
                $params['medias'] = new Collection($medias);
            }

        }
        return view('view_suda::widgets.theme.gallery.config', $params);
    }

    public function view($config)
    {
        $params = [];
        $params = ['config' => $config];
        $content = [];
        if(array_key_exists('content',$config))
        {
            $content = $config['content'];
            unset($config['content']);
            $params['content'] = $content;

            //获取图片
            if(array_key_exists('images',$content) && count($content['images'])>0)
            {
                $medias = [];
                foreach($content['images'] as $image){
                    $media = Media::where('id',$image)->first();
                    if($media){
                        $medias[] = $media;
                    }
                }
                $params['medias'] = $medias;
            }

        }
        return view('view_suda::widgets.theme.gallery.view', $params);
    }
}
