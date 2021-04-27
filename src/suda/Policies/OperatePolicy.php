<?php

namespace Gtd\Suda\Policies;

use Gtd\Suda\Models\Operate as OperateModel;

class OperatePolicy extends BasePolicy
{
    
    public function view()
    {
        return $this->checkPermission();
    }
    
}
