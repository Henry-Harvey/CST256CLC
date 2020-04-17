<?php
/**
 * Controller | app/Htp/Controllers/UserRestController.php
 * Controller for handling user API requests
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Http\Controllers;

use Exception;
use App\Models\Utility\DTO;
use App\Models\Utility\LoggerInterface;
use App\Models\Services\Business\AccountBusinessService;
use App\Models\Objects\UserModel;

class UserRestController extends Controller
{
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Takes in a user id
     * Creates a partial user from the id
     * Creates a user business service and call its getUser method
     * If the result is an int, creates a dto with user not found
     * Else creates a dto with the user
     * Serializes the dto and returns it
     *
     * @param int $user_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $this->logger->info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            // Creates a partial user from the id
            $partialUser = new UserModel($user_id, "", "", "", "", "", "");
            
            // Creates a user business service and call its getUser method
            $bs = new AccountBusinessService();
            $user = $bs->getUser($partialUser);
            
            // If the result is an int, creates a dto with user not found
            if (is_int($user)) {
                $dto = new DTO(- 2, "User not found", $user);
                // Else creates a dto with the user
            } else {
                $dto = new DTO(0, "OK", $user);
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
}
