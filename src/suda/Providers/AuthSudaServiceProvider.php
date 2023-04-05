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
use Illuminate\Support\Facades\Log;

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
        $password_link = config('sudaconf.password_link','zp');
        $plain = $plain.$password_link.$salt;
        
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


    public function __invoke(mixed $var)
    {

        
        
        // $provider = $this->createUserProvider($config['provider'] ?? null);

        // $guard = new SessionGuard(
        //     $name,
        //     $provider,
        //     $this->app['session.store'],
        // );

        // // When using the remember me functionality of the authentication services we
        // // will need to be set the encryption instance of the guard, which allows
        // // secure, encrypted cookie values to get generated for those cookies.
        // if (method_exists($guard, 'setCookieJar')) {
        //     $guard->setCookieJar($this->app['cookie']);
        // }

        // if (method_exists($guard, 'setDispatcher')) {
        //     $guard->setDispatcher($this->app['events']);
        // }

        // if (method_exists($guard, 'setRequest')) {
        //     $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
        // }

        // if (isset($config['remember'])) {
        //     $guard->setRememberDuration($config['remember']);
        // }

        // return $guard;
    }
}