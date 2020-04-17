<?php
/**
 * Data Service | app/Models/Services/Data/UserDataService.php
 * Data Service for accessing the Users db table
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Services\Data;

use App\Models\Objects\UserModel;
use App\Models\Utility\DatabaseException;
use App\Models\Utility\Logger;
use PDO;
use PDOException;

class UserDataService implements DataServiceInterface
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
    function create($user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        try {
            $first_name = $user->getFirst_name();
            $last_name = $user->getLast_name();
            $location = $user->getLocation();
            $summary = $user->getSummary();
            $role = 0;
            $credentials_id = $user->getCredentials_id();
            $stmt = $this->db->prepare('INSERT INTO users
                                            (FIRSTNAME, LASTNAME, LOCATION, SUMMARY, ROLE, CREDENTIALS_ID)
                                            VALUES (:first_name, :last_name, :location, :summary, :role, :credentials_id)');
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':summary', $summary);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':credentials_id', $credentials_id);
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
    function read($user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        try {
            $id = $user->getId();

            $stmt = $this->db->prepare('SELECT * FROM users
                                        WHERE ID = :id
                                        LIMIT 1');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $id = $result["ID"];
            $first_name = $result['FIRSTNAME'];
            $last_name = $result['LASTNAME'];
            $location = $result['LOCATION'];
            $summary = $result['SUMMARY'];
            $role = $result['ROLE'];
            $credentials_id = $result['CREDENTIALS_ID'];

            $u = new UserModel($id, $first_name, $last_name, $location, $summary, $role, $credentials_id);

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $u . " and " . $stmt->rowCount() . " row(s) found");
            return $u;
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
     * @see DataServiceInterface readAll
     */
    function readAll()
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));
        try {
            $stmt = $this->db->prepare('SELECT * FROM users');
            $stmt->execute();

            $user_array = array();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $result["ID"];
                $first_name = $result['FIRSTNAME'];
                $last_name = $result['LASTNAME'];
                $location = $result['LOCATION'];
                $summary = $result['SUMMARY'];
                $role = $result['ROLE'];
                $credentials_id = $result['CREDENTIALS_ID'];

                $user = new UserModel($id, $first_name, $last_name, $location, $summary, $role, $credentials_id);

                array_push($user_array, $user);
            }
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with UserModel array and " . $stmt->rowCount() . " row(s) found");
            return $user_array;
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
     * @see DataServiceInterface update
     */
    function update($user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        try {
            $firstname = $user->getFirst_name();
            $lastname = $user->getLast_name();
            $location = $user->getLocation();
            $summary = $user->getSummary();
            $id = $user->getId();

            $stmt = $this->db->prepare('UPDATE users
                                        SET FIRSTNAME = :first_name, 
                                            LASTNAME = :last_name, 
                                            LOCATION = :location, 
                                            SUMMARY = :summary
                                        WHERE ID = :id');
            $stmt->bindParam(':first_name', $firstname);
            $stmt->bindParam(':last_name', $lastname);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':summary', $summary);
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
    function delete($user)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $user);
        try {
            $id = $user->getId();

            $stmt = $this->db->prepare('DELETE FROM users
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