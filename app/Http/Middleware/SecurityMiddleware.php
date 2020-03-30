<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Utility\Logger;
use Illuminate\Support\Facades\Session;

class SecurityMiddleware
{
    /**
     * Handle an incoming request
     * If the request is global, return next
     * If the request is for logged out users
     * Check if the user is logged out
     * If so, return next
     * If not, redirect to home
     * If the request is for logged in users
     * If the request is for admins
     * Check if the user is an admin
     * If so, return next
     * If not, redirect home
     * Check if the user is logged in
     * If so, return next
     * If not, redirect to login
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with path: " . $request->path());
               
        // If the request is global, return next
        if($request->is('/')
        || $request->is('home')
        || $request->is('getProfile/*')
        || $request->is('getAllJobPostings')
        || $request->is('getJobPosting/*')){
            return $next($request);
        }
        
        // If the request is for logged out users
        if($request->is('login')
        || $request->is('register')
        || $request->is('processRegister')
        || $request->is('processLogin'))
        {
            // Check if the user is logged out
            if(!Session::get('sp')){
                // If so, return next
                //Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with success");
                return $next($request);
            }
            else{
                // If not, redirect to home
                //Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with failure");
                $data = [
                    'process' => "You must be logged out to view this page. Request ",
                    'back' => "home"
                ];
                return redirect('/home');
                //return view('error')->with($data);
            }
        }
        
        // If the request is for logged in users
        else
        {
            
            // If the request is for admins
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
                // Check if the user is an admin
                if(Session::get('sp')->getRole() != 0){
                    // If so, return next
                    //Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with success");
                    return $next($request);
                }
                else{
                    // If not, redirect home
                    //Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with failure");
                    $data = [
                        'process' => "You must be an admin to view this page. Request ",
                        'back' => "home"
                    ];
                    return redirect('/home');
                    //return view('error')->with($data);
                }
            }
            
            // Check if the user is logged in
            if(Session::get('sp')){
                // If so, return next
                //Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with success");
                return $next($request);
            }
            else{
                // If not, redirect to login
                //Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with failure");
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
