<?php
namespace Gtd\Suda\Auth;

enum Authority: string
{
    case general    = 'general';
    case operation  = 'operation';
    case extension  = 'extension';
    case superadmin = 'superadmin';

    public static function fromName(string $name){
        
        return constant("self::$name");
    }
}