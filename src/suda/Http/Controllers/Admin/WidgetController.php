<?php

namespace Gtd\Suda\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;

use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\ThemeWidget;
use Gtd\Suda\Http\Controllers\Admin\DashboardController;



class WidgetController extends DashboardController
{
    public $view_in_suda = true;
       
    //挂件列表
    public function index(Request $request,$app='site',$theme='')
    {
        $this->title('模板挂件');
        $this->gate('appearance.appearance_widget',app(Setting::class));   
        $this->setMenu('appearance','appearance_widget');
        
        if(empty($theme)){
            $theme = app('theme')->getTheme($app);
        }
        
        //获取可设置的挂件区
        $widget_areas = app('theme')->getWidgetArea($app,$theme);
        if($widget_areas){
            $this->setData('widget_areas',$widget_areas);
        }

        //获取已经设置的挂件
        $theme_widgets = ThemeWidget::where('app',$app)->where('theme',$theme)->orderBy('order','asc')->get();
        $theme_widgets = $theme_widgets->groupBy('widget_area')->toArray();
        $this->setData('theme_widgets',$theme_widgets);
        

        //获取所有挂件
        $widgets = app('theme')->getWidgets();
        
        
        $this->setData('widgets',$widgets);

        $this->setData('app_name',$app);
        $this->setData('theme_name',$theme);

        return $this->display('theme.widget');
    }

    //保存挂件内容
    public function saveWidget(Request $request,$slug)
    {

        //保存模板数据
        $app = $request->app;
        $theme = $request->theme;
        
        $widget_area = $request->widget_area;
        $widget_ctl = $request->widget_ctl;
        if(!$widget_area){
            return $this->responseAjax('fail','挂件数据异常');
        }
        $widget_slug = $slug;
        if(!$widget_slug){
            return $this->responseAjax('fail','挂件数据异常');
        }

        $widget_id = $request->widget_id;

        if(!$request->data){
            return $this->responseAjax('fail','挂件数据异常');
        }

        $widget_data = [];
        parse_str($request->data,$widget_data);

        $content = serialize($widget_data);

        if(!ThemeWidget::where('app',$app)->where('theme',$theme)->where('widget_area',$widget_area)->where('widget_slug',$widget_slug)->where('widget_id',$widget_id)->first()){

            (new ThemeWidget)->fill([

                'app'=>$app,
                'theme'=>$theme,
                'widget_area'=>$widget_area,
                'widget_slug'=>$widget_slug,
                'widget_ctl'=>$widget_ctl,
                'widget_id'=>$widget_id,
                'content'=>$content,

            ])->save();

        }else{

            $widget = ThemeWidget::where('app',$app)->where('theme',$theme)->where('widget_area',$widget_area)->where('widget_slug',$widget_slug)->where('widget_id',$widget_id)->first();
            $widget->update([
                'content'=>$content
            ]);

        }

        //刷新单个区域缓存
        app('theme')->updateWidgetCache($app,$theme,$widget_area);


        return $this->responseAjax('success','数据已保存');
    }


    //排序+新增挂件
    public function sortOrder(Request $request)
    {

        $app = $request->app;
        $theme = $request->theme;
        $widget_area = '';

        $orders = $request->order;
        
        
        foreach($orders as $k=>$order)
        {
            if(!isset($order->area) || !isset($order->slug) || !isset($order->id)){
                continue;
            }
            $widget = ThemeWidget::where('app',$app)->where('theme',$theme)->where('widget_area',$order->area)->where('widget_slug',$order->slug)->where('widget_id',$order->id)->first();
            if($widget){
                $widget->update([
                    'widget_ctl' => $order->ctl,
                    'order' => $k,
                ]);
            }else{
                //新增挂件
                (new ThemeWidget)->fill([
                    
                    'app'=>$app,
                    'theme'=>$theme,
                    'widget_area'=>$order->area,
                    'widget_slug'=>$order->slug,
                    'widget_ctl'=>$order->ctl,
                    'widget_id'=>$order->id,
                    'order' => $k,
                    'content'=>'',

                ])->save();
            }

            $widget_area = $order->area;

        }

        // refresh widget
        app('theme')->updateWidgetCache($app,$theme,$widget_area);
    }

    //删除挂件
    public function removeWidget(Request $request)
    {

        $app = $request->app;
        $theme = $request->theme;
        
        if(!isset($request->widget_id)){
            exit;
        }
        $widget = ThemeWidget::where('app',$app)->where('theme',$theme)->where('widget_id',$request->widget_id)->first();

        if($widget){
            $widget->delete();
        }

        // refresh widget
        app('theme')->updateWidgetCache($app,$theme,$request->area);
        
    }
    

    // //缓存挂件
    // public function updateCache(Request $request)
    // {
    //     app('theme')->updateWidgetCache();
    //     $url = 'widget';
    //     return $this->responseAjax('success',__('suda_lang::press.msg.success'),$url);
    // }
    
}