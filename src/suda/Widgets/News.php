<?php

namespace Gtd\Suda\Widgets;

use Arrilot\Widgets\AbstractWidget;
use View;
use Gtd\Suda\Widgets\Widget;
use \willvincent\Feeds\Facades\FeedsFacade as Feeds;

class News extends Widget
{
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
        return view('view_suda::widgets.loading');
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        
        $product = $this->getProductNews();
        if(!$product)
        {
            return '';
        }

        return view('view_suda::widgets.news', ['product'=>$product]);
    }
    
    public function getProductNews()
    {
        $feed_url = config('sudaconf.feed_url','');
        if(!$feed_url)
        {
            return false;
        }
        $feed = Feeds::make($feed_url, config('sudaconf.feed_count',5));

        $data = array(
          'title'     => config('sudaconf.feed_title','资讯'),
          'permalink' => $feed->get_permalink(),
          'items'     => $feed->get_items(0,config('sudaconf.feed_count',5)),
        );
        
        return $data;
    }
}
