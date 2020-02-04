<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\Services\Business\UserBusinessService;
use App\Models\UserModel;
use App\Models\CredentialsModel;

class AccountController extends Controller
{

    /**
     * Controller method that takes in a request
     * Sets the data from the request to variables
     * Creates a Calculation object from the variables
     * Creates an associative array with the object
     * Returns result view and pushes the data array
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onRegister(Request $request)
    {
        Log::info("Entering AccountController.onRegister()");
        try {
            $username = $request->input('username');
            $password = $request->input('password');

            $first_name = $request->input('firstname');
            $last_name = $request->input('lastname');
            $location = $request->input('location');
            $summary = $request->input('summary');

            $c = new CredentialsModel(0, $username, $password);

            $u = new UserModel(0, $first_name, $last_name, $location, $summary, 0, 0);

            $bs = new UserBusinessService();

            $flag = $bs->register($c, $u);

            Log::info("Exiting AccountController.onRegister() with " . $flag);
            if ($flag == 1) {
                return view('login');
            } else {
                return view('register');
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            return view('exception')->with($data);
        }
    }

    public function onLogin(Request $request)
    {
        Log::info("Entering AccountController.onLogin()");
        try {
            $username = $request->input('username');
            $password = $request->input('password');

            $c = new CredentialsModel(0, $username, $password);

            $bs = new UserBusinessService();

            $flag = $bs->login($c);

            Log::info("Exiting AccountController.onLogin() with " . $flag);
            if ($flag != null) {

                if(is_int($flag)){
                    $data = [
                        'errorMsg' => "Account suspended"
                    ];
                    return view('loginFailed')->with($data);
                }
                Session::put('user_id', $flag->getId());
                Session::put('role', $flag->getRole());

                return view('home');
            } else {
                $data = [
                    'errorMsg' => "Account does not exist"
                ];
                return view('loginFailed')->with($data);
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            return view('exception')->with($data);
        }
    }

    public function onLogout()
    {
        Log::info("Entering AccountController.onLogout()");
        Session::forget('user_id');
        Session::forget('role');
        Log::info("Exiting AccountController.onLogout()");
        return view('login');
    }

    public function onGetAllUsers()
    {
        Log::info("Entering AccountController.onGetAllUsers()");
        try {
            $bs = new UserBusinessService();

            $flag = $bs->getAllUsers();

            Log::info("Exiting AccountController.onGetAllUsers()");
            if ($flag != null) {
                $data = [
                    'allUsers' => $flag
                ];
                return view('admin')->with($data);
            } else {
                return view('home');
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            return view('exception')->with($data);
        }
    }
    
    public function onGetProfile()
    {
        Log::info("Entering AccountController.onGetProfile()");
        try {
            $bs = new UserBusinessService();
            
            $userid = Session::get('user_id');
            
            $user = new UserModel($userid, "", "", "", "", 0, 0);
            
            $flag = $bs->getUser($user);
            
            Log::info("Exiting AccountController.onGetProfile()");
            if ($flag != null) {
                $data = [
                    'user' => $flag
                ];
                return view('profile')->with($data);
            } else {
                return view('home');
            }
        } catch (Exception $e) {
            Log::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            return view('exception')->with($data);
        }
    }

    public function onTryDeleteUser(Request $request)
    {
        Log::info("Entering AccountController.onTryDeleteUser()");
        $idToDelete = $request->input('idToDelete');
        $userToDelete = new UserModel($idToDelete, "", "", "", "", 0, 0);

        $bs = new UserBusinessService();

        $flag = $bs->getUser($userToDelete);

        $data = [
            'userToDelete' => $flag
        ];
        Log::info("Exiting AccountController.onTryDeleteUser()");
        return view('tryDelete')->with($data);
    }

    public function onDeleteUser(Request $request)
    {
        Log::info("Entering AccountController.onDeleteUser()");
        $idToDelete = $request->input('idToDelete');
        $userToDelete = new UserModel($idToDelete, "", "", "", "", 0, 0);
        
        $bs = new UserBusinessService();
        
        $user = $bs->getUser($userToDelete);
        
        $flag = $bs->remove($user);
        Log::info("Exiting AccountController.onDeleteUser() with" . $flag);
        return $this->onGetAllUsers();
    }

    public function onToggleSuspendUser(Request $request)
    {
        Log::info("Entering AccountController.onToggleSuspendUser()");
        $idToToggle = $request->input('idToToggle');
        $userToToggle = new UserModel($idToToggle, "", "", "", "", 0, 0);

        $bs = new UserBusinessService();

        $user = $bs->getUser($userToToggle);

        $flag = $bs->toggleSuspendUser($user);

        Log::info("Exiting AccountController.onToggleSuspendUser() with " . $flag);
        return $this->onGetAllUsers();
    }
}
