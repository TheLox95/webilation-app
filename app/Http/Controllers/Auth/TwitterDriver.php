<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;


class TwitterDriver{
    protected $user;

    public function providerRedirect(){
        return Socialite::driver('twitter')->redirect();
    }

    public function providerUser(){
        $this->user = Socialite::driver('twitter')->user();
        return $this->user;
    }

    public function provideData(){
        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'     => env('TWITTER_ID'),
            'consumer_secret' => env('TWITTER_SECRET'),
            'token'           => $this->user->token,
            'token_secret'    => $this->user->tokenSecret
        ]);

        $stack->push($middleware);

        $client = new Client([
            'base_uri' => 'https://api.twitter.com/1.1/',
            'handler' => $stack,
            'auth' => 'oauth',
        ]);

        $user_info = $client->get('users/show.json',['query' => [
            'screen_name' => $this->user->nickname,
            'user_id' => $this->user->id,
        ]]);

        $res = json_decode($user_info->getBody());

        $tweets = $client->get('statuses/user_timeline.json',['query' => [
            'screen_name' => $this->user->nickname,
            'count' => '50',
        ]]);

        $res->tweets = json_decode($tweets->getBody());

        Session()->put('twitter_data', $res);
    }
}