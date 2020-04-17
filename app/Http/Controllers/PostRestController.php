<?php
/**
 * Controller | app/Htp/Controllers/PostRestController.php
 * Controller for handling post API requests
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Http\Controllers;

use Exception;
use App\Models\Utility\DTO;
use App\Models\Utility\LoggerInterface;
use App\Models\Services\Business\PostBusinessService;
use App\Models\Objects\PostModel;

class PostRestController extends Controller
{
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Takes in a post id
     * Creates a partial post from the id
     * Creates a post business service and call its getPost method
     * If the result is an int, creates a dto with post not found
     * Else creates a dto with the post
     * Serializes the dto and returns it
     *
     * @param int $post_id
     * @return \Illuminate\Http\Response
     */
    public function show($post_id)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a partial post from the id
            $partialPost = new PostModel($post_id, "", "", "", "");
            
            // Creates a post business service and call its getPost method
            $bs = new PostBusinessService();
            $post = $bs->getPost($partialPost);
            
            // If the result is an int, creates a dto with post not found
            if (is_int($post)) {
                $dto = new DTO(- 2, "Post not found", $post);
                // Else creates a dto with the post
            } else {
                $dto = new DTO(0, "OK", $post);
            }
            
            // Serializes the dto and returns it
            $json = json_encode($dto);
            
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $dto);
            return $json;
        } catch (Exception $e) {
            $this->logger->error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $dto = new DTO(- 1, $e->getMessage(), "");
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $dto);
            return json_encode($dto);
        }
    }
    
    /**
     * Creates a post business service and call its getAllPosts method
     * If the result is empty, creates a dto with post not found
     * Else creates a dto with the posts
     * Serializes the dto and returns it
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a post business service and call its getAllPosts method
            $bs = new PostBusinessService();
            $posts = $bs->getAllPosts();

            // If the result is empty, creates a dto with post not found
            if (empty($posts)) {
                $dto = new DTO(- 2, "No Posts found", $posts);
                // Else creates a dto with the posts
            } else {
                $dto = new DTO(0, "OK", $posts);
            }

            // Serializes the dto and returns it
            $json = json_encode($dto);

            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $dto);
            return $json;
        } catch (Exception $e) {
            $this->logger->error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $dto = new DTO(- 2, $e->getMessage(), "");
            $this->logger->info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $dto);
            return json_encode($dto);
        }
    }
}
