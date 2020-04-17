<?php
/**
 * Controller | app/Htp/Controllers/UserEducationController.php
 * Controller for handling user education
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Http\Controllers;

use App\Models\Objects\UserEducationModel;
use App\Models\Utility\ValidationRules;
use Illuminate\Http\Request;
use App\Models\Utility\LoggerInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Services\Business\UserEducationBusinessService;

class UserEducationController extends Controller
{
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Takes in a request from newUserEducation form
     * Creates a ValidationRules and validates the request with the user education rules
     * Sets variables from the request inputs
     * Creates UserEducation model from the variables
     * Creates UserEducation business service
     * Calls createEducation bs method
     * If flag equals 0, returns error page
     * Creates a new account controller and returns its onGetProfile method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onCreateUserEducation(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the user education rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getUserEducationRules());

            // Sets variables from the request inputs
            $school = $request->input('school');
            $degree = $request->input('degree');
            $years = $request->input('years');

            // Creates UserEducation model from the variables
            $education = new UserEducationModel(0, $school, $degree, $years, Session::get('sp')->getUser_id());

            // Creates UserEducation business service
            $bs = new UserEducationBusinessService();

            // Calls createEducation bs method
            // flag is rows affected
            $flag = $bs->createEducation($education);

            // If flag equals 0, returns error page
            if ($flag == 0) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Education",
                    'back' => "createUserEducation"
                ];
                return view('error')->with($data);
            }
            // Creates a new account controller and returns its onGetProfile method
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return app('App\Http\Controllers\AccountController')->onGetProfile();
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
     * Takes in a request from profile view
     * Sets a userEducation equal to this method's getUserEducationFromId method, using the request input
     * Passes the userEducation to editUserEducation view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetEditUserEducation(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {            
            // Sets a userEducation equal to this method's getUserEducationFromId method, using the request input
            $userEducationToEdit = $this->getUserEducationFromId($request->input('idToEdit'));
            
            // Passes the userEducation to editUserEducation view
            $data = [
                'userEducationToEdit' => $userEducationToEdit
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editUserEducation view");
            return view('editUserEducation')->with($data);
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
     * Takes in a request from editUserEducation form
     * Creates a ValidationRules and validates the request with the user education rules
     * Sets variables equal to request inputs
     * Creates a user education model from the variables
     * Creates a user education business service
     * Calls the editEducation bs method with the post
     * If flag is is not equal to 1, returns error page
     * Creates a new account controller and returns its onGetProfile method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onEditUserEducation(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the user education rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getUserEducationRules());
            
            // Sets variables equal to request inputs
            $id = $request->input('id');
            $school = $request->input('school');
            $degree = $request->input('degree');
            $years = $request->input('years');
            
            // Creates a user education model from the variables
            $education = new UserEducationModel($id, $school, $degree, $years, Session::get('sp')->getUser_id());
            
            // Creates a user education business service
            $bs = new UserEducationBusinessService();
            
            // Calls the editEducation bs method with the post
            // flag is rows affected
            $flag = $bs->editEducation($education);
            
            // If flag is is not equal to 1, returns error page
            if ($flag != 1) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit UserEducation",
                    'back' => "getProfile"
                ];
                return view('error')->with($data);
            }
            
            // Creates a new account controller and returns its onGetProfile method
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return app('App\Http\Controllers\AccountController')->onGetProfile();
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
     * Takes in a request from Profile view
     * Sets a user education equal to this method's getUserEducationFromId method, using the request input
     * Passes the user education to the tryDeleteUserEducation view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onTryDeleteUserEducation(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a user education equal to this method's getUserEducationFromId method, using the request input
        $education = $this->getUserEducationFromId($request->input('idToDelete'));
        
        // Passes the user education to the tryDeleteUserEducation view
        $data = [
            'educationToDelete' => $education
        ];       
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeleteUserEducation view");
        return view('tryDeleteUserEducation')->with($data);
    }
    
    /**
     * Takes in a request from tryDeleteUserEducation view
     * Sets a user education equal to this method's getUserEducationFromId method, using the request input
     * Creates a user education business service
     * Calls the remove bs method
     * If flag is not equal to 1, returns error page
     * Creates a new account controller and returns its onGetProfile method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onDeleteUserEducation(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a user education equal to this method's getUserEducationFromId method, using the request input
        $education = $this->getUserEducationFromId($request->input('idToDelete'));
        
        // Creates a user education business service
        $bs = new UserEducationBusinessService();
        
        // Calls the remove bs method
        // flag is rows affected
        $flag = $bs->remove($education);
        
        // If flag is not equal to 1, returns error page
        if ($flag != 1) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Delete UserEducation",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        // Creates a new account controller and returns its onGetProfile method
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return app('App\Http\Controllers\AccountController')->onGetProfile();
    }
    
    /**
     * Takes in an education id
     * Creates a user education with the id
     * Creates a user education business service
     * Calls the bs getEducation method
     * If flag is an int, returns error page
     * Returns user education
     *
     * @param Integer $educationid
     * @return UserEducationModel user
     */
    private function getUserEducationFromId($educationid)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Creates a user education with the id
        $partialUserEducation = new UserEducationModel($educationid, "", "", "", "");
        
        // Creates a user education business service
        $bs = new UserEducationBusinessService();
        
        // Calls the bs getEducation method
        // flag is either UserEducationModel or rows found
        $flag = $bs->getEducation($partialUserEducation);
        
        // If flag is an int, returns error page
        if (is_int($flag)) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Education",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        // Returns user education
        $education = $flag;
        
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $education);
        return $education;
    }
}