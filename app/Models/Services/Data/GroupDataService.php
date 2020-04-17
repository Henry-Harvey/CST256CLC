<?php
/**
 * Data Service | app/Models/Services/Data/GroupDataService.php
 * Data Service for accessing the Groups db table
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Services\Data;

use App\Models\Utility\DatabaseException;
use App\Models\Utility\Logger;
use PDO;
use PDOException;
use App\Models\Objects\GroupModel;

class GroupDataService implements DataServiceInterface
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
    function create($group)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group);
        try {

            $title = $group->getTitle();
            $description = $group->getDescription();
            $owner_id = $group->getOwner_id();

            $stmt = $this->db->prepare('INSERT INTO groups
                                        (TITLE, DESCRIPTION, OWNER_ID)
                                        VALUES (:title, :description, :owner_id)');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':owner_id', $owner_id);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with -1. " . $stmt->rowCount() . " row(s) affected");
                return -1;
            }
            
            $insertId = $this->db->lastInsertId();
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with insertId:" . $insertId . " and " . $stmt->rowCount() . " row(s) affected");
            return $insertId;
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
    function read($group)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group);
        try {
            $id = $group->getId();
            $stmt = $this->db->prepare('SELECT * FROM groups
                                        WHERE ID = :id
                                        LIMIT 1');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() != 1) {
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $stmt->rowCount() . " row(s) found");
                return $stmt->rowCount();
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $title = $result['TITLE'];
            $description = $result['DESCRIPTION'];
            $owner_id = $result['OWNER_ID'];
            
            $group = new GroupModel($id, $title, $description, $owner_id);

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group . " and " . $stmt->rowCount() . " row(s) found");
            return $group;
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
            $stmt = $this->db->prepare('SELECT * FROM groups');
            $stmt->execute();

            $group_array = array();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $result['ID'];
                $title = $result['TITLE'];
                $description = $result['DESCRIPTION'];
                $owner_id = $result['OWNER_ID'];

                $group = new GroupModel($id, $title, $description, $owner_id);

                array_push($group_array, $group);
            }
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with GroupModel array and " . $stmt->rowCount() . " row(s) found");
            return $group_array;
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
    function update($group)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group);
        try {
            $title = $group->getTitle();
            $description = $group->getDescription();
            $owner_id = $group->getOwner_id();
            $id = $group->getId();

            $stmt = $this->db->prepare('UPDATE groups
                                        SET TITLE = :title,
                                            DESCRIPTION = :description,
                                            OWNER_ID = :owner_id
                                        WHERE ID = :id');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':owner_id', $owner_id);
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
    function delete($group)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $group);
        try {
            $id = $group->getId();

            $stmt = $this->db->prepare('DELETE FROM groups
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