<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


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
        $items = Session()->get('social_user')->user['likes']['data'];
        $length = 5;

        $page = $this->request->get('page') ?: 1;
        $offset = ($page - 1) * $length;
        $paginator = new LengthAwarePaginator(array_slice($items, $offset, $length), count($items), $length);
        $paginator->setPath($this->request->path());


        Session()->put('pagination', $paginator);

        
        return view('home');
    }
}
