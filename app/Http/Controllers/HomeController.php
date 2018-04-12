<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;



class HomeController extends Controller
{
    protected $request;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session()->get('social_user')){
            $length = 5;

        $likes = Session()->get('social_user')->user['likes']['data'];
        $likes_page = $this->request->get('page') ?: 1;
        $likes_offset = ($likes_page - 1) * $length;
        $likes_paginator = new LengthAwarePaginator(array_slice($likes, $likes_offset, $length), count($likes), $length);
        $likes_paginator->setPath($this->request->path());

        $post = Session()->get('social_user')->user['posts']['data'];
        $post_page = $this->request->get('page') ?: 1;
        $post_offset = ($post_page - 1) * $length;
        $posts_paginator = new LengthAwarePaginator(array_slice($post, $post_offset, $length), count($post), $length);
        $posts_paginator->setPath($this->request->path());


        $sales = \Lava::DataTable();

        $sales->addDateColumn('Date')
            ->addNumberColumn('Orders');

        foreach (Session()->get('social_user')->user['likes']['data'] as $like) {
            for ($a=0; $a < 20; $a++) {
                $day = rand(1, 30);
                $sales->addRow([$like['created_time'], rand(0,100)]);
            }
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
                'values' => [0, 100],
                'colors' => ['black', 'green']
            ]
        ]);
        return view('home')
        ->with('likes_paginator', $likes_paginator)
        ->with('posts_paginator', $posts_paginator);
        }else {
            return view('home');
        }
    }
}
