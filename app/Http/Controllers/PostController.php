<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility\Logger;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\Services\Business\PostBusinessService;
use App\Models\Utility\ValidationRules;
use App\Models\Objects\PostModel;
use App\Models\Objects\PostSkillModel;

class PostController extends Controller
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
    public function onCreatePost(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the post rules
            $vr = new ValidationRules();
            $this->validate($request, $vr->getPostRules());

            // Sets variables from the request inputs
            $title = $request->input('title');
            $company = $request->input('company');
            $location = $request->input('location');
            $description = $request->input('description');

            // Creates Post model from the variables
            $post = new PostModel(0, $title, $company, $location, $description);

            // Creates an array for the post's skills
            $postSkill_array = array();

            // Creates a post skill from the first skill input and adds it to the array
            $skill1 = $request->input('skill1');
            $postSkill1 = new PostSkillModel(0, $skill1, 0);
            array_push($postSkill_array, $postSkill1);

            // If the other skill's requests were sent, create post skills from them and add them to the array
            if ($request->input('skill2') != "") {
                $skill2 = $request->input('skill2');
                $postSkill2 = new PostSkillModel(0, $skill2, 0);
                array_push($postSkill_array, $postSkill2);
            }
            if ($request->input('skill3') != "") {
                $skill3 = $request->input('skill3');
                $postSkill3 = new PostSkillModel(0, $skill3, 0);
                array_push($postSkill_array, $postSkill3);
            }
            if ($request->input('skill4') != "") {
                $skill4 = $request->input('skill4');
                $postSkill4 = new PostSkillModel(0, $skill4, 0);
                array_push($postSkill_array, $postSkill4);
            }

            // Set the post's skill array equal to the new array
            $post->setPostSkill_array($postSkill_array);

            // Creates post business service
            $postBS = new PostBusinessService();

            // Calls createPost bs method
            // flag is rows affected
            $flag = $postBS->createPost($post);

            // If flag doesnt equal 1, returns error page
            if ($flag != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Create Post",
                    'back' => "newPost"
                ];
                return view('error')->with($data);
            }

            // Returns this controller's getAllPosts method
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $this->onGetAllPosts();
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

    /**
     * Create a post business service
     * Calls getAllPosts bs method
     * If flag is empty, returns error page
     * Passes flag to allJobPostings view
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onGetAllPosts()
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Create a post business service
            $bs = new PostBusinessService();
            
            // Calls getAllPosts bs method
            // flag is array
            $flag = $bs->getAllPosts();

            // If flag is empty, returns error page
            if (empty($flag)) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . implode($flag));
                if(Session::get('sp')->getRole() != 0){
                    $data = [
                        'process' => "Loading Job Posts",
                        'back' => "newPost"
                    ];
                }
                else{
                    $data = [
                        'process' => "Loading Job Posts",
                        'back' => "home"
                    ];
                }            
                return view('error')->with($data);
            }

            // Passes flag to allJobPostings view
            $data = [
                'allPosts' => $flag
            ];
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to allJobPostings view");
            return view('allJobPostings')->with($data);
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
    public function onGetEditPost(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Sets a post equal to this method's getPostFromId method, using the request input
            $postToEdit = $this->getPostFromId($request->input('idToEdit'));

            // Passes the post to editPost view
            $data = [
                'postToEdit' => $postToEdit
            ];
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to editPost view");
            return view('editPost')->with($data);
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
    public function onEditPost(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a ValidationRules and validates the request with the post edit rules
            $vr = new ValidationRules();
            //$this->validate($request, $vr->getPostEditRules());

            // Sets variables equal to request inputs
            $id = $request->input('id');
            $title = $request->input('title');
            $company = $request->input('company');
            $location = $request->input('location');
            $description = $request->input('description');

            // Creates a post model from the variables
            $post = new PostModel($id, $title, $company, $location, $description);

            // Creates an array for the post's skills
            $postSkill_array = array();

            // Creates a post skill from the first skill input and adds it to the array
            $skill1 = $request->input('skill1');
            $postSkill1 = new PostSkillModel(0, $skill1, $id);
            array_push($postSkill_array, $postSkill1);

            // If the other skill's requests were sent, create post skills from them and add them to the array
            if ($request->input('skill2') != "") {
                $skill2 = $request->input('skill2');
                $postSkill2 = new PostSkillModel(0, $skill2, $id);
                array_push($postSkill_array, $postSkill2);
            }
            if ($request->input('skill3') != "") {
                $skill3 = $request->input('skill3');
                $postSkill3 = new PostSkillModel(0, $skill3, $id);
                array_push($postSkill_array, $postSkill3);
            }
            if ($request->input('skill4') != "") {
                $skill4 = $request->input('skill4');
                $postSkill4 = new PostSkillModel(0, $skill4, $id);
                array_push($postSkill_array, $postSkill4);
            }

            // Set the post's skill array equal to the new array
            $post->setPostSkill_array($postSkill_array);

            // Creates a post business service
            $bs = new PostBusinessService();

            // Calls the editPost bs method with the post
            // flag is rows affected
            $flag = $bs->editPost($post);
            
            // If flag is is not 1, returns error page
            if ($flag != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
                $data = [
                    'process' => "Edit Post",
                    'back' => "getJobPostings"
                ];
                return view('error')->with($data);
            }

            // Returns this controller's getAllPosts method
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $this->onGetAllPosts();
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

    /**
     * Takes in a request from allJobPostings view
     * Sets a post equal to this method's getPostFromId method, using the request input
     * Passes the post to the tryDeletePost view
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onTryDeletePost(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        // Sets a post equal to this method's getPostFromId method, using the request input
        $post = $this->getPostFromId($request->input('idToDelete'));

        // Passes the post to the tryDeletePost view
        $data = [
            'postToDelete' => $post
        ];
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to tryDeletePost view");
        return view('tryDeletePost')->with($data);
    }

    /**
     * Takes in a request from tryDeletePost view
     * Sets a post equal to this method's getPostFromId method, using the request input
     * Creates a post business service
     * Calls the remove bs method
     * If flag is not equal to 1, returns error page
     * Returns this method's onGetAllUsers method
     *
     * @param Request $request
     *            Implicit request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory result view
     */
    public function onDeletePost(Request $request)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        // Sets a post equal to this method's getPostFromId method, using the request input
        $post = $this->getPostFromId($request->input('idToDelete'));

        // Creates a post business service
        $bs = new PostBusinessService();

        // Calls the remove bs method
        // flag is rows affected
        $flag = $bs->remove($post);
        
        // If flag is not equal to 1, returns error page
        if ($flag != 1) {
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Delete Post",
                'back' => "getJobPostings"
            ];
            return view('error')->with($data);
        }
        
        // Returns this method's onGetAllUsers method
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
        return $this->onGetAllPosts();
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
     * @return PostModel user
     */
    private function getPostFromId($postid)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        // Creates a post with the id
        $partialPost = new PostModel($postid, "", "", "", "");
        //  Creates a post business service
        $bs = new PostBusinessService();
        
        // Calls the bs getPost method
        // flag is either PostModel or rows found
        $flag = $bs->getPost($partialPost);
        
        // If flag is an int, returns error page
        if (is_int($flag)) {
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " to error view. Flag: " . $flag);
            $data = [
                'process' => "Get Post",
                'back' => "home"
            ];
            return view('error')->with($data);
        }
        
        $post = $flag;

        // Returns post
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        return $post;
    }
}
