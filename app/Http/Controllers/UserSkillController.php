<?php
namespace App\Http\Controllers;

use App\Models\Objects\UserSkillModel;
use App\Models\Services\Business\UserSkillBusinessService;
use App\Models\Utility\ValidationRules;
use Illuminate\Http\Request;
use App\Models\Utility\LoggerInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;

class UserSkillController extends Controller
{
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Takes in a request from newUserSkill form
     * Creates a ValidationRules and validates the request with the user skill rules
     * Sets variables from the request inputs
     * Creates UserSkill model from the variables
     * Creates UserSkill business service
     * Calls createSkill bs method
     * If flag equals 0, returns error page
     * Creates a new account controller and returns its onGetProfile method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onCreateUserSkill(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the user skill rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getUserSkillRules());

            // Sets variables from the request inputs
            $skill = $request->input('skill');

            // Creates UserSkill model from the variables
            $userSkill = new UserSkillModel(0, $skill, Session::get('sp')->getUser_id());

            // Creates UserSkill business service
            $bs = new UserSkillBusinessService();

            // Calls createSkill bs method
            // flag is rows affected
            $flag = $bs->createSkill($userSkill);

            // If flag equals 0, returns error page
            if ($flag == 0) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Skill",
                    'back' => "createUserSkill"
                ];
                return view('error')->with($data);
            }
            
            // Creates a new account controller and returns its onGetProfile method
            $c = new AccountController();
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $c->onGetProfile();
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
     * Sets a userSkill equal to this method's getUserSkillFromId method, using the request input
     * Passes the userSkill to editUserSkill view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetEditUserSkill(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Sets a userSkill equal to this method's getUserSkillFromId method, using the request input
            $userSkillToEdit = $this->getUserSkillFromId($request->input('idToEdit'));
            
            // Passes the userSkill to editUserSkill view
            $data = [
                'userSkillToEdit' => $userSkillToEdit
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editUserSkill view");
            return view('editUserSkill')->with($data);
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
     * Takes in a request from editUserSkill form
     * Creates a ValidationRules and validates the request with the user skill rules
     * Sets variables equal to request inputs
     * Creates a user skill model from the variables
     * Creates a user skill business service
     * Calls the editSkill bs method with the post
     * If flag is is not equal to 1, returns error page
     * Creates a new account controller and returns its onGetProfile method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onEditUserSkill(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the user skill rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getUserSkillRules());
            
            // Sets variables equal to request inputs
            $id = $request->input('id');
            $skill_ = $request->input('skill');
            
            // Creates a user skill model from the variables
            $skill = new UserSkillModel($id, $skill_, Session::get('sp')->getUser_id());
            
            // Creates a user skill business service
            $bs = new UserSkillBusinessService();
            
            // Calls the editSkill bs method with the post
            // flag is rows affected
            $flag = $bs->editSkill($skill);
            
            // If flag is is not equal to 1, returns error page
            if ($flag != 1) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit UserSkill",
                    'back' => "getProfile"
                ];
                return view('error')->with($data);
            }
            
            // Creates a new account controller and returns its onGetProfile method
            $c = new AccountController();
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $c->onGetProfile();
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
     * Sets a user skill equal to this method's getUserSkillFromId method, using the request input
     * Passes the user skill to the tryDeleteUserSkill view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onTryDeleteUserSkill(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a user skill equal to this method's getUserSkillFromId method, using the request input
        $skill = $this->getUserSkillFromId($request->input('idToDelete'));
        
        // Passes the user skill to the tryDeleteUserSkill view
        $data = [
            'skillToDelete' => $skill
        ];       
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeleteUserSkill view");
        return view('tryDeleteUserSkill')->with($data);
    }
    
    /**
     * Takes in a request from tryDeleteUserSkill view
     * Sets a user skill equal to this method's getUserSkillFromId method, using the request input
     * Creates a user skill business service
     * Calls the remove bs method
     * If flag is not equal to 1, returns error page
     * Creates a new account controller and returns its onGetProfile method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onDeleteUserSkill(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a user skill equal to this method's getUserSkillFromId method, using the request input
        $skill = $this->getUserSkillFromId($request->input('idToDelete'));
        
        // Creates a user skill business service
        $bs = new UserSkillBusinessService();
        
        // Calls the remove bs method
        // flag is rows affected
        $flag = $bs->remove($skill);
        
        // If flag is not equal to 1, returns error page
        if ($flag != 1) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Delete UserSkill",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        // Creates a new account controller and returns its onGetProfile method
        $c = new AccountController();
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $c->onGetProfile();
    }
    
    /**
     * Takes in an skill id
     * Creates a user skill with the id
     * Creates a user skill business service
     * Calls the bs getSkill method
     * If flag is an int, returns error page
     * Returns user skill
     *
     * @param Integer $skillid
     * @return UserSkillModel user
     */
    private function getUserSkillFromId($skillid)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Creates a user skill with the id
        $partialUserSkill = new UserSkillModel($skillid, "", "", "", "");
        
        // Creates a user skill business service
        $bs = new UserSkillBusinessService();
        
        // Calls the bs getSkill method
        // flag is either UserSkillModel or rows found
        $flag = $bs->getSkill($partialUserSkill);
        
        // If flag is an int, returns error page
        if (is_int($flag)) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Skill",
                'back' => "getProfile"
            ];
            return view('error')->with($data);
        }
        
        // Returns user skill
        $skill = $flag;       
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $skill);
        return $skill;
    }
}