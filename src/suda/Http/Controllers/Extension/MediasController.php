<?php
/**
 * MediasController.php
 * 媒体资源方法
 * date 2018-12-12 15:44:59
 * author daocatt <dev@gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 

namespace Gtd\Suda\Http\Controllers\Extension;

//把这里改为自己需要使用的控制器，才能获取到$this->user;
use Gtd\Suda\Http\Controllers\Extension\DashboardController;


use Gtd\Suda\Traits\MediaBoxTrait;

class MediasController extends DashboardController
{
    use MediaBoxTrait;
    
    public function mediaSetting(){

        $this->guard = 'operate';

    }

}
