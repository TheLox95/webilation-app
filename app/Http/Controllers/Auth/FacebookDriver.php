<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Illuminate\Pagination\LengthAwarePaginator;


class FacebookDriver{
    protected $user;
    protected $fields = [
        'name', 'first_name', 'last_name', 'email', 'gender', 'birthday', 'likes.limit(1000)', 'friends', 'posts.order(reverse_chronological )'
    ];
    protected $scopes = [
        'user_birthday', 'user_hometown', 'user_location', 'user_likes', 'user_friends', 'user_posts', 'email', 'public_profile'
    ];

    public function providerRedirect(){
        return Socialite::driver('facebook')
        ->fields($this->fields)
        ->scopes($this->scopes)->redirect();
    }

    public function providerUser(){
       $this->user = Socialite::driver('facebook')
        ->fields($this->fields)->user();
        return $this->user;
    }

    public function provideData(){
 
    }
}