<?php
namespace App\Models\Services\Data;

use App\Models\Utility\DatabaseException;
use App\Models\Utility\Logger;
use PDO;
use PDOException;
use App\Models\Objects\Group_Has_UserModel;

class Group_Has_UserDataService implements DataServiceInterface
{

    private $db = NULL;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     *
     * @see DataServiceInterface create
     */
    function create($group_has_user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group_has_user);
        try {

            $group_id = $group_has_user->getGroup_id();
            $user_id = $group_has_user->getUser_id();

            $stmt = $this->db->prepare('INSERT INTO groups_has_users
                                        (GROUP_ID, USER_ID)
                                        VALUES (:group_id, :user_id)');
            $stmt->bindParam(':group_id', $group_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) affected");
            return $stmt->rowCount();
        } catch (PDOException $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * not implemented
     */
    function read($group_has_user)
    {
       
    }
    
    function readAllFor($group)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group);
        try {
            $group_id = $group->getId();
            $stmt = $this->db->prepare('SELECT * FROM groups_has_users
                                        WHERE GROUP_ID = :group_id');
            $stmt->bindParam(':group_id', $group_id);
            $stmt->execute();
            
            $group_has_user_array = array();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user_id = $result['USER_ID'];              
                $group_has_user = new Group_Has_UserModel($group_id, $user_id);
                
                array_push($group_has_user_array, $group_has_user);
            }
            
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($group_has_user_array) . " and " . $stmt->rowCount() . " row(s) found");
            return $group_has_user_array;
        } catch (PDOException $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     *
     * not implemented
     */
    function readAll()
    {
       
    }

    /**
     *
     * not implemented
     */
    function update($group_has_user)
    {
       
    }

    /**
     *
     * @see DataServiceInterface delete
     */
    function delete($group_has_user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group_has_user);
        try {
            $group_id = $group_has_user->getGroup_id();
            $user_id = $group_has_user->getUser_id();

            $stmt = $this->db->prepare('DELETE FROM groups_has_users
                                        WHERE GROUP_ID = :group_id
                                        AND USER_ID = :user_id');
            $stmt->bindParam(':group_id', $group_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) affected");
            return $stmt->rowCount();
        } catch (PDOException $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }
}