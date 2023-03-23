<?php

namespace Gtd\Suda\Traits;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use ReflectionClass;

trait Extension
{
    
    //刷新应用
    public function refresh(){
        
        //
        
    }
    
    //获取应用信息
    public function getInfo(){
        
        $info = $this->getExtensionFile('config.php');
        
        if(isset($info['setting']) && isset($info['setting']['default_page']))
        {
            $info['default_page_url'] = 'extension/'.$info['slug'].'/'.$info['setting']['default_page'];
        }
        else
        {
            $info['default_page_url'] = 'entry/extension/'.$info['slug'];
        }
        return $info;
    }
    
    //获取菜单
    public function getExtensionMenu(){
        
        if($this->single_extension_menu){
            
            return $this->getExtensionFile('menu.php');
            
        }
        
    }
    
    //获取应用目录文件
    protected function getExtensionFile($file=''){
        
        $slug = $this->getExtensionSlug();
        if(file_exists(extension_path(ucfirst($slug)).'/'.$file)){
            return require extension_path(ucfirst($slug)).'/'.$file;
        }
        
        return false;;
        
    }
    
    public function getExtensionPath(){
        $reflector = new ReflectionClass(get_class($this));
        $whole_dir = dirname($reflector->getFileName());

        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);
        
        $path_dir = substr(strstr($whole_dir,'app/'.$ucf_extension_dir),strlen('app/'.$ucf_extension_dir)+1);
        $path_dirs = explode('/',$path_dir);

        if(count($path_dirs)<1 || empty($path_dirs[0])){
            return false;
        }
        return strtolower($path_dirs[0]);
    }
    
    //获取应用Slug
    public function getExtensionSlug(){
        $path = $this->getExtensionPath();
        if(!$path){
            $this->redirect(403,'应用配置目录异常');
        }
        return $path;
    }
}
