<?php

namespace Gtd\Suda\Http\Controllers\Admin;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;
use Validator;
use Response;
use Illuminate\Support\Facades\Cache;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

use Gtd\Suda\Models\Page;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Mediatable;

use Gtd\Suda\Models\Operate;
use Gtd\Suda\Models\Organization;
use Gtd\Suda\Certificate;

use GuzzleHttp\Client as HttpClient;


class LangController extends DashboardController
{
    public $view_in_suda = true;
       
    
    
    
    public function switchLang(Request $request,$lang)
    {
        
        App::setLocale($lang);
        session()->put('locale', $lang);
        
        return redirect()->back();
        
    }
    
    
}
