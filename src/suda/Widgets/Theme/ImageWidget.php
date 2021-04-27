<?php

namespace Gtd\Suda\Widgets\Theme;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;
use Gtd\Suda\Models\Media;

class ImageWidget extends Widget
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
        
        if(isset($this->config['view']) && $this->config['view']=='config'){
            return $this->displayConfig();
        }
        
        return $this->displayView();

        
    }

    public function displayConfig()
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
                $media_id = array_values($content['images'])[0];
                $media = Media::where('id',$media_id)->first();
                if($media){
                    $params['media'] = $media;
                }
            }

        }
        return view('view_suda::widgets.theme.image.config', $params);

    }

    public function view($config)
    {
        $this->config = array_merge($this->config,$config);

        return $this->displayView();
    }

    public function displayView()
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
                $media_id = array_values($content['images'])[0];
                $media = Media::where('id',$media_id)->first();
                if($media){
                    $params['media'] = $media;
                }
            }
        }
        

        return view('view_suda::widgets.theme.image.view')->with($params);
    }
}
