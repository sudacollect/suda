<?php
/**
 * Log.php
 * description
 * date 2018-04-23 16:03:37
 * author suda <dev@panel.cc>
 * @copyright Suda. All Rights Reserved.
 */
 
 
namespace Gtd\Suda;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Exception;
use Storage;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Mail\TransportManager;

class Log
{
    
    public function process($monolog){
        
        $monolog->pushHandler(new StreamHandler(storage_path('logs/sdlog.info.'.date('Y-m-d').'.log'), Logger::INFO, false));
        $monolog->pushHandler(new StreamHandler(storage_path('logs/sdlog.debug.'.date('Y-m-d').'.log'), Logger::DEBUG, false));
        $monolog->pushHandler(new StreamHandler(storage_path('logs/sdlog.error.'.date('Y-m-d').'.log'), Logger::ERROR, false));
    
        
        /**
        if (config('app.env') === 'production')
        {
            $from = config('mail.from');
            $transport = new TransportManager($app);
            $mailer    = new Swift_Mailer($transport->driver());

            $monolog->pushHandler(new Monolog\Handler\SwiftMailerHandler(
                $mailer,
                Swift_Message::newInstance('[Log] Error Bookbranch')
                    ->setFrom($from['address'], $from['name'])
                    ->setTo('paul@reedmaniac.com'),
                Logger::ERROR,
                true
            ));
        }
        */
        
    }
    
}
