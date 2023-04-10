<?php

namespace Gtd\Suda\Traits;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

trait Extension
{
    protected $extension_info;
    //获取应用信息
    public function getExtensionInfo()
    {
        if(!$this->extension_info)
        {
            $this->extension_info = app('suda_extension')->use($this->getExtensionSlug())->extension;
        }
        return $this->extension_info;
    }
    
    // NOTHING
    public function getExtensionMenu(){}
    
    //获取应用目录文件
    protected function getExtensionFile($file=''){
        
        $ext = $this->getExtensionInfo();
        if(file_exists($ext['path'].'/'.$file)){
            return require_once $ext['path'].'/'.$file;
        }
        
        return false;;
        
    }
    
    public function guessExtensionPath(){
        $reflector = new ReflectionClass(get_class($this));
        $whole_dir = dirname($reflector->getFileName());

        $path_dir = '';

        // guess extension => custom repository
        $root_path = base_path();
        $root_match = Str::startsWith($whole_dir, $root_path);
        if($root_match)
        {
            $path_dir = Str::substr($whole_dir, strlen($root_path)+1);
        }

        // guess extension => vendor repository
        $vendor_path = base_path('vendor');
        $vendor_match = Str::startsWith($whole_dir, $vendor_path);
        if($vendor_match)
        {
            $path_dir = Str::substr($whole_dir, strlen($vendor_path)+1);
        }
        
        // guess extension => app/extensions
        $ext_dir = 'app/'.ucfirst(config('sudaconf.extension_dir','extensions'));
        $app_ext_match = Str::startsWith($path_dir, $ext_dir);
        if($app_ext_match)
        {
            $path_dir = substr(strstr($path_dir,$ext_dir),strlen($ext_dir)+1);
        }
        
        $path_dirs = explode('/',strtolower($path_dir));
        
        if(count($path_dirs) < 1 || empty($path_dirs[0])){
            return false;
        }

        $extensions = app('suda_extension')->installedExtensions();
        $ext_keys = array_keys($extensions);
        
        $filtered = Arr::where($path_dirs, function (string $value, int $key) use ($ext_keys) {
            return in_array($value,$ext_keys);
        });

        if(count($filtered) < 1)
        {
            return false;
        }

        if(count($filtered) > 1)
        {
            exit('extension slug filtered: '.implode(',',$filtered));
        }
        
        return strtolower(Arr::first($filtered));
    }
    
    //获取应用Slug
    public function getExtensionSlug(){
        $path = $this->guessExtensionPath();
        if(!$path){
            $this->redirect(403,'应用配置目录异常');
        }
        return $path;
    }
}
