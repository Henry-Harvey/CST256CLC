<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility\Logger;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Utility\ValidationRules;
use App\Models\Services\Business\GroupBusinessService;
use App\Models\Objects\GroupModel;
use App\Models\Objects\Group_Has_UserModel;

class GroupController extends Controller
{

    /**
     * Takes in a request from newPost form
     * Creates a ValidationRules and validates the request with the post rules
     * Sets variables from the request inputs
     * Creates Post model from the variables
     * Creates an array for the post's skills
     * Creates a post skill from the first skill input and adds it to the array
     * If the other skill's requests were sent, create post skills from them and add them to the array
     * Set the post's skill array equal to the new array
     * Creates post business service
     * Calls createPost bs method
     * If flag doesnt equal 1, returns error page
     * Returns this controller's getAllPosts method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onCreateGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the post rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getGroupRules());

            // Sets variables from the request inputs
            $title = $request->input('title');
            $description = $request->input('description');

            // Creates Post model from the variables
            $group = new GroupModel("", $title, $description, Session::get('sp')->getUser_id());

            // Creates post business service
            $bs = new GroupBusinessService();

            // Calls createPost bs method
            // flag is rows affected
            $flag = $bs->createGroup($group);

            // If flag doesnt equal 1, returns error page
            if ($flag != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Group",
                    'back' => "newGroup"
                ];
                return view('error')->with($data);
            }

            // Returns this controller's getAllPosts method
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $this->onGetAllGroups();
        } catch (ValidationException $e2) {
            throw $e2;
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
    
    public function onGetGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $idToDisplay = $request->input('idToDisplay');
            
            // Calls getAllPosts bs method
            // flag is array
            $group = $this->getGroupFromId($idToDisplay);
            
            $userIsMember = false;
            foreach ($group->getMembers_array() as $member){
                if(Session::get('sp')->getUser_id() == $member->getId()){
                    $userIsMember = true;
                }
            }
            
            $data = [
                'group' => $group,
                'userIsMember' => $userIsMember
            ];
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to group view with " . $group);
            return view('group')->with($data);
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
     * Create a post business service
     * Calls getAllPosts bs method
     * If flag is empty, returns error page
     * Passes flag to allJobPostings view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetAllGroups()
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Create a post business service
            $bs = new GroupBusinessService();

            // Calls getAllPosts bs method
            // flag is array
            $flag = $bs->getAllGroups();

            // If flag is empty, returns error page
            if (empty($flag)) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . implode($flag));

                $data = [
                    'process' => "Loading Groups",
                    'back' => "getNewGroup"
                ];

                return view('error')->with($data);
            }

            // Passes flag to allJobPostings view
            $data = [
                'allGroups' => $flag
            ];
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to allGroups view");
            return view('allGroups')->with($data);
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
     * Takes in a request from allJobPostings view
     * Sets a post equal to this method's getPostFromId method, using the request input
     * Passes the post to editPost view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetEditGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Sets a post equal to this method's getPostFromId method, using the request input
            $GroupToEdit = $this->getGroupFromId($request->input('idToEdit'));
            
            // Passes the post to editPost view
            $data = [
                'groupToEdit' => $GroupToEdit
            ];
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editGroup view");
            return view('editGroup')->with($data);
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
     * Takes in a request from editPost form
     * Creates a ValidationRules and validates the request with the post edit rules
     * Sets variables equal to request inputs
     * Creates a post model from the variables
     * Creates an array for the post's skills
     * Creates a post skill from the first skill input and adds it to the array
     * If the other skill's requests were sent, create post skills from them and add them to the array
     * Set the post's skill array equal to the new array
     * Creates a post business service
     * Calls the editPost bs method with the post
     * If flag is is not 1, returns error page
     * Returns this controller's getAllPosts method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onEditGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the post edit rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getGroupRules());
            
            // Sets variables equal to request inputs
            $id = $request->input('id');
            $title = $request->input('title');
            $description = $request->input('description');
            $owner_id = $request->input('owner_id');
            
            // Creates a post model from the variables
            $group = new GroupModel($id, $title, $description, $owner_id);
            
            
            // Creates a post business service
            $bs = new GroupBusinessService();
            
            // Calls the editPost bs method with the post
            // flag is rows affected
            $flag = $bs->editGroup($group);
            
            // If flag is is not 1, returns error page
            if ($flag != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit Group",
                    'back' => "getGroups"
                ];
                return view('error')->with($data);
            }
            
            // Returns this controller's getAllPosts method
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $this->onGetAllGroups();
        } catch (ValidationException $e2) {
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with validation error");
            throw $e2;
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
    
    public function onGetTryDeleteGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a post equal to this method's getPostFromId method, using the request input
        $idToDelete = $request->input('idToDelete');
        
        $groupToDelete = $this->getGroupFromId($idToDelete);
        
        // Passes the post to the tryDeletePost view
        $data = [
            'groupToDelete' => $groupToDelete
        ];
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeleteGroup view");
        return view('tryDeleteGroup')->with($data);
    }
    
    public function onDeleteGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a post equal to this method's getPostFromId method, using the request input
        $idToDelete = $request->input('idToDelete');
        
        $partialGroup = new GroupModel($idToDelete, "", "", "");
        
        $bs = new GroupBusinessService();
        
        $flag = $bs->removeGroup($partialGroup);
        
        if($flag != 1){
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            
            $data = [
                'process' => "Deleting Group",
                'back' => "getGroups"
            ];
            return view('error')->with($data);
        }
        
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllGroups();
    }
    
    public function onGetTryJoinGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a post equal to this method's getPostFromId method, using the request input
        $group = $this->getGroupFromId($request->input('groupid'));
        
        // Passes the post to the tryDeletePost view
        $data = [
            'group' => $group
        ];
        Logger::info($group);
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryJoinGroup view");
        return view('tryJoinGroup')->with($data);
    }
    
    public function onJoinGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a post equal to this method's getPostFromId method, using the request input
        $userid = Session::get('sp')->getUser_id();
        $groupid = $request->input('groupid');
        
        $group_has_user = new Group_Has_UserModel($groupid, $userid);
        
        $bs = new GroupBusinessService();
        
        $flag = $bs->join($group_has_user);
        
        if($flag != 1){
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            
            $data = [
                'process' => "Joining Group",
                'back' => "getGroups"
            ];           
            return view('error')->with($data);
        }
        
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllGroups();
    }
    
    public function onGetTryLeaveGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a post equal to this method's getPostFromId method, using the request input
        $group = $this->getGroupFromId($request->input('groupid'));
        
        // Passes the post to the tryDeletePost view
        $data = [
            'group' => $group
        ];
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryLeaveGroup view");
        return view('tryLeaveGroup')->with($data);
    }
    
    public function onLeaveGroup(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        
        // Sets a post equal to this method's getPostFromId method, using the request input
        $userid = Session::get('sp')->getUser_id();
        $groupid = $request->input('groupid');
        
        $group_has_user = new Group_Has_UserModel($groupid, $userid);
        
        $bs = new GroupBusinessService();
        
        $flag = $bs->leave($group_has_user);
        
        if($flag != 1){
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            
            $data = [
                'process' => "Leaving Group",
                'back' => "getGroups"
            ];
            return view('error')->with($data);
        }
        
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllGroups();
    }

    /**
     * Takes in a post id
     * Creates a post with the id
     * Creates a post business service
     * Calls the bs getPost method
     * If flag is an int, returns error page
     * Returns post
     *
     * @param Integer $postid
     * @return GroupModel user
     */
    private function getGroupFromId($groupid)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Creates a post with the id
        $partialGroup = new GroupModel($groupid, "", "", 0);

        // Creates a post business service
        $bs = new GroupBusinessService();

        // Calls the bs getPost method
        // flag is either PostModel or rows found
        $flag = $bs->getGroup($partialGroup);

        // If flag is an int, returns error page
        if (is_int($flag)) {
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Group",
                'back' => "home"
            ];
            return view('error')->with($data);
        }

        $group = $flag;

        // Returns post
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group);
        return $group;
    }
}
