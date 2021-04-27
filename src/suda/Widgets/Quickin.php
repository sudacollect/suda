<?php

namespace Gtd\Suda\Widgets;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;
use \willvincent\Feeds\Facades\FeedsFacade as Feeds;

use Gtd\Suda\Models\Setting;

class Quickin extends Widget
{
    public $cacheTime = 5;

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'count' => 5
    ];
    
    // public $reloadTimeout = 10;
    
    public function placeholder()
    {
        // return view('view_suda::widgets.loading');
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        
        $exts = $this->getExts();
        if($exts){
            return view('view_suda::widgets.quickin', ['exts'=>$exts]);
        }

        return '';
        
    }
    
    public function getExts(){
        
        $available_data = app('suda_extension')->getQuickins();
        $user = auth('operate')->user();
        if($user->user_role < 6){
            $show_extensions = [];
            $permissions = $user->role->permissions;
            if(array_key_exists('exts',$permissions)){
                //suda-exts
                foreach($permissions['exts'] as $slug=>$permission){
                    if(array_key_exists($slug,$available_data)){
                        $show_extensions[$slug] = $available_data[$slug];
                    }
                }
            }
            return $show_extensions;
        }
        if(count($available_data)>0){
            return $available_data;
        }
        
        return false;
        
    }
}
