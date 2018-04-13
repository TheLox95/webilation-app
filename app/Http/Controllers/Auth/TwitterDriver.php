<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Auth;


class TwitterDriver{
    protected $user;

    public function providerRedirect(){
        return Socialite::driver('twitter')->redirect();
    }

    public function providerUser(){
        $this->user = Socialite::driver('twitter')->user();
        return $this->user;
    }

    public function provideData($request){
        $stack = HandlerStack::create();
        $user = Session()->get('social_user');

        $middleware = new Oauth1([
            'consumer_key'     => env('TWITTER_ID'),
            'consumer_secret' => env('TWITTER_SECRET'),
            'token'           => $user->token,
            'token_secret'    => $user->tokenSecret
        ]);

        $stack->push($middleware);

        $client = new Client([
            'base_uri' => 'https://api.twitter.com/1.1/',
            'handler' => $stack,
            'auth' => 'oauth',
        ]);

        $user_info = $client->get('users/show.json',['query' => [
            'screen_name' => $user->nickname,
            'user_id' => $user->id,
        ]]);

        $res = json_decode($user_info->getBody());

        $tweets = $client->get('statuses/user_timeline.json',['query' => [
            'screen_name' => $user->nickname,
            'count' => '50',
        ]]);

        $res->tweets = json_decode($tweets->getBody());

        Session()->put('twitter_data', $res);

        return view('home');
        
    }
}