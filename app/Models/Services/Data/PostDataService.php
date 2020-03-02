<?php
namespace App\Models\Services\Data;

use App\Models\Utility\DatabaseException;
use App\Models\Utility\Logger;
use PDO;
use PDOException;
use App\Models\Objects\PostModel;

class PostDataService implements DataServiceInterface
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
    function create($post)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        try {

            $title = $post->getTitle();
            $company = $post->getCompany();
            $location = $post->getLocation();
            $description = $post->getDescription();

            $stmt = $this->db->prepare('INSERT INTO posts
                                        (TITLE, COMPANY, LOCATION, DESCRIPTION)
                                        VALUES (:title, :company, :location, :description)');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':company', $company);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':description', $description);
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
    function read($post)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        try {
            $id = $post->getId();
            $stmt = $this->db->prepare('SELECT * FROM posts
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
            $company = $result['COMPANY'];
            $location = $result['LOCATION'];
            $description = $result['DESCRIPTION'];

            $post = new PostModel($id, $title, $company, $location, $description);

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post . " and " . $stmt->rowCount() . " row(s) found");
            return $post;
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
            $stmt = $this->db->prepare('SELECT * FROM posts');
            $stmt->execute();

            $post_array = array();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $result['ID'];
                $title = $result['TITLE'];
                $company = $result['COMPANY'];
                $location = $result['LOCATION'];
                $description = $result['DESCRIPTION'];

                $post = new PostModel($id, $title, $company, $location, $description);

                array_push($post_array, $post);
            }
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with PostModel array and " . $stmt->rowCount() . " row(s) found");
            return $post_array;
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
    function update($post)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        try {
            $title = $post->getTitle();
            $company = $post->getCompany();
            $location = $post->getLocation();
            $description = $post->getDescription();
            $id = $post->getId();

            $stmt = $this->db->prepare('UPDATE posts
                                        SET TITLE = :title,
                                            COMPANY = :company,
                                            LOCATION = :location,
                                            DESCRIPTION = :description
                                        WHERE ID = :id');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':company', $company);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':description', $description);
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
    function delete($post)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        try {
            $id = $post->getId();

            $stmt = $this->db->prepare('DELETE FROM posts
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