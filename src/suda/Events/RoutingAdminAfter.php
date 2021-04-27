<?php

namespace Gtd\Suda\Events;

use Illuminate\Queue\SerializesModels;

class RoutingAdminAfter
{
    use SerializesModels;

    public $router;

    public function __construct()
    {
        $this->router = app('router');

        // @deprecate
        //
        event('sudaroute.admin.routing.after', $this->router);
    }
}
