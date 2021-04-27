<?php

namespace Gtd\Suda\Http\Controllers\Admin\Compass;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\Response;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;
use Gtd\Suda\Models\Operate;


class DistrictController extends DashboardController
{
    
    public $view_in_suda = true;
    
    
    public function areaJson(){
        
        $data = config('suda_districts',[]);

        $code = 200;
        return Response::json(['districts'=>$data], $code);
        
    }
    
}
