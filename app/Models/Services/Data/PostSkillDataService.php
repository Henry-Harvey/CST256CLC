<?php
/**
 * Data Service | app/Models/Services/Data/PostSkillDataService.php
 * Data Service for accessing the Post_skills db table
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Services\Data;

use App\Models\Utility\DatabaseException;
use App\Models\Utility\Logger;
use PDO;
use PDOException;
use App\Models\Objects\PostSkillModel;

class PostSkillDataService implements DataServiceInterface
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
    function create($postSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill);
        try {

            $skill = $postSkill->getSkill();
            $post_id = $postSkill->getPost_id();

            $stmt = $this->db->prepare('INSERT INTO post_skills
                                        (SKILL, POST_ID)
                                        VALUES (:skill, :post_id)');
            $stmt->bindParam(':skill', $skill);
            $stmt->bindParam(':post_id', $post_id);
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
    function read($postSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill);
        try {
            $id = $postSkill->getId();
            $stmt = $this->db->prepare('SELECT * FROM post_skills
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
            $post_id = $result['POST_ID'];

            $postSkill = new PostSkillModel($id, $skill, $post_id);

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill . " and " . $stmt->rowCount() . " row(s) found");
            return $postSkill;
        } catch (PDOException $e) {
            Logger::error("Exception ", array(
                "message" => $e->getMessage()
            ));
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with exception");
            throw new DatabaseException("Database Exception: " . $e->getMessage(), 0, $e);
        }
    }

    function readAllFor($post)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        try {
            $post_id = $post->getId();
            $stmt = $this->db->prepare('SELECT * FROM post_skills
                                        WHERE POST_ID = :post_id');
            $stmt->bindParam(':post_id', $post_id);
            $stmt->execute();

            $postSkill_array = array();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $result['ID'];
                $skill = $result['SKILL'];

                $postSkill = new PostSkillModel($id, $skill, $post_id);
                array_push($postSkill_array, $postSkill);
            }

            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with PostSkillModel array and " . $stmt->rowCount() . " row(s) found");
            return $postSkill_array;
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
    function update($postSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill);
        try {
            $skill = $postSkill->getSkill();
            $post_id = $postSkill->getPost_id();
            $id = $postSkill->getId();

            $stmt = $this->db->prepare('UPDATE post_skills
                                        SET SKILL = :skill,
                                            POST_ID = :post_id,
                                        WHERE ID = :id');
            $stmt->bindParam(':skill', $skill);
            $stmt->bindParam(':post_id', $post_id);
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
    function delete($postSkill)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $postSkill);
        try {
            $id = $postSkill->getId();

            $stmt = $this->db->prepare('DELETE FROM post_skills
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

    function deleteAllFor($post)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        try {
            $post_id = $post->getId();
            $stmt = $this->db->prepare('DELETE FROM post_skills
                                        WHERE POST_ID = :post_id');
            $stmt->bindParam(':post_id', $post_id);
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