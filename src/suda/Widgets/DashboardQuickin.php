<?php

namespace Gtd\Suda\Widgets;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;
use \willvincent\Feeds\Facades\FeedsFacade as Feeds;

use Gtd\Suda\Models\Setting;

class DashboardQuickin extends Widget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'count' => 10
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
        
        return view('view_suda::widgets.dashboard_quickin', ['exts'=>$exts]);

        return '';
        
    }
    
    public function getExts(){
        
        $available_data = app('suda_extension')->availableExtensions();
        $user = auth('operate')->user();
        if(\Gtd\Suda\Auth\OperateCan::general($user)){
            $show_extensions = [];
            $permissions = $user->role->permissions;
            if(array_key_exists('exts',$permissions)){
                foreach($permissions['exts'] as $slug=>$permission){
                    if(array_key_exists($slug,$available_data)){
                        $show_extensions[$slug] = $available_data[$slug];
                        if(isset($permission['ext_entrance_menu'])){
                            $show_extensions[$slug]['ext_entrance_menu'] = $permission['ext_entrance_menu'];
                        }
                    }
                }
            }
            return $show_extensions;
        }else{
            return $available_data;
        }
        
        return false;
        
    }
}
