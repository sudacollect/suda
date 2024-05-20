# ABOUT
Laravel Suda - build mutiple extensions, private SaaS.


- [Official Website](https://panel.cc)
- [Documents](https://docs.gtd.xyz)
- [中文文档](https://docs.gtd.xyz)

[![Latest Stable Version](http://poser.pugx.org/gtdxyz/suda/v)](https://packagist.org/packages/gtdxyz/suda) 
[![Total Downloads](http://poser.pugx.org/gtdxyz/suda/downloads)](https://packagist.org/packages/gtdxyz/suda) 
[![Latest Unstable Version](http://poser.pugx.org/gtdxyz/suda/v/unstable)](https://packagist.org/packages/gtdxyz/suda) 
[![PHP Version Require](http://poser.pugx.org/gtdxyz/suda/require/php)](https://packagist.org/packages/gtdxyz/suda)
[![composer.lock](http://poser.pugx.org/gtdxyz/suda/composerlock)](https://packagist.org/packages/gtdxyz/suda)

Versions
---

|  Laravel   | Suda  | PHP  |
|  ----  | ----  | ----  |
| 11.x  | 11.x(dev) | 8.2+ |
| 10.x  | 10.x(dev) | 8.1+ |
| 9.x  | 9.x | 8.0.2+ |
| 8.x  | 8.x | 8.0+ |
| 7.x  | 5.2.1 | 7.2~7.4 |


## Requirments

  PHP >= 8.2+
  

## Install

After initial Laravel project folder,

```
composer require gtdxyz/suda
```

```
php artisan suda:install
# auto install include migrations,assets,themes
```

```
# edit config/app.php
# Chinse
'locale' => 'zh_CN',
'timezone' => 'Asia/Shanghai',

# edit config/auth.php

'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
	 // add operate
    'operate' => [
        'driver'    => 'session',
        'provider'  => 'operates',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
	 
	 // add provider
    'operates' => [
        'driver' => 'authsuda_provider',
        'model' => Gtd\Suda\Models\Operate::class,
    ],

    // 'users' => [
    //     'driver' => 'database',
    //     'table' => 'users',
    // ],
],

```

## Suda 11.x

|  List   | Default  | Change  |
|  ----  | ----  | ----  |
| Default Login Path  | /admin | change config/sudaconf.php |
| Login Account  | admin@suda.run |  |
| Password  | suda#2021 |  |


## Suda 10.x

|  List   | Default  | Change  |
|  ----  | ----  | ----  |
| Default Login Path  | /admin | change config/sudaconf.php |
| Login Account  | admin@suda.run |  |
| Password  | suda#2021 |  |

## Suda 9.x

|  List   | Default  | Change  |
|  ----  | ----  | ----  |
| Default Login Path  | /admin | change config/sudaconf.php |
| Login Account  | admin@gtd.xyz |  |
| Password  | suda#2021 |  |



 
