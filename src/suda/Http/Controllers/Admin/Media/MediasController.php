<?php
/**
 * MediasController.php
 * 媒体资源方法
 * date 2018-12-12 15:44:59
 * author daocatt <dev@panel.cc>
 * @copyright Suda. All Rights Reserved.
 */
 

namespace Gtd\Suda\Http\Controllers\Admin\Media;

//把这里改为自己需要使用的控制器，才能获取到$this->user;
use Gtd\Suda\Http\Controllers\Admin\DashboardController;


use Gtd\Suda\Traits\MediaBoxTrait;

class MediasController extends DashboardController
{
    use MediaBoxTrait;
    
    public function mediaSetting(){

        $this->guard = 'operate';

    }

}
