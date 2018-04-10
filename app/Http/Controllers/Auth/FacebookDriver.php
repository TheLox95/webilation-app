<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Illuminate\Pagination\LengthAwarePaginator;


class FacebookDriver{
    protected $user;
    protected $fields = [
        'name', 'first_name', 'last_name', 'email', 'gender', 'birthday', 'likes', 'friends', 'posts.order(reverse_chronological )'
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
 
        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
 
        // Create a new Laravel collection from the array data
        $itemCollection = collect($this->user->user['likes']['data']);
 
        // Define how many items we want to be visible in each page
        $perPage = 2;
 
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
 
        // Create our paginator and pass it to the view
        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
 
        // set url path for generted links
        $paginatedItems->setPath('');

        Session()->put('pagination', $paginatedItems);
    }
}