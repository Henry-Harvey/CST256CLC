<?php
namespace App\Models\Services\Data;

use App\Models\Utility\DatabaseException;
use App\Models\Utility\Logger;
use PDO;
use PDOException;
use App\Models\Objects\UserSkillModel;

class UserSkillDataService implements DataServiceInterface
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
    function create($userSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill);
        try {

            $skill = $userSkill->getSkill();
            $user_id = $userSkill->getUser_id();

            $stmt = $this->db->prepare('INSERT INTO user_skills
                                        (SKILL, USER_ID)
                                        VALUES (:skill, :user_id)');
            $stmt->bindParam(':skill', $skill);
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
     *
     * @see DataServiceInterface read
     */
    function read($userSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill);
        try {
            $id = $userSkill->getId();
            $stmt = $this->db->prepare('SELECT * FROM user_skills
                                        WHERE ID = :id
                                        LIMIT 1');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $skill = $result['SKILL'];
            $user_id = $result['USER_ID'];

            $userSkill = new UserSkillModel($id, $skill, $user_id);

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill . " and " . $stmt->rowCount() . " row(s) found");
            return $userSkill;
        } catch (PDOException $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }
    
    function readAllFor($user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        try {
            $user_id = $user->getId();
            $stmt = $this->db->prepare('SELECT * FROM user_skills
                                        WHERE USER_ID = :user_id');
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();        
            
            $userSkill_array = array();            
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $result['ID'];
                $skill = $result['SKILL'];
                
                $userSkill = new UserSkillModel($id, $skill, $user_id);          
                array_push($userSkill_array, $userSkill);
            }     
            
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with UserSkillModel array and " . $stmt->rowCount() . " row(s) found");
            return $userSkill_array;
        } catch (PDOException $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    // not implemented
    function readAll()
    {}

    /**
     *
     * @see DataServiceInterface update
     */
    function update($userSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill);
        try {
            $skill = $userSkill->getSkill();
            $user_id = $userSkill->getUser_id();
            $id = $userSkill->getId();

            $stmt = $this->db->prepare('UPDATE user_skills
                                        SET SKILL = :skill,
                                            USER_ID = :user_id
                                        WHERE ID = :id');
            $stmt->bindParam(':skill', $skill);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':id', $id);
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
     *
     * @see DataServiceInterface delete
     */
    function delete($userSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userSkill);
        try {
            $id = $userSkill->getId();

            $stmt = $this->db->prepare('DELETE FROM user_skills
                                        WHERE ID = :id');
            $stmt->bindParam(':id', $id);
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