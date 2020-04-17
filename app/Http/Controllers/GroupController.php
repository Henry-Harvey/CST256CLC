<?php
/**
 * Controller | app/Htp/Controllers/GroupController.php
 * Controller for handling groups
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility\LoggerInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Utility\ValidationRules;
use App\Models\Services\Business\GroupBusinessService;
use App\Models\Objects\GroupModel;
use App\Models\Objects\Group_Has_UserModel;

class GroupController extends Controller
{
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Takes in a request from newGroup form
     * Creates a ValidationRules and validates the request with the group rules
     * Sets variables from the request inputs
     * Creates Group model from the variables
     * Creates group business service
     * Calls createGroup bs method
     * If flag doesnt equal 1, returns error page
     * Returns this controller's getAllGroups method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onCreateGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the group rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getGroupRules());

            // Sets variables from the request inputs
            $title = $request->input('title');
            $description = $request->input('description');

            // Creates Group model from the variables
            $group = new GroupModel("", $title, $description, Session::get('sp')->getUser_id());

            // Creates group business service
            $bs = new GroupBusinessService();

            // Calls createGroup bs method
            // flag is rows affected
            $flag = $bs->createGroup($group);

            // If flag doesnt equal 1, returns error page
            if ($flag != 1) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Group",
                    'back' => "newGroup"
                ];
                return view('error')->with($data);
            }

            // Returns this controller's getAllGroups method
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $this->onGetAllGroups();
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
     * Takes in a request from allGroups view
     * Calls this controller's getGroupFromId method with the request input
     * Foreach loop to determne if the user is a member of the group
     * Sets a boolean to true if they are, false if not
     * Passes boolean and group model to the group view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {           
            // Calls this controller's getGroupFromId method with the request input
            $group = $this->getGroupFromId($request->input('idToDisplay'));
            
            // Foreach loop to determne if the user is a member of the group
            // Sets a boolean to true if they are, false if not
            $userIsMember = false;
            foreach ($group->getMembers_array() as $member){
                if(Session::get('sp')->getUser_id() == $member->getId()){
                    $userIsMember = true;
                }
            }
            
            // Passes boolean and group model to the group view
            $data = [
                'group' => $group,
                'userIsMember' => $userIsMember
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to group view with " . $group);
            return view('group')->with($data);
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
     * Create a group business service
     * Calls getAllGroups bs method
     * If flag is empty, returns error page
     * Passes flag to allGroups view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetAllGroups()
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Create a group business service
            $bs = new GroupBusinessService();

            // Calls getAllGroups bs method
            // flag is array
            $flag = $bs->getAllGroups();

            // If flag is empty, returns error page
            if (empty($flag)) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . implode($flag));
                $data = [
                    'process' => "Loading Groups",
                    'back' => "getNewGroup"
                ];

                return view('error')->with($data);
            }

            // Passes flag to allGroups view
            $data = [
                'allGroups' => $flag
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to allGroups view");
            return view('allGroups')->with($data);
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
     * Takes in a request from group view
     * Sets a group equal to this method's getGroupFromId method, using the request input
     * Passes the group to editGroup view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetEditGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Sets a group equal to this method's getGroupFromId method, using the request input
            $GroupToEdit = $this->getGroupFromId($request->input('idToEdit'));
            
            // Passes the group to editGroup view
            $data = [
                'groupToEdit' => $GroupToEdit
            ];
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editGroup view");
            return view('editGroup')->with($data);
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
     * Takes in a request from editGroup form
     * Creates a ValidationRules and validates the request with the group edit rules
     * Sets variables equal to request inputs
     * Creates a group model from the variables
     * Creates a group business service
     * Calls the editGroup bs method with the group
     * If flag is is not 1, returns error page
     * Returns this controller's getAllGroups method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onEditGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the grop edit rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getGroupRules());
            
            // Sets variables equal to request inputs
            $id = $request->input('id');
            $title = $request->input('title');
            $description = $request->input('description');
            $owner_id = $request->input('owner_id');
            
            // Creates a group model from the variables
            $group = new GroupModel($id, $title, $description, $owner_id);
            
            
            // Creates a group business service
            $bs = new GroupBusinessService();
            
            // Calls the editGroup bs method with the post
            // flag is rows affected
            $flag = $bs->editGroup($group);
            
            // If flag is is not 1, returns error page
            if ($flag != 1) {
                $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit Group",
                    'back' => "getGroups"
                ];
                return view('error')->with($data);
            }
            
            // Returns this controller's getAllGroups method
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $this->onGetAllGroups();
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
     * Takes in a request from group view
     * Sets a group equal to this method's getGroupFromId method, using the request input
     * Passes the post to the tryDeleteGroup view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetTryDeleteGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a group equal to this method's getGroupFromId method, using the request input
        $idToDelete = $request->input('idToDelete');
        
        $groupToDelete = $this->getGroupFromId($idToDelete);
        
        // Passes the post to the tryDeleteGroup view
        $data = [
            'groupToDelete' => $groupToDelete
        ];
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeleteGroup view");
        return view('tryDeleteGroup')->with($data);
    }
    
    /**
     * Takes in a request from tryDeleteGroup view
     * Creates a partial group with the id from the request input
     * Creates a group business service
     * Calls the remove group bs method
     * If flag is not equal to 1, returns error page
     * Returns this method's onGetAllGroups method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onDeleteGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Creates a partial group with the id from the request input
        $partialGroup = new GroupModel($request->input('idToDelete'), "", "", "");
        
        // Creates a group business service
        $bs = new GroupBusinessService();
        
        // Calls the remove group bs method
        // flag is rows affected
        $flag = $bs->removeGroup($partialGroup);
        
        // If flag is not equal to 1, returns error page
        if($flag != 1){
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            
            $data = [
                'process' => "Deleting Group",
                'back' => "getGroups"
            ];
            return view('error')->with($data);
        }
        
        // Returns this method's onGetAllGroups method
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllGroups();
    }
    
    /**
     * Takes in a request from group view
     * Sets a group equal to this method's getGroupFromId method, using the request input
     * Passes the post to the tryJoinGroup view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetTryJoinGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a group equal to this method's getGroupFromId method, using the request input
        $group = $this->getGroupFromId($request->input('groupid'));
        
        // Passes the post to the tryJoinGroup view
        $data = [
            'group' => $group
        ];
        $this->logger->info($group);
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryJoinGroup view");
        return view('tryJoinGroup')->with($data);
    }
    
    /**
     * Takes in a request from tryJoinGroup view
     * Creates a group_has_user using the request input for groupid and the session for userid
     * Creates a group business service
     * Calls the join group bs method
     * If flag is not equal to 1, returns error page
     * Returns this method's onGetAllGroups method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onJoinGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
             
        // Creates a group_has_user using the request input for groupid and the session for userid
        $group_has_user = new Group_Has_UserModel($request->input('groupid'), Session::get('sp')->getUser_id());
        
        // Creates a group business service
        $bs = new GroupBusinessService();
        
        // Calls the join group bs method
        $flag = $bs->join($group_has_user);
        
        // If flag is not equal to 1, returns error page
        if($flag != 1){
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            
            $data = [
                'process' => "Joining Group",
                'back' => "getGroups"
            ];           
            return view('error')->with($data);
        }
        
        // Returns this method's onGetAllGroups method
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllGroups();
    }
    
    /**
     * Takes in a request from group view
     * Sets a group equal to this method's getGroupFromId method, using the request input
     * Passes the post to the tryLeaveGroup view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetTryLeaveGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a group equal to this method's getGroupFromId method, using the request input
        $group = $this->getGroupFromId($request->input('groupid'));
        
        // Passes the post to the tryLeaveGroup view
        $data = [
            'group' => $group
        ];
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryLeaveGroup view");
        return view('tryLeaveGroup')->with($data);
    }
    
    /**
     * Takes in a request from tryLeaveGroup view
     * Creates a group_has_user using the request input for groupid and the session for userid
     * Creates a group business service
     * Calls the leave group bs method
     * If flag is not equal to 1, returns error page
     * Returns this method's onGetAllGroups method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onLeaveGroup(Request $request)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Creates a group_has_user using the request input for groupid and the session for userid
        $group_has_user = new Group_Has_UserModel($request->input('groupid'), Session::get('sp')->getUser_id());
        
        // Creates a group business service
        $bs = new GroupBusinessService();
        
        // Calls the leave group bs method
        $flag = $bs->leave($group_has_user);
        
        // If flag is not equal to 1, returns error page
        if($flag != 1){
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            
            $data = [
                'process' => "Leaving Group",
                'back' => "getGroups"
            ];
            return view('error')->with($data);
        }
        
        // Returns this method's onGetAllGroups method
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllGroups();
    }

    /**
     * Takes in a group id
     * Creates a group with the id
     * Creates a group business service
     * Calls the bs getGroup method
     * If flag is an int, returns error page
     * Returns group
     *
     * @param Integer $postid
     * @return GroupModel user
     */
    private function getGroupFromId($groupid)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Creates a group with the id
        $partialGroup = new GroupModel($groupid, "", "", 0);

        // Creates a group business service
        $bs = new GroupBusinessService();

        // Calls the bs getGroup method
        // flag is either GroupModel or rows found
        $flag = $bs->getGroup($partialGroup);

        // If flag is an int, returns error page
        if (is_int($flag)) {
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Group",
                'back' => "home"
            ];
            return view('error')->with($data);
        }

        $group = $flag;

        // Returns group
        $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group);
        return $group;
    }
}
