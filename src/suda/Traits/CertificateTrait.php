<?php

 
namespace Gtd\Suda\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Exception;
use Storage;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Log;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

use Gtd\Suda\Models\Setting;
use Illuminate\Support\Carbon;

trait CertificateTrait
{
    
    //检查是否授权
    public static function isLicensed(&$err='')
    {
        return true;
    }
    
    public static function getLicense(&$err='')
    {
        return [];
    }
    
    private static function getServerName()
    {
        $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
        return $server_name;
    }
    
}
