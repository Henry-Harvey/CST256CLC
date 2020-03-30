<?php

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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($post_id)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $partialPost = new PostModel($post_id, "", "", "", "");
            
            $bs = new PostBusinessService();
            $post = $bs->getPost($partialPost);
            
            if (is_int($post)) {
                $dto = new DTO(- 2, "Post not found", $post);
            } else {
                $dto = new DTO(0, "OK", $post);
            }
            
            // serialize dto to json
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $bs = new PostBusinessService();

            $posts = $bs->getAllPosts();

            if (empty($posts)) {
                $dto = new DTO(- 2, "No Posts found", $posts);
            } else {
                $dto = new DTO(0, "OK", $posts);
            }

            // serialize dto to json
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
