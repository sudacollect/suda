<?php

namespace Gtd\Suda\Events;

use Illuminate\Queue\SerializesModels;
use Gtd\Suda\Models\Menu;

class MenuDisplay
{
    use SerializesModels;

    public $menu;

    public function __construct($menu)
    {
        $this->menu = $menu;

        // @deprecate
        //
        event('menu.display', $menu);
    }
}
