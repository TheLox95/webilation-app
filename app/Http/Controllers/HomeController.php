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
        $this->socialDrivers['facebook'] = new \App\Http\Controllers\Auth\FacebookDriver();
        $this->socialDrivers['twitter'] = new \App\Http\Controllers\Auth\TwitterDriver();        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session()->get('social_user')){
            return $this->socialDrivers[Session()->get('social_user')->user['provider']]->provideData($this->request);
        }else {
            return view('home');
        }
    }
}
