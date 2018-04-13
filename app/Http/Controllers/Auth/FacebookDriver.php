<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Illuminate\Pagination\LengthAwarePaginator;


class FacebookDriver{
    protected $user;
    protected $fields = [
        'name', 'first_name', 'last_name', 'email', 'gender', 'birthday', 'likes.limit(1000)', 'friends', 'posts.limit(1000).order(reverse_chronological )'
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

    public function provideData($request){

        $length = 5;

        $likes = Session()->get('social_user')->user['likes']['data'];
        $likes_page = $request->get('page') ?: 1;
        $likes_offset = ($likes_page - 1) * $length;
        $likes_paginator = new LengthAwarePaginator(array_slice($likes, $likes_offset, $length), count($likes), $length);
        $likes_paginator->setPath($request->path());

        $post = Session()->get('social_user')->user['posts']['data'];
        $post_page = $request->get('page') ?: 1;
        $post_offset = ($post_page - 1) * $length;
        $posts_paginator = new LengthAwarePaginator(array_slice($post, $post_offset, $length), count($post), $length);
        $posts_paginator->setPath($request->path());


        $sales = \Lava::DataTable();

        $sales->addDateColumn('Date')
            ->addNumberColumn('Orders');

        foreach (Session()->get('social_user')->user['likes']['data'] as $like) {
            $date = date('d-m-Y', strtotime($like['created_time']));
            if(isset($dates[$date]) == false){
                $dates[$date] = 0;
            }
            $dates[$date]++;
        }

        $biggnestNumber = max(array_values($dates));

        foreach (Session()->get('social_user')->user['likes']['data'] as $like) {
            $date = date('d-m-Y', strtotime($like['created_time']));            
            $sales->addRow([$like['created_time'], $dates[$date] ]);
        }

        \Lava::CalendarChart('Likes', $sales, [
            'title' => 'Likes',
            'unusedMonthOutlineColor' => [
                'stroke'        => '#ECECEC',
                'strokeOpacity' => 0.75,
                'strokeWidth'   => 1
            ],
            'dayOfWeekLabel' => [
                'color'    => '#4f5b0d',
                'fontSize' => 16,
                'italic'   => true
            ],
            'noDataPattern' => [
                'color' => '#DDD',
                'backgroundColor' => '#11FFFF'
            ],
            'colorAxis' => [
                'values' => [0, $biggnestNumber],
                'colors' => ['black', 'green']
            ]
        ]);
        return view('home')
        ->with('likes_paginator', $likes_paginator)
        ->with('posts_paginator', $posts_paginator);
    }
}