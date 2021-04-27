<?php

 
namespace Gtd\Suda;

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

use Gtd\Suda\Traits\CertificateTrait;

class Certificate
{
    use CertificateTrait;
    
}
