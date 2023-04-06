<?php

namespace Gtd\Suda\Auth;

use Illuminate\Support\Arr;
use Gtd\Suda\Auth\Authority;
use Gtd\Suda\Models\Operate;

class OperateCan
{
    
    public static function superadmin(Operate $user): bool
    {
        return $user->superadmin==1??false;
    }

    public static function operation(Operate $user): bool
    {
        if(!$user->level)
        {
            return false;
        }
        return match($user->level)
        {
            Authority::operation->name  => true,
            Authority::superadmin->name => true,
            Authority::general->name    => false,
            Authority::extension->name  => false,
        };
    }
    
    public static function general(Operate $user): bool
    {
        if(!$user->level)
        {
            return false;
        }
        return match($user->level)
        {
            Authority::operation->name  => false,
            Authority::superadmin->name => false,
            Authority::general->name    => true,
            Authority::extension->name  => false,
        };
    }

    public static function extension(Operate $user): bool
    {
        if(!$user->level)
        {
            return false;
        }
        return match($user->level)
        {
            Authority::operation->name  => false,
            Authority::superadmin->name => false,
            Authority::general->name    => false,
            Authority::extension->name  => true,
        };
    }
    
    public static function guest(Operate $user): bool
    {
        if(!$user->level)
        {
            return true;
        }
        return match($user->level)
        {
            Authority::operation->name  => false,
            Authority::superadmin->name => false,
            Authority::general->name    => false,
            Authority::extension->name  => false,
        };
    }

    public static function getAuthoritesByLevel($level=''): array
    {
        $auths = Authority::cases();
        $values = array_column($auths, 'name');

        return match($level)
        {
            Authority::superadmin->name => $values,
            Authority::operation->name  => array_flip(Arr::except(array_flip($values),['superadmin'])),
            Authority::general->name    => array_flip(Arr::only(array_flip($values),['general'])),
            Authority::extension->name  => [],
        };
        
    }
}