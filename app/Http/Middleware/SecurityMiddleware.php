<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Utility\Logger;
use Illuminate\Support\Facades\Session;

class SecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with path: " . $request->path());
               
        // global routes
        if($request->is('/')
        || $request->is('home')){
            return $next($request);
        }
        
        // logged out routes
        if($request->is('login')
        || $request->is('register')
        || $request->is('processRegister')
        || $request->is('processLogin'))
        {
            if(!Session::get('sp')){
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with success");
                return $next($request);
            }
            else{
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with failure");
                $data = [
                    'process' => "You must be logged out to view this page. Request ",
                    'back' => "home"
                ];
                return redirect('/home');
                //return view('error')->with($data);
            }
        }
        
        // logged in routes
        else
        {
            
            // admin routes
            if($request->is('newPost')
                || $request->is('getOtherProfile')
                || $request->is('getAllUsers')
                || $request->is('getTryToggleSuspension')
                || $request->is('processToggleSuspension')
                || $request->is('processCreatePost')
                || $request->is('getEditPost')
                || $request->is('processEditPost')
                || $request->is('getTryDeletePost')
                || $request->is('processDeletePost'))
            {
                if(Session::get('sp')->getRole() != 0){
                    Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with success");
                    return $next($request);
                }
                else{
                    Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with failure");
                    $data = [
                        'process' => "You must be an admin to view this page. Request ",
                        'back' => "home"
                    ];
                    return redirect('/home');
                    //return view('error')->with($data);
                }
            }
            
            if(Session::get('sp')){
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with success");
                return $next($request);
            }
            else{
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with failure");
                $data = [
                    'process' => "You must be logged in to view this page. Request ",
                    'back' => "login"
                ];
                return redirect('/login');
                //return view('error')->with($data);
            }
        } 
    }
}
