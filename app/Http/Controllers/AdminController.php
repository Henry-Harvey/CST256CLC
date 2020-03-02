<?php
namespace App\Http\Controllers;

use App\Models\Objects\UserModel;
use App\Models\Services\Business\AccountBusinessService;
use Illuminate\Http\Request;
use App\Models\Utility\Logger;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\Services\Business\UserJobBusinessService;
use App\Models\Services\Business\UserSkillBusinessService;
use App\Models\Services\Business\UserEducationBusinessService;

class AdminController extends Controller
{

    /**
     * Creates an account business service
     * Calls the getAllUsers bs method
     * If flag is empty, return error page
     * Passes array of users to allUsers view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetAllUsers()
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates an account business service
            $bs = new AccountBusinessService();
            
            // Calls the getAllUsers bs method
            // flag is array
            $flag = $bs->getAllUsers();

            // If flag is empty, return error page
            if (empty($flag)) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Get All Users",
                    'back' => "home"
                ];
                return view('error')->with($data);
            }

            // Passes array of users to allUsers view
            $data = [
                'allUsers_array' => $flag
            ];
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to allUsers view");
            return view('allUsers')->with($data);
        } catch (Exception $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }

    /**
     * Takes in a request from allUsers view
     * Sets a user equal to this method's getUserFromId method, using the request input
     * Creates job, skill, & education business services
     * Calls each service's getAllFor methods and sets them equal to arrays
     * Passes arrays and user to the profile view
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetOtherProfile(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Sets a user equal to this method's getUserFromId method, using the request input
            $user = $this->getUserFromId($request->input('idToShow'));

            // Creates job, skill, & education business services
            $jobBS = new UserJobBusinessService();
            $skillBS = new UserSkillBusinessService();
            $educationBS = new UserEducationBusinessService();
            
            // Calls each service's getAllFor methods and sets them equal to arrays
            // arrays may be empty, so dont check flags
            $userJob_array = $jobBS->getAllJobsForUser($user);
            $userSkill_array = $skillBS->getAllSkillsForUser($user);
            $userEducation_array = $educationBS->getAllEducationForUser($user);
            
            // Passes arrays and user to the profile view
            $data = [
                'user' => $user,
                'userJob_array' => $userJob_array,
                'userSkill_array' => $userSkill_array,
                'userEducation_array' => $userEducation_array
            ];
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to profile view");
            return view('profile')->with($data);
        } catch (Exception $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }

    /**
     * Takes in a request from admin view
     * Sets a user equal to this method's getUserFromId method, using the request input
     * Passes the user to the tryDeleteUser view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onTryDeleteUser(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        // Sets a user equal to this method's getUserFromId method, using the request input
        $user = $this->getUserFromId($request->input('idToDelete'));

        // Passes the user to the tryDeleteUser view
        $data = [
            'userToDelete' => $user
        ];
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeleteUser view");
        return view('tryDeleteUser')->with($data);
    }

    /**
     * Takes in a request from tryDeleteUser view
     * Sets a user equal to this method's getUserFromId method, using the request input
     * Creates an account business service
     * Calls the remove bs method
     * If flag is 0, returns error page
     * Returns this method's onGetAllUsers method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onDeleteUser(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        // Sets a user equal to this method's getUserFromId method, using the request input
        $user = $this->getUserFromId($request->input('idToDelete'));

        // Creates an account business service
        $bs = new AccountBusinessService();
        
        // Calls the remove bs method
        // flag is rows affected
        $flag = $bs->remove($user);
        
        // If flag is 0, returns error page
        if ($flag == 0) {
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Remove User",
                'back' => "getAllUsers"
            ];
            return view('error')->with($data);
        }

        // Returns this method's onGetAllUsers method
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllUsers();
    }

    /**
     * Takes in a request from allUsers view
     * Sets a user equal to this method's getUserFromId method, using the request input
     * Passes the user to the tryToggleSuspension view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onTryToggleSuspension(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        // Sets a user equal to this method's getUserFromId method, using the request input
        $user = $this->getUserFromId($request->input('idToToggle'));

        // Passes the user to the tryToggleSuspension view
        $data = [
            'userToToggle' => $user
        ];
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryToggleSuspension view");
        return view('tryToggleSuspension')->with($data);
    }

    /**
     * Takes in a request from tryToggleSuspension view
     * Sets a user equal to this method's getUserFromId method, using the request input
     * Creates an account business service
     * Calls the toggleSuspend bs method
     * If flag is 0, returns error page
     * Returns this method's onGetAllUsers method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onToggleSuspension(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        // Sets a user equal to this method's getUserFromId method, using the request input
        $user = $this->getUserFromId($request->input('idToToggle'));

        // Creates an account business service
        $bs = new AccountBusinessService();
        
        // Calls the toggleSuspend bs method
        // flag is rows affected
        $flag = $bs->toggleSuspension($user);
        
        // If flag is 0, returns error page
        if ($flag == 0) {
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Toggle Suspend User",
                'back' => "getAllUsers"
            ];
            return view('error')->with($data);
        }

        // Returns this method's onGetAllUsers method
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . "with " . $flag);
        return $this->onGetAllUsers();
    }
    
    private function getUserFromSession()
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        $userid = Session::get('sp')->getUser_id();
        $partialUser = new UserModel($userid, "", "", "", "", 0, 0);
        $bs = new AccountBusinessService();
        
        // flag is either user or rows found
        $flag = $bs->getUser($partialUser);
        
        if (is_int($flag)) {
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get User",
                'back' => "home"
            ];
            return view('error')->with($data);
        }
        
        $user = $flag;
        
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        return $user;
    }
    
    /**
     * Takes in a user id
     * Creates a user with the id
     * Creates an account business service
     * Calls the bs getUser method
     * If flag is an int, returns error page
     * Returns user
     *
     * @param Integer $userid
     * @return UserModel user
     */
    private function getUserFromId($userid)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Creates a user with the id
        $partialUser = new UserModel($userid, "", "", "", "", 0, 0);
        // Creates an account business service
        $bs = new AccountBusinessService();
        
        // Calls the bs getUser method
        // flag is either user or rows found
        $flag = $bs->getUser($partialUser);
        
        // If flag is an int, returns error page
        if (is_int($flag)) {
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get User",
                'back' => "home"
            ];
            return view('error')->with($data);
        }
        
        $user = $flag;
        
        // Returns user
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        return $user;
    }

}
