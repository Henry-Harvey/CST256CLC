<?php
/**
 * Data Service | app/Models/Services/Data/UserEducationDataService.php
 * Data Service for accessing the User_eucation db table
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Services\Data;

use App\Models\Utility\DatabaseException;
use App\Models\Utility\Logger;
use PDO;
use PDOException;
use App\Models\Objects\UserEducationModel;

class UserEducationDataService implements DataServiceInterface
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
    function create($userEducation)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userEducation);
        try {

            $school = $userEducation->getSchool();
            $degree = $userEducation->getDegree();
            $years = $userEducation->getYears();
            $user_id = $userEducation->getUser_id();

            $stmt = $this->db->prepare('INSERT INTO user_education
                                        (SCHOOL, DEGREE, YEARS, USER_ID)
                                        VALUES (:school, :degree, :years, :user_id)');
            $stmt->bindParam(':school', $school);
            $stmt->bindParam(':degree', $degree);
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
    function read($userEducation)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userEducation);
        try {
            $id = $userEducation->getId();
            $stmt = $this->db->prepare('SELECT * FROM user_education
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
            $school = $result['SCHOOL'];
            $degree = $result['DEGREE'];
            $years = $result['YEARS'];
            $user_id = $result['USER_ID'];

            $userEducation = new UserEducationModel($id, $school, $degree, $years, $user_id);    

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userEducation . " and " . $stmt->rowCount() . " row(s) found");
            return $userEducation;
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
            $stmt = $this->db->prepare('SELECT * FROM user_education
                                        WHERE USER_ID = :user_id');
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();           
            
            $userEducation_array = array();            
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $result['ID'];
                $school = $result['SCHOOL'];
                $degree = $result['DEGREE'];
                $years = $result['YEARS'];
                
                $userEducation = new UserEducationModel($id, $school, $degree, $years, $user_id);     
                array_push($userEducation_array, $userEducation);
            }     
            
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with UserEducationModel array and " . $stmt->rowCount() . " row(s) found");
            return $userEducation_array;
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
    function update($userEducation)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userEducation);
        try {
            $school = $userEducation->getSchool();
            $degree = $userEducation->getDegree();
            $years = $userEducation->getYears();
            $user_id = $userEducation->getUser_id();
            $id = $userEducation->getId();

            $stmt = $this->db->prepare('UPDATE user_education
                                        SET SCHOOL = :school,
                                            DEGREE = :degree,
                                            YEARS = :years,
                                            USER_ID = :user_id
                                        WHERE ID = :id');
            $stmt->bindParam(':school', $school);
            $stmt->bindParam(':degree', $degree);
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
    function delete($userEducation)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $userEducation);
        try {
            $id = $userEducation->getId();

            $stmt = $this->db->prepare('DELETE FROM user_education
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