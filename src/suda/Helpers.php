<?php
/**
* helpers.php
* 自定义函数表
* date 2016-11-11 19:01:12
* @author dev <hello@suda.gtd.xyz>
* @copyright GTD. All Rights Reserved.
*/

use Illuminate\Support\Arr;
use \Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;

if (! function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     *
     * @deprecated Arr::get() should be used directly instead. Will be removed in Laravel 6.0.
     */
    function array_get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }
}

if (!function_exists('suda_widget')) {
    function suda_widget($widget_name,$params=[])
    {
        return Sudacore::widget($widget_name,$params);
    }
}

if (!function_exists('suda_asset')) {
    function suda_asset($path, $secure = null)
    {
        return asset(config('sudaconf.core_assets_path').'/'.$path, $secure?$secure:config('sudaconf.force_secure',null));
    }
}



function passet($path,$secure=null){
    if(!empty(config('sudaconf.static_host','')) && env('APP_ENV')!='local'){
        
        return config('sudaconf.static_host').'/'.$path;
        
    }else{
        return asset($path,$secure?$secure:config('sudaconf.force_secure',null));
    }
}

if(!function_exists('menu_link')){
    
    function menu_link($menu_data,$app='admin'){
        
        if(!isset($menu_data->route)){
            $menu_data->route = '';
        }
        if(!isset($menu_data->parameters)){
            $menu_data->parameters = null;
        }
        if(!isset($menu_data->url)){
            $menu_data->url = '';
        }

        //处理应用的菜单
        if(property_exists($menu_data,'extension_slug') && $app=='admin')
        {
            $menu_data->url = 'extension/'.$menu_data->extension_slug.'/'.$menu_data->url;
        }
        
        return Gtd\Suda\Models\MenuItem::prepareLink(false,$app,$menu_data->route,$menu_data->parameters,$menu_data->url);
    }
    
}

if (!function_exists('menu')) {
    function menu($menuName, $type = null, array $options = [])
    {
        return Gtd\Suda\Models\Menu::display($menuName, $type, $options);
    }
}

if (! function_exists('config_admin_path')) {
    function config_admin_path($path = '')
    {
        return config('sudaconf.admin_path','admin');
    }
}

if (! function_exists('suda_path')) {
    function suda_path($path = '')
    {
        return base_path('vendor/gtdxyz/suda/'.$path);
    }
}

if (! function_exists('theme_path')) {
    function theme_path($path = '')
    {
        return public_path('theme/'.$path);
    }
}



//改变basename
if (!function_exists('change_basename')) {
   function change_basename($path, $newBasename) {
       
       $newBasename = str_replace('%s', '$2', $newBasename);
       $result = preg_replace('/^(.*\/)?(.*?)(\.[^.]+)$/', '$1'.$newBasename.'$3', $path);
       
       return $result;
   }
}

//生成字符串前缀
if (!function_exists('stringBeginsWith')) {
   function stringBeginsWith($Haystack, $Needle, $CaseInsensitive = FALSE, $Trim = FALSE) {
      if (strlen($Haystack) < strlen($Needle))
         return $Trim ? $Haystack : FALSE;
      elseif (strlen($Needle) == 0) {
         if ($Trim)
            return $Haystack;
         return TRUE;
      } else {
         $Result = substr_compare($Haystack, $Needle, 0, strlen($Needle), $CaseInsensitive) == 0;
         if ($Trim)
            $Result = $Result ? substr($Haystack, strlen($Needle)) : $Haystack;
         return $Result;
      }
   }
}


//获取本地图片地址函数
if (!function_exists('suda_image_process')) {
    /**
     * $options = ['size'=>'large','imageClass'=>'good','title'=>'图片标题','url'=>'是否只返回图片链接']
     */
    function suda_image_process($data,$options=[],$cdn=true){
        if (is_string($options)){
            $options = array('imageClass' => $options);
        }else{
            $size = array_get($options,'size','medium');
            $image_class = array_get($options,'imageClass','');
            $title = array_get($options,'title','');
            $return_url = array_get($options,'url',false);
        }
        
        if(!$data){
            $image_url = asset(suda_asset('images/empty.jpg'));
            if($return_url){
                return $image_url;
            }
            return '<img src="'.$image_url.'" title="'.$title.'" class="zpress-image image-'.$size.' '.$image_class.'">';
        }
        
        $path = $data->path;
        $name = $data->name;
        $disk = $data->disk;
        if(!$disk)
        {
            $disk = 'local';
        }

        $large_path = change_basename($path, 'p%s');
        if(!file_exists(Storage::disk($disk)->path($large_path))){
            $large_path = $path;
        }
        
        $title = $title?$title:$name;
        
        $withLarge = array_get($options,'withLarge',false);
        
        $image_large_url = $image_large_url_str = '';
        if($withLarge){
            if($cdn){
                $image_large_url = passet(Storage::disk($disk)->url($large_path));
            }else{
                $image_large_url = asset(Storage::disk($disk)->url($large_path));
            }
            
            $image_large_url_str = 'data-src="'.$image_large_url.'"';
        }
        
        if($size=='large'){
            $path = $large_path;//原图
        }
        if($size=='medium'){
            $path = change_basename($path, 'm%s');//中图
        }
        if($size=='small'){
            $path = change_basename($path, 's%s');//小兔
        }
        if($size=='minimal'){
            $path = change_basename($path, 'i%s');//mini图
        }
        
        if(!file_exists(Storage::disk($disk)->path($path))){
            $path = $data->path;
        }
        
        
        
        if($cdn){
            $image_url = passet(Storage::disk($disk)->url($path));
        }else{
            $image_url = asset(Storage::disk($disk)->url($path));
        }
        
        if($return_url){
            return $image_url;
        }
        return '<img src="'.$image_url.'?'.time().'" title="'.$title.'" '.$image_large_url_str.' class="zpress-image image-'.$size.' '.$image_class.'">';
    }
    
}

if (!function_exists('suda_media_process')) {
    /**
     * $options = ['size'=>'large','imageClass'=>'good','title'=>'图片标题','url'=>'是否只返回图片链接']
     */
    function suda_media_process($data,$options=[],$cdn=true){

        if (is_string($options)){
            $options = array('imageClass' => $options);
        }else{
            $size = array_get($options,'size','medium');
            $image_class = array_get($options,'imageClass','');
            $title = array_get($options,'title','');
            $return_url = array_get($options,'url',false);
        }
        
        if(!$data){
            $image_url = asset(suda_asset('images/file.png'));
            if($return_url){
                return $image_url;
            }
            return '<img src="'.$image_url.'" title="'.$title.'" class="zpress-image image-'.$size.' '.$image_class.'">';
        }
        
        $disk = $data->disk;
        if(!$disk)
        {
            $disk = 'local';
        }

        $path = $data->path;
        $name = $data->name;
        $type = $data->type;
        $types = explode('|',$type);
        
        $file_icon = 'images/file.png';
        if(count($types)>1){
            if($types[1] == 'pdf'){
                $file_icon = 'images/file_'.$types[1].'.png';
            }
        }

        $title = $title?$title:$name;
        
        $withLarge = array_get($options,'withLarge',false);
        
        $file_path_url = $file_path_url_str = '';
        if($withLarge){
            if($cdn){
                $file_path_url = url(Storage::disk($disk)->url($path));
            }else{
                $file_path_url = url(Storage::disk($disk)->url($path));
            }
            
            $file_path_url_str = 'data-src="'.$file_path_url.'"';
        }
        
        if($size=='large'){
            $path = asset(suda_asset($file_icon));//原图
        }
        if($size=='medium'){
            $path = asset(suda_asset($file_icon));//中图
        }
        if($size=='small'){
            $path = asset(suda_asset($file_icon));//小兔
        }
        if($size=='minimal'){
            $path = asset(suda_asset($file_icon));//mini图
        }
        
        $image_url = $path;
        
        if($return_url){
            return $image_url;
        }
        return '<img src="'.$image_url.'" file="file" title="'.$title.'" '.$file_path_url_str.' class="zpress-image image-'.$size.' '.$image_class.'">';
    }
    
}


if (!function_exists('suda_image')) {
    /**
     * $options = ['size'=>'large','imageClass'=>'good','title'=>'图片标题','url'=>'是否只返回图片链接']
     */
    function suda_image($data,$options=[],$cdn=true){
        return suda_media($data,$options,$cdn);
    }
    
}

//获取本地图片地址函数
if (!function_exists('suda_media')) {
    /**
     * $options = ['size'=>'large','imageClass'=>'good','title'=>'图片标题','url'=>'是否只返回图片链接']
     */
    function suda_media($data,$options=[],$cdn=true){
        
        if (is_string($options)){
            $options = array('imageClass' => $options);
        }else{
            $size = array_get($options,'size','medium');
            $image_class = array_get($options,'imageClass','');
            $title = array_get($options,'title','');
            $return_url = array_get($options,'url',false);
        }
        
        if(!$data){
            $image_url = asset(suda_asset('images/empty.jpg'));
            if($return_url){
                return $image_url;
            }
            return '<img src="'.$image_url.'?.'.time().'" title="'.$title.'" class="zpress-image image-'.$size.' '.$image_class.'">';
        }
        
        $type = $data->type;
        $types = explode('|',$type);
        if($types[0]!='FILE'){
            return suda_image_process($data,$options,$cdn);
        }
        return suda_media_process($data,$options,$cdn);
    }
    
}

if(!function_exists('suda_avatar')){
    function suda_avatar($avatar='',$size='medium',$return_url = false){
        if($avatar && isset($avatar->media)){
            $avatar_href = suda_image($avatar->media,['size'=>$size,"url"=>true]);
            if($return_url){
                echo $avatar_href;
            }
        }else{
            $avatar_href = url(suda_asset('images/avatar.png'));
            if($return_url){
                echo $avatar_href;
            }
        }
        
        echo '<img src="'.$avatar_href.'" class="zpress-image avatar">';
    }
}


if (!function_exists('userAgentType')) {
    function userAgentType($value = null) {
        
        static $type = null;
        
        if ($value !== null) {
            $type = $value;
        }
        
        if ($type !== null) {
            return $type;
        }
        
        $type = strtolower(getValue('HTTP_X_UA_DEVICE', $_SERVER, ''));
        if ($type) {
            return $type;
        }
        
        if ($type = getValue('X-UA-Device-Force', $_COOKIE)) {
            return $type;
        }
        
        $allHttp = strtolower(getValue('ALL_HTTP', $_SERVER));
        $httpAccept = strtolower(getValue('HTTP_ACCEPT', $_SERVER));
        $userAgent = strtolower(getValue('HTTP_USER_AGENT', $_SERVER));
        
        if (strpos($userAgent, 'sudaapp') !== false) {
            return $type = 'app';
        }
        
        if ((strpos($httpAccept, 'application/vnd.wap.xhtml+xml') > 0)
            || ((isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']))) || strpos($userAgent, 'mobile')
        ) {
            return $type = 'mobile';
        }
        
        if (strpos($userAgent, 'android') !== false && strpos($userAgent, 'mobile') !== false) {
            return $type = 'mobile';
        }
        
        if (strpos($allHttp, 'operamini') > 0) {
            return $type = 'mobile';
        }
        
        $directAgents = array(
            'up.browser',
            'up.link',
            'mmp',
            'symbian',
            'smartphone',
            'midp',
            'wap',
            'phone',
            'opera m',
            'kindle',
            'webos',
            'playbook',
            'bb10',
            'playstation vita',
            'windows phone',
            'iphone',
            'ipod',
            'nintendo 3ds'
        );
        $directAgentsMatch = implode('|', $directAgents);
        if (preg_match("/({$directAgentsMatch})/i", $userAgent)) {
            return $type = 'mobile';
        }
        
        $mobileUserAgent = substr($userAgent, 0, 4);
        $mobileUserAgents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox', 'qwap',
            'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar', 'sie-',
            'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-', 'tosh',
            'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp', 'wapr',
            'webc', 'winw', 'winw', 'xda', 'xda-');

        if (in_array($mobileUserAgent, $mobileUserAgents)) {
            return $type = 'mobile';
        }
        
        return $type = 'desktop';
    }
}

if (!function_exists('isMobile')) {
    function isMobile($value = '') {
        if ($value === true || $value === false) {
            $type = $value ? 'mobile' : 'desktop';
            userAgentType($type);
        } elseif ($value === null) {
            userAgentType(false);
        }

        $type = userAgentType();
        
        switch ($type) {
            case 'app':
            case 'mobile':
                $isMobile = true;
                break;
            default:
                $isMobile = false;
                break;
        }

        return $isMobile;
    }

}

function isPhone($string){
    return !!preg_match('/^1[3|4|5|7|8]\d{9}$/', $string);
}

if (!function_exists('metas')) {
    function metas($meta=''){
        $title = '';
        if(!empty($meta)){
            if(is_string($meta)){
                $title = $meta.' - '.config('app.name',trans('suda_lang::press.system_name'));
            }else{
            
                if(is_array($meta)){
                    $meta = arrayObject($meta);
                }
                
                if(property_exists($meta,'title')){
                    if(property_exists($meta,'settings')){
                        if(!empty($meta->title)){
                            $title = $meta->title.' - '.$meta->settings->site_name;
                        }else{
                            $title = $meta->settings->site_name;
                        }
                        
                    }else{
                        if(!empty($meta->title)){
                            $title = $meta->title.' - '.config('app.name',trans('suda_lang::press.system_name'));
                        }else{
                            $title = config('app.name',trans('suda_lang::press.system_name'));
                        }
                        
                    }
                }
            }
        
        }else{
            $title = config('app.name',trans('suda_lang::press.system_name'));
        }
        return $title.' - Powered by Suda';
    }
}


if(!function_exists('locale_url')) {
    function locale_url($to = null){
        
        if (!is_null($to)) {
            
            $locale = '';
            // if(config('app.locale') != 'en'){
            //     $locale = config('app.locale');
            // }
            
            if(substr($to,0,1)=='/'){
                if(!empty($locale)){
                    $to = '/'.$locale.$to;
                }
            }else{
                if(!empty($locale)){
                    $to = $locale.'/'.$to;
                }
            }
            
        }
        return $to;
    }
}


function is_url($path)
{
    if (! preg_match('~^(#|//|https?://|mailto:|tel:)~', $path)) {
        return filter_var($path, FILTER_VALIDATE_URL) !== false;
    }

    return true;
}

if (!function_exists('admin_url')) {
    function admin_url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            $path = 'index';
        }
        
        if(!is_url($path)){
            $admin_path = config('sudaconf.admin_path','admin');
            if(Auth::guard('operate')->user() && Auth::guard('operate')->user()->user_role==2)
            {
                $admin_path = config('sudaconf.extension_admin_path','sudaapp');
            }
            
        
            $path = $admin_path.'/'.$path;
            // if(config('app.locale') != 'en'){
            //     $path = config('app.locale').'/'.$path;
            // }
        }
        
        return url($path, $parameters, $secure);
    }
}

if (!function_exists('admin_ext_url')) {
    function admin_ext_url($path = null, $parameters = [], $secure = null)
    {
        $path = 'extension/'.$path;
        return admin_url($path,$parameters,$secure);
    }
}

if (!function_exists('extadmin_url')) {
    function extadmin_url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            $path = 'index';
        }
        
        if(!is_url($path)){
            $admin_path = config('sudaconf.extension_admin_path','sudaapp');
        
            $path = $admin_path.'/'.$path;
            // if(config('app.locale') != 'en'){
            //     $path = config('app.locale').'/'.$path;
            // }
        }
        
        return url($path, $parameters, $secure);
    }
}

if (!function_exists('web_url')) {
    function web_url($path = null, $extra = [], $secure = null)
    {
        $urlclass = app(UrlGenerator::class);
        
        if ($urlclass->isValidUrl($path)) {
            return $path;
        }

        $tail = implode('/', array_map(
            'rawurlencode', (array) $urlclass->formatParameters($extra))
        );

        $web_host = Sudacore::webHost();
        $root_url = $urlclass->formatScheme($secure).$web_host;
        
        $root = $urlclass->formatRoot($urlclass->formatScheme($secure),$root_url);

        [$path, $query] = extractQueryString($path);

        return $urlclass->format(
            $root, '/'.trim($path.'/'.$tail, '/')
        ).$query;
    }
}

if (!function_exists('api_url')) {
    function api_url($path = null, $extra = [], $secure = null)
    {
        $urlclass = app(UrlGenerator::class);
        // $urlclass->forceRootUrl(config('sudaconf.web_host'));
        
        if ($urlclass->isValidUrl($path)) {
            return $path;
        }

        $tail = implode('/', array_map(
            'rawurlencode', (array) $urlclass->formatParameters($extra))
        );

        $web_host = Sudacore::apiHost();
        $root_url = $urlclass->formatScheme($secure).$web_host;
        
        $root = $urlclass->formatRoot($urlclass->formatScheme($secure),$root_url);

        [$path, $query] = extractQueryString($path);

        return $urlclass->format(
            $root, '/'.trim($path.'/'.$tail, '/')
        ).$query;
    }
}

function extractQueryString($path)
{
    if (($queryPosition = strpos($path, '?')) !== false) {
        return [
            substr($path, 0, $queryPosition),
            substr($path, $queryPosition),
        ];
    }

    return [$path, ''];
}

if (!function_exists('arrayObject')) {
    
    function arrayObject($arr){
        
        if(is_array($arr)){
            
            foreach($arr as $k=>&$value){
                
                if(is_array($value)){
                    $value = arrayObject($value);
                }
                
                $arr[$k] = $value;
            }
            
            $arr = (object)$arr;
            return $arr;
        }
        
    }
    
}

if (!function_exists('getValue')) {
	function getValue($key, &$collection, $default = FALSE, $remove = FALSE) {
		$result = $default;
		if(is_array($collection) && array_key_exists($key, $collection)) {
			$result = $collection[$key];
            if($remove){
               unset($collection[$key]); 
            }
            
		} elseif(is_object($collection) && property_exists($collection, $key)) {
			$result = $collection->$key;
            if($remove){
                unset($collection->$key);
            }
      }

      return $result;
	}
}


if (!function_exists('extension_path')) {
    function extension_path($path = '')
    {
        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);

        return app_path($ucf_extension_dir.'/'.$path);
    }
}

if (!function_exists('extension_asset')) {
    function extension_asset($extension_name, $path='',$secure = null)
    {
        $extension_dir = config('sudaconf.extension_dir','extensions');
        $ucf_extension_dir = ucfirst($extension_dir);
        return asset($extension_dir.'/'.strtolower($extension_name).'/assets/'.$path, $secure?$secure:config('sudaconf.force_secure',null));
    }
}

function extension_logo($extension_slug,$secure=null){
    return admin_url('manage/extension/'.$extension_slug.'/logo');
}

function ext_extension_logo($extension_slug,$secure=null){
    return extadmin_url('entry/extension/'.$extension_slug.'/logo');
}

if (!function_exists('extension_menu')) {
    function extension_menu($extension, $type = null, array $options = [])
    {
        return Gtd\Suda\Models\Extension::menuDisplay($extension, $type, $options);
    }
}

if (!function_exists('is_serialized')) {
    function is_serialized( $data, $strict = true ) {
        // if it isn't a string, it isn't serialized.
        if ( ! is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( 'N;' == $data ) {
            return true;
        }
        if ( strlen( $data ) < 4 ) {
            return false;
        }
        if ( ':' !== $data[1] ) {
            return false;
        }
        if ( $strict ) {
            $lastc = substr( $data, -1 );
            if ( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $data, ';' );
            $brace     = strpos( $data, '}' );
            // Either ; or } must exist.
            if ( false === $semicolon && false === $brace )
                return false;
            // But neither must be in the first X characters.
            if ( false !== $semicolon && $semicolon < 3 )
                return false;
            if ( false !== $brace && $brace < 4 )
                return false;
        }
        $token = $data[0];
        switch ( $token ) {
            case 's' :
                if ( $strict ) {
                    if ( '"' !== substr( $data, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( false === strpos( $data, '"' ) ) {
                    return false;
                }
                // or else fall through
            case 'a' :
            case 'O' :
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b' :
            case 'i' :
            case 'd' :
                $end = $strict ? '$' : '';
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
        }
        return false;
    }
}