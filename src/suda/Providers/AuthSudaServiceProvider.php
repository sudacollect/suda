<?php
/**
 * AuthPressServiceProvider.php
 * description
 * date 2017-08-06 13:43:30
 * author suda <hello@suda.gtd.xyz>
 * @copyright GTD. All Rights Reserved.
 */
 

namespace Gtd\Suda\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class AuthSudaServiceProvider extends EloquentUserProvider {

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
        $plain = $plain.'zp'.$salt;
        
        /**
        if ($this->hasher->needsRehash($hashedValue) && $hashedValue === md5($plain)) {
            $user->passwordnew_enc = bcrypt($plain);
            $user->save();
        }
        */
        
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