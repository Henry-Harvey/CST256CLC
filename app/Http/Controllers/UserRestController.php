<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Utility\DTO;
use App\Models\Utility\Logger;
use App\Models\Services\Business\AccountBusinessService;
use App\Models\Objects\UserModel;

class UserRestController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $partialUser = new UserModel($user_id, "", "", "", "", "", "");
            
            $bs = new AccountBusinessService();
            $user = $bs->getUser($partialUser);
            
            if (is_int($user)) {
                $dto = new DTO(- 2, "User not found", $user);
            } else {
                $dto = new DTO(0, "OK", $user);
            }
            
            // serialize dto to json
            $json = json_encode($dto);
            
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $dto);
            return $json;
        } catch (Exception $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            $dto = new DTO(- 1, $e->getMessage(), "");
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $dto);
            return json_encode($dto);
        }
    }
}
