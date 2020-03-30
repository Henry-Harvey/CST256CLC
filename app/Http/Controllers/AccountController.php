<?php
namespace App\Http\Controllers;

use App\Models\Objects\CredentialsModel;
use App\Models\Objects\UserModel;
use App\Models\Services\Business\AccountBusinessService;
use App\Models\Utility\SecurityPrinciple;
use App\Models\Utility\ValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Services\Business\UserJobBusinessService;
use App\Models\Services\Business\UserSkillBusinessService;
use App\Models\Services\Business\UserEducationBusinessService;
use App\Models\Utility\LoggerInterface;

class AccountController extends Controller
{
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Takes in a request from register form
     * Creates a ValidationRules and validates the request with the registration rules
     * Sets variables from the request inputs
     * Creates Credentials and User objects from the variables
     * Sets the user's credentials to the new credentials model
     * Creates an account business service
     * Calls the register bs method using the User object
     * If flag is 0, return error page
     * Return login page
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onRegister(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $vr = new ValidationRules();
            // Creates a ValidationRules and validates the request with the registration rules
            $this->validate($request, $vr->getRegistrationRules());

            // Sets variables from the request inputs
            $username = $request->input('username');
            $password = $request->input('password');

            $first_name = $request->input('firstname');
            $last_name = $request->input('lastname');
            $location = $request->input('location');
            $summary = $request->input('summary');

            // Creates Credentials and User objects from the variables
            $c = new CredentialsModel(0, $username, $password, 0);
            $u = new UserModel(0, $first_name, $last_name, $location, $summary, 0, 0);
            // Sets the user's credentials to the new credentials model
            $u->setCredentials($c);

            // Creates an account business service
            $bs = new AccountBusinessService();

            // Calls the register bs method using the User object
            // flag is rows affected
            $flag = $bs->register($u);

            // If flag is 0, return error page
            if ($flag != 1) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Register",
                    'back' => "register"
                ];
                return view('error')->with($data);
            }
            // Return login page
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to login view");
            return view('login');
        } catch (ValidationException $e2) {
            throw $e2;
        } catch (Exception $e) {
            $this->logger->error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }

    /**
     * Takes in a request from login form
     * Creates a ValidationRules and validates the request with the login rules
     * Sets variables from the request inputs
     * Creates Credentials object from the variables
     * Creates an account business service
     * Calls the login bs method using the User object
     * If flag is an int, returns error page
     * Creates a SecurityPrinciple with flag (user) and sets session
     * Returns home page
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onLogin(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the login rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getLoginRules());

            // Sets variables from the request inputs
            $username = $request->input('username');
            $password = $request->input('password');

            // Creates Credentials object from the variables
            $c = new CredentialsModel(0, $username, $password, 0);

            // Creates an account business service
            $bs = new AccountBusinessService();
            
            // Calls the login bs method using the User object
            // flag is either user or rows affected or -1
            $flag = $bs->login($c);

            // If flag is an int, returns error page
            if (is_int($flag)) {
                if ($flag == - 1) {
                    $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                    $data = [
                        'process' => "(Suspended) Login",
                        'back' => "login"
                    ];
                    return view('error')->with($data);
                }
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Login",
                    'back' => "login"
                ];
                return view('error')->with($data);
            }

            // Creates a SecurityPrinciple with flag (user) and sets session
            $sp = new SecurityPrinciple($flag->getId(), $flag->getFirst_name(), $flag->getRole());
            Session::put('sp', $sp);

            // Returns home page
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to home view");
            return view('home');
        } catch (ValidationException $e2) {
            throw $e2;
        } catch (Exception $e) {
            $this->logger->error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }

    /**
     * Removes securityPrinciple from session
     * Return the login view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onLogout()
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Removes securityPrinciple from session
        Session::forget('sp');
        // Return the login view
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to login view");
        return view('login');
    }

    /**
     * Sets a user equal to this method's getUserFromSession method
     * Creates job, skill, & education business services
     * Calls each service's getAllFor methods and sets them equal to arrays
     * Creates assoc array with the arrays and passes it to the profile view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetProfile()
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Sets a user equal to this method's getUserFromSession method
            $user = $this->getUserFromSession();

            // Creates job, skill, & education business services
            $jobBS = new UserJobBusinessService();
            $skillBS = new UserSkillBusinessService();
            $educationBS = new UserEducationBusinessService();
            
            // Calls each service's getAllFor methods and sets them equal to arrays
            // arrays may be empty, so dont check flags
            $userJob_array = $jobBS->getAllJobsForUser($user);
            $userSkill_array = $skillBS->getAllSkillsForUser($user);
            $userEducation_array = $educationBS->getAllEducationForUser($user);

            // Creates assoc array with the arrays and passes it to the profile view
            $data = [
                'user' => $user,
                'userJob_array' => $userJob_array,
                'userSkill_array' => $userSkill_array,
                'userEducation_array' => $userEducation_array
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to profile view");
            return view('profile')->with($data);
        } catch (Exception $e) {
            $this->logger->error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }

    /**
     * Sets a user equal to this method's getUserFromSession method
     * Passes user to editProfile view
     * 
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetEditProfile()
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Sets a user equal to this method's getUserFromSession method
            $user = $this->getUserFromSession();

            // Passes user to editProfile view
            $data = [
                'user' => $user
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editProfile view");
            return view('editProfile')->with($data);
        } catch (Exception $e) {
            $this->logger->error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }

    /**
     * Takes in a request from editProfile form
     * Creates a ValidationRules and validates the request with the profile edit rules
     * Sets variables equal to request inputs
     * Creates a user model from the variables
     * Creates a user business service
     * Calls the editUser bs method with the user
     * If flag is 0, returns error page
     * Updates session
     * Returns this controller's getProfile method
     * 
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onEditProfile(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the profile edit rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getProfileEditRules());

            // Sets variables equal to request inputs
            $id = $request->input('id');
            $first_name = $request->input('firstname');
            $last_name = $request->input('lastname');
            $location = $request->input('location');
            $summary = $request->input('summary');
            $role = $request->input('role');
            $credentials_id = $request->input('credentials_id');

            // Creates a user model from the variables
            $u = new UserModel($id, $first_name, $last_name, $location, $summary, $role, $credentials_id);

            // Creates a user business service
            $bs = new AccountBusinessService();

            // Calls the editUser bs method with the user
            // flag is rows affected
            $flag = $bs->editUser($u);
            // If flag is 0, returns error page
            if ($flag == 0) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit User",
                    'back' => "getEditProfile"
                ];
                return view('error')->with($data);
            }

            // Updates session
            $sp = Session::get('sp');
            $sp->setFirst_name($first_name);
            Session::put('sp', $sp);

            // Returns this controller's getProfile method
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to Profile view");
            return $this->onGetProfile();
        } catch (ValidationException $e2) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with validation error");
            throw $e2;
        } catch (Exception $e) {
            $this->logger->error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $data = [
                'errorMsg' => $e->getMessage()
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to exception view");
            return view('exception')->with($data);
        }
    }

    /**
     * Gets userid from session
     * Creates a user with the id
     * Creates an account business service
     * Calls the bs getUser method
     * If flag is an int, returns error page
     * Returns user
     *
     * @return UserModel user
     */
    private function getUserFromSession()
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Gets userid from session
        $userid = Session::get('sp')->getUser_id();
        // Creates a user with the id
        $partialUser = new UserModel($userid, "", "", "", "", 0, 0);
        // Creates an account business service
        $bs = new AccountBusinessService();

        // Calls the bs getUser method
        // flag is either user or rows found
        $flag = $bs->getUser($partialUser);

        // If flag is an int, returns error page
        if (is_int($flag)) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get User",
                'back' => "home"
            ];
            return view('error')->with($data);
        }

        $user = $flag;

        // Returns user
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
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
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Creates a user with the id
        $partialUser = new UserModel($userid, "", "", "", "", 0, 0);
        // Creates an account business service
        $bs = new AccountBusinessService();

        // Calls the bs getUser method
        // flag is either user or rows found
        $flag = $bs->getUser($partialUser);

        // If flag is an int, returns error page
        if (is_int($flag)) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get User",
                'back' => "home"
            ];
            return view('error')->with($data);
        }

        $user = $flag;

        // Returns user
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        return $user;
    }
}
