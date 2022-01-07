<?php

namespace Gtd\Suda\Http\Controllers\Mobile;

use Illuminate\Http\Request;

use Gtd\Suda\Http\Controllers\MobileController;

class HomeController extends MobileController
{
    public $view_in_suda = true;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Request $request){
        $this->title('Welcome');
        return $this->display('home');
    }
}
