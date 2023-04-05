<?php

namespace Gtd\Suda\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $guarded = [];

    protected $translatable = ['title'];

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->with('children');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function link($absolute = false)
    {
        return $this->prepareLink($absolute,'admin',$this->route,$this->parameters, $this->url);
    }

    protected function prepareLink($absolute, $app='admin', $route, $parameters, $url)
    {
        if (is_null($parameters)) {
            $parameters = [];
        }

        if (is_string($parameters)) {
            $parameters = json_decode($parameters, true);
        } elseif (is_array($parameters)) {
            $parameters = $parameters;
        } elseif (is_object($parameters)) {
            $parameters = json_decode(json_encode($parameters), true);
        }
        
        if (!is_null($route) && !empty($route)) {
            if (!Route::has($route)) {
                return '#';
            }
            return route($route, $parameters, $absolute);
        }

        $admin_url = admin_url($url);
        if(Auth::guard('operate')->user() && \Gtd\Suda\Auth\OperateCan::extension(Auth::guard('operate')->user()))
        {
            $admin_url = extadmin_url($url);
        }

        if ($absolute) {
            if($app=='admin'){
                return $admin_url;
            }
            return url($url);
        }

        if($app=='admin'){
            return $admin_url;
        }
        return url($url);
    }

    public function getParametersAttribute()
    {
        return json_decode($this->attributes['parameters']);
    }

    public function setParametersAttribute($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->attributes['parameters'] = $value;
    }

    public function setUrlAttribute($value)
    {
        if (is_null($value)) {
            $value = '';
        }

        $this->attributes['url'] = $value;
    }

    /**
     * Return the Highest Order Menu Item.
     *
     * @param number $parent (Optional) Parent id. Default null
     *
     * @return number Order number
     */
    public function highestOrderMenuItem($parent = null)
    {
        $order = 1;

        $item = $this->where('parent_id', '=', $parent)
            ->orderBy('order', 'DESC')
            ->first();

        if (!is_null($item)) {
            $order = intval($item->order) + 1;
        }

        return $order;
    }
}
