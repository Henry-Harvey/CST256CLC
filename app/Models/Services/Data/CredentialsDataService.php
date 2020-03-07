<?php
namespace App\Models\Services\Data;

use App\Models\Objects\CredentialsModel;
use App\Models\Utility\Logger;
use App\Models\Utility\DatabaseException;
use PDO;
use PDOException;
use App\Models\Objects\UserModel;

class CredentialsDataService implements DataServiceInterface
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
    function create($credentials)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $credentials);
        try {

            $username = $credentials->getUsername();
            $password = $credentials->getPassword();

            $stmt = $this->db->prepare('INSERT INTO credentials
                                        (USERNAME, PASSWORD)
                                        VALUES (:username, :password)');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            if ($stmt->rowCount() != 0) {
                $insertId = $this->db->lastInsertId();
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with insertId:" . $insertId . " and " . $stmt->rowCount() . " row(s) affected");
                return $insertId;
            } else {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with -1. " . $stmt->rowCount() . " row(s) affected");
                return - 1;
            }
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
    function read($credentials)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $credentials);
        try {
            $id = $credentials->getId();
            $stmt = $this->db->prepare('SELECT * FROM credentials
                                        WHERE ID = :id
                                        LIMIT 1');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $username = $result['USERNAME'];
            $password = $result['PASSWORD'];
            $suspended = $result['SUSPENDED'];

            $credentials = new CredentialsModel($id, $username, $password, $suspended);

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $credentials . " and " . $stmt->rowCount() . " row(s) found");
            return $credentials;
        } catch (PDOException $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    function readByUsername($credentials)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $credentials);
        try {
            $username = $credentials->getUsername();
            $stmt = $this->db->prepare('SELECT * FROM credentials
                                        WHERE USERNAME = :username
                                        LIMIT 1');
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $result['ID'];
            $password = $result['PASSWORD'];
            $suspended = $result['SUSPENDED'];

            $credentials = new CredentialsModel($id, $username, $password, $suspended);

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $credentials . " and " . $stmt->rowCount() . " row(s) found");
            return $credentials;
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
    function update($credentials)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $credentials);
        try {
            $username = $credentials->getUsername();
            $password = $credentials->getPassword();
            $suspended = $credentials->getSuspended();
            $id = $credentials->getId();

            $stmt = $this->db->prepare('UPDATE credentials
                                        SET USERNAME = :username,
                                            PASSWORD = :password,
                                            SUSPENDED = :suspended
                                        WHERE ID = :id');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':suspended', $suspended);
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
    function delete($credentials)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $credentials);
        try {
            $id = $credentials->getId();

            $stmt = $this->db->prepare('DELETE FROM credentials
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

    /**
     * Takes in a credentials model
     * Sets variables for each parameter of the object
     * Creates a SELECT sql statement from the database
     * Binds the parameters of the sql statement equal to the variables
     * Executes the sql statement
     * If no row(s) found, return null
     * If a row is found
     * Set a credentials_id equal to the selected row's id
     * Get and test the suspended column data
     * If it is not 0, return -1
     * Else, creates a second SELECT sql statement from the database
     * Binds the paramters of the sql statement equal to credentials_id
     * Executes the sql statement
     * If no row(s) found returns null
     * If rows were found, sets variables for all column data
     * Creates a user from the variables
     * Returns the user
     *
     * @param
     *            loginCredentials credentials to log in with
     * @return {@link User} user that was logged in
     */
    function authenticate($credentials)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $credentials);
        try {
            $username = $credentials->getUsername();
            $password = $credentials->getPassword();

            $stmt = $this->db->prepare('SELECT * FROM credentials
                                        WHERE BINARY USERNAME = :username AND PASSWORD = :password
                                        LIMIT 1');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $suspended = $result['SUSPENDED'];
            if ($suspended != 0) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with -1. Account suspended.");
                return - 1;
            }

            $credentials_id = $result['ID'];
            $stmt2 = $this->db->prepare('SELECT * FROM users
                                            WHERE CREDENTIALS_ID = :credentials_id');
            $stmt2->bindParam(':credentials_id', $credentials_id);
            $stmt2->execute();

            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $id = $result2["ID"];
            $first_name = $result2['FIRSTNAME'];
            $last_name = $result2['LASTNAME'];
            $location = $result2['LOCATION'];
            $summary = $result2['SUMMARY'];
            $role = $result2['ROLE'];
            $credentials_id = $result2['CREDENTIALS_ID'];
            $user = new UserModel($id, $first_name, $last_name, $location, $summary, $role, $credentials_id);

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found and " . $user);
            return $user;
        } catch (PDOException $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }
}