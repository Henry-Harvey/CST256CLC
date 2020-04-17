<?php
/**
 * Data Service | app/Models/Services/Data/UserJobDataService.php
 * Data Service for accessing the User_jobs db table
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Services\Data;

use App\Models\Utility\DatabaseException;
use App\Models\Utility\Logger;
use PDO;
use PDOException;
use App\Models\Objects\UserJobModel;

class UserJobDataService implements DataServiceInterface
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
    function create($userJob)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userJob);
        try {

            $title = $userJob->getTitle();
            $company = $userJob->getCompany();
            $years = $userJob->getYears();
            $user_id = $userJob->getUser_id();

            $stmt = $this->db->prepare('INSERT INTO user_jobs
                                        (TITLE, COMPANY, YEARS, USER_ID)
                                        VALUES (:title, :company, :years, :user_id)');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':company', $company);
            $stmt->bindParam(':years', $years);
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
    function read($userJob)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userJob);
        try {
            $id = $userJob->getId();
            $stmt = $this->db->prepare('SELECT * FROM user_jobs
                                        WHERE ID = :id
                                        LIMIT 1');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $result['ID'];
            $title = $result['TITLE'];
            $company = $result['COMPANY'];
            $years = $result['YEARS'];
            $user_id = $result['USER_ID'];

            $userJob = new UserJobModel($id, $title, $company, $years, $user_id);    

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userJob . " and " . $stmt->rowCount() . " row(s) found");
            return $userJob;
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
            $stmt = $this->db->prepare('SELECT * FROM user_jobs
                                        WHERE USER_ID = :user_id');
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();          
            
            $userJob_array = array();            
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $result['ID'];
                $title = $result['TITLE'];
                $company = $result['COMPANY'];
                $years = $result['YEARS'];
                
                $userJob = new UserJobModel($id, $title, $company, $years, $user_id);     
                array_push($userJob_array, $userJob);
            }     
            
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with UserJobModel array and " . $stmt->rowCount() . " row(s) found");
            return $userJob_array;
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
    function update($userJob)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userJob);
        try {
            $title = $userJob->getTitle();
            $company = $userJob->getCompany();
            $years = $userJob->getYears();
            $user_id = $userJob->getUser_id();
            $id = $userJob->getId();

            $stmt = $this->db->prepare('UPDATE user_jobs
                                        SET TITLE = :title,
                                            COMPANY = :company,
                                            YEARS = :years,
                                            USER_ID = :user_id
                                        WHERE ID = :id');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':company', $company);
            $stmt->bindParam(':years', $years);
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
    function delete($userJob)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userJob);
        try {
            $id = $userJob->getId();

            $stmt = $this->db->prepare('DELETE FROM user_jobs
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