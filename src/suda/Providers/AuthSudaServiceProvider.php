<?php
/**
 * AuthPressServiceProvider.php
 * description
 * date 2017-08-06 13:43:30
 * author suda <dev@gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 

namespace Gtd\Suda\Providers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Log;

class AuthSudaServiceProvider extends EloquentUserProvider
{

    /**
    * Validate a user against the given credentials.
    *
    * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
    * @param  array  $credentials
    * @return bool
    */
    
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password']; // will depend on the name of the input on the login form
        $hashedValue = $user->getAuthPassword();
        
        $salt = $user->salt;
        $password_link = config('sudaconf.password_link','zp');
        $plain = $plain.$password_link.$salt;

        return $this->hasher->check($plain, $user->getAuthPassword());
    }
    
    public function retrieveById($identifier){
        
        $model = $this->createModel();
        
        $user = $model->newQuery()
            ->where($model->getAuthIdentifierName(), $identifier)
            ->with('avatar')
            ->first();
        return $user;
    }
    
}