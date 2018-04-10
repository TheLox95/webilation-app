<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


use Socialite;
use Auth;
use App\User;

class SocialAccountController extends Controller
{
    protected $redirectTo = '/home';
    protected $socialDrivers = array();

    public function __construct(){
        $this->socialDrivers['facebook'] = new FacebookDriver();
        $this->socialDrivers['twitter'] = new TwitterDriver();
    }


    public function redirectToProvider($provider)
    {
        return $this->socialDrivers[$provider]->providerRedirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $driver = $this->socialDrivers[$provider];
            $user = $driver->providerUser();

            $authUser = $this->findOrCreateUser($user, $provider);
            Auth::login($authUser, true);   
            $user['provider'] = $provider;
            Session()->put('social_user', $user);

            $driver->provideData();
                   

            return redirect('/home');
            //return redirect($this->redirectTo);
        }
        catch (\Exception $e) {  
            Session()->flash('message', $e->getMessage());    
            return redirect('/');
        }
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
    }
}
