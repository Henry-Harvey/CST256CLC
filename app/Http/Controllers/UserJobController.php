<?php
namespace App\Http\Controllers;

use App\Models\Objects\UserJobModel;
use App\Models\Utility\ValidationRules;
use Illuminate\Http\Request;
use App\Models\Utility\LoggerInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Services\Business\UserJobBusinessService;

class UserJobController extends Controller
{
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Takes in a request from newUserJob form
     * Creates a ValidationRules and validates the request with the user job rules
     * Sets variables from the request inputs
     * Creates UserJob model from the variables
     * Creates UserJob business service
     * Calls createJob bs method
     * If flag equals 0, returns error page
     * Creates a new account controller and returns its onGetProfile method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onCreateUserJob(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the user job rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getUserJobRules());

            // Sets variables from the request inputs
            $title = $request->input('title');
            $company = $request->input('company');
            $years = $request->input('years');

            // Creates UserJob model from the variables
            $job = new UserJobModel(0, $title, $company, $years, Session::get('sp')->getUser_id());

            // Creates UserJob business service
            $bs = new UserJobBusinessService();

            // Calls createJob bs method
            // flag is rows affected
            $flag = $bs->createJob($job);

            // If flag equals 0, returns error page
            if ($flag == 0) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Job",
                    'back' => "createUserJob"
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
     * Sets a userJob equal to this method's getUserJobFromId method, using the request input
     * Passes the userJob to editUserJob view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetEditUserJob(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Sets a userJob equal to this method's getUserJobFromId method, using the request input
            $userJobToEdit = $this->getUserJobFromId($request->input('idToEdit'));
            
            // Passes the userJob to editUserJob view
            $data = [
                'userJobToEdit' => $userJobToEdit
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editUserJob view");
            return view('editUserJob')->with($data);
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
     * Takes in a request from editUserJob form
     * Creates a ValidationRules and validates the request with the user job rules
     * Sets variables equal to request inputs
     * Creates a user job model from the variables
     * Creates a user job business service
     * Calls the editJob bs method with the post
     * If flag is is not equal to 1, returns error page
     * Creates a new account controller and returns its onGetProfile method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onEditUserJob(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the user job rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getUserJobRules());
            
            // Sets variables equal to request inputs
            $id = $request->input('id');
            $title = $request->input('title');
            $company = $request->input('company');
            $years = $request->input('years');
            
            // Creates a user job model from the variables
            $job = new UserJobModel($id, $title, $company, $years, Session::get('sp')->getUser_id());
            
            // Creates a user job business service
            $bs = new UserJobBusinessService();
            
            // Calls the editJob bs method with the post
            // flag is rows affected
            $flag = $bs->editJob($job);
            
            // If flag is is not equal to 1, returns error page
            if ($flag != 1) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit UserJob",
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
     * Sets a user job equal to this method's getUserJobFromId method, using the request input
     * Passes the user job to the tryDeleteUserJob view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onTryDeleteUserJob(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a user job equal to this method's getUserJobFromId method, using the request input
        $job = $this->getUserJobFromId($request->input('idToDelete'));
        
        // Passes the user job to the tryDeleteUserJob view
        $data = [
            'jobToDelete' => $job
        ];       
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeleteUserJob view");
        return view('tryDeleteUserJob')->with($data);
    }
    
    /**
     * Takes in a request from tryDeleteUserJob view
     * Sets a user job equal to this method's getUserJobFromId method, using the request input
     * Creates a user job business service
     * Calls the remove bs method
     * If flag is not equal to 1, returns error page
     * Creates a new account controller and returns its onGetProfile method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onDeleteUserJob(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a user job equal to this method's getUserJobFromId method, using the request input
        $job = $this->getUserJobFromId($request->input('idToDelete'));
        
        // Creates a user job business service
        $bs = new UserJobBusinessService();
        
        // Calls the remove bs method
        // flag is rows affected
        $flag = $bs->remove($job);
        
        // If flag is not equal to 1, returns error page
        if ($flag != 1) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Delete UserJob",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        // Creates a new account controller and returns its onGetProfile method
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return app('App\Http\Controllers\AccountController')->onGetProfile();
    }
       
    /**
     * Takes in an job id
     * Creates a user job with the id
     * Creates a user job business service
     * Calls the bs getJob method
     * If flag is an int, returns error page
     * Returns user job
     *
     * @param Integer $jobid
     * @return UserJobModel user
     */
    private function getUserJobFromId($jobid)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Creates a user job with the id
        $partialUserJob = new UserJobModel($jobid, "", "", "", "");
        
        // Creates a user job business service
        $bs = new UserJobBusinessService();
        
        // Calls the bs getJob method
        // flag is either UserJobModel or rows found
        $flag = $bs->getJob($partialUserJob);
        
        // If flag is an int, returns error page
        if (is_int($flag)) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Job",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        // Returns user job
        $job = $flag;
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $job);
        return $job;
    }
    
}