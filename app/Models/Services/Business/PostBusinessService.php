<?php
/**
 * Business Service | app/Models/Services/Business/PostBusinessService.php
 * Business Service for dealing with job posting logic
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Services\Business;

use App\Models\Utility\DatabaseModel;
use App\Models\Utility\Logger;
use App\Models\Services\Data\PostDataService;
use App\Models\Services\Data\PostSkillDataService;

class PostBusinessService
{

    /**
     * Takes in a post model to be created
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates post and postSkill data services
     * Calls the post data service create method with the post
     * If flag is -1, rollback, sets db to null, and returns the flag
     * For each of the post's postSkills
     * Sets the PostSkill's post_id equal to the post's insert id
     * Calls the postSkill data service create method with the postSkill
     * If the flag2 is not equal to 1, rollback, sets db to null, and returns the flag
     * Ends for each
     * Commits changes to db and sets db to null
     * Returns 1
     *
     * @param
     *            newPost post to be created
     * @return {@link Integer} number of rows affected
     */
    function createPost($newPost)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $newPost);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates post and postSkill data services
        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        // Calls the post data service create method with the post
        // flag is either -1 (rows affected != 1), or insertID
        $flag = $postDS->create($newPost);

        // If flag is -1, rollback, sets db to null, and returns the flag
        if ($flag == - 1) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $insertId = $flag;

        // For each of the post's postSkills
        foreach ($newPost->getPostSkill_array() as $postSkills) {
            // Sets the PostSkill's post_id equal to the post's insert id
            $postSkills->setPost_id($insertId);
            
            // Calls the postSkill data service create method with the postSkill
            // flag is rows affected
            $flag2 = $postSkillDS->create($postSkills);
            
            // If the flag2 is not equal to 1, rollback, sets db to null, and returns the flag
            if ($flag2 != 1) {
                Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
                return $flag2;
            }
        }
        // Ends for each

        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;

        // Returns 1
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with 1");
        return 1;
    }

    /**
     * Takes in a post model to be found
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates post and postSkill data services
     * Calls the post data service read method with the user
     * If flag is an int, rollback, sets db to null, and returns the flag
     * Calls the postSkill data service readAllFor method with the new post model
     * If flag2 is an int, rollback, sets db to null, and returns the flag
     * Set the post's postSkills to the found postSkills
     * Commits changes to db and sets db to null
     * Returns found post
     *
     * @param
     *            partialPost post to be found
     * @return {@link PostModel} post that was found
     */
    function getPost($partialPost)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialPost);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates post and postSkill data services
        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        // Calls the post data service read method with the user
        // flag is PostModel or rows found
        $flag = $postDS->read($partialPost);

        // If flag is an int, rollback, sets db to null, and returns the flag
        if (is_int($flag)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
            return $flag;
        }

        $post = $flag;

        // Calls the postSkill data service readAllFor method with the new post model
        $flag2 = $postSkillDS->readAllFor($post);

        // If flag2 is an int, rollback, sets db to null, and returns the flag
        if (is_int($flag2)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
            return $flag2;
        }

        // Set the post's postSkills to the found postSkills
        $postSkill_array = $flag2;
        $post->setPostSkill_array($postSkill_array);

        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;

        // Returns found post
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $post);
        return $post;
    }

    /**
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates post and postSkill data services
     * Calls the post data service readAll method
     * If flag is empty, rollback, sets db to null, and returns the flag
     * For each of the posts found
     * Calls the postSkill data service readAllFor method with the post
     * If flag2 is empty, rollback, sets db to null, and returns the flag
     * Set the post's postSkills to the postSkills found
     * After for each
     * Commits changes to db and sets db to null
     * Returns array of found posts
     *
     * @return {@link Array} array of posts found
     */
    function getAllPosts()
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1));

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates post and postSkill data services
        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        // Calls the post data service readAll method
        // flag is array Post models
        $flag = $postDS->readAll();

        // If flag is empty, rollback, sets db to null, and returns the flag
        if (empty($flag)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag));
            return $flag;
        }

        // For each of the posts found
        $posts_array = $flag;
        foreach ($posts_array as $post) {
            // Calls the postSkill data service readAllFor method with the post
            // flag2 is array of PostSkill models
            $flag2 = $postSkillDS->readAllFor($post);

            // If flag2 is empty, rollback, sets db to null, and returns the flag
            if (empty($flag2)) {
                Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag2));
                return $flag2;
            }

            // Set the post's postSkills to the postSkills found
            $postSkill_array = $flag2;
            $post->setPostSkill_array($postSkill_array);
        }
        // After for each

        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;

        // Returns array of found posts
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with PostModel array");
        return $posts_array;
    }

    /**
     * Takes in a post model to be updated
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates post and postSkill data services
     * Calls the post data service update method with the post
     * Calls the postSkill data service deleteAllFor method with the post
     * For each of the post's postSkills
     * Calls the postSkill data service create method with the postSkill
     * If flag3 is not equal to 1, rollback, sets db to null, and returns the flag
     * End for each
     * If flag and flag2 both equal 0, rollback, sets db to null, and returns 0
     * Commits changes to db and sets db to null
     * Returns 1
     *
     * @param
     *            updatedPost post to be updated
     * @return {@link Integer} number of rows affected
     */
    function editPost($updatedPost)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $updatedPost);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Begins a transaction
        $db->beginTransaction();

        // Creates post and postSkill data services
        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        // Calls the post data service update method with the post
        // flag is rows affected, Okay if not affected
        $flag = $postDS->update($updatedPost);
       
        // Calls the postSkill data service deleteAllFor method with the post
        // flag2 is rows affected, Okay if not affected
        $flag2 = $postSkillDS->deleteAllFor($updatedPost);
             
        // For each of the post's postSkills
        foreach ($updatedPost->getPostSkill_array() as $postSkills) {
            // Calls the postSkill data service create method with the postSkill
            // flag3 is rows affected
            $flag3 = $postSkillDS->create($postSkills);
            
            // If flag3 is not equal to 1, rollback, sets db to null, and returns the flag
            if ($flag3 != 1) {
                Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag3);
                return $flag3;
            }
        }
        // End for each
        
        // If flag and flag2 both equal 0, rollback, sets db to null, and returns 0
        if($flag == 0 && $flag2 == 0){
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with 0");
            return 0;
        }

        // Commits changes to db and sets db to null
        $db->commit();        
        $db = null;

        // Returns 1
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with 1");
        return 1;
    }

    /**
     * Takes in a post model to be deleted
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates post and postSkill data services
     * For each of the post's postSkills
     * Calls the postSkill data service delete method with the postSkill
     * If flag is not 1, rollback, sets db to null, and returns the flag
     * End for each
     * Calls the post data service delete method with the post
     * If flag2 is not 1, rollback, sets db to null, and returns the flag
     * Commits changes to db and sets db to null
     * Returns flag2
     *
     * @param
     *            partialPost post to be deleted
     * @return {@link Integer} number of rows affected
     */
    function remove($partialPost)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialPost);

        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();

        // Begins a transaction
        $db->beginTransaction();

        // Creates post and postSkill data services
        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);

        // For each of the post's postSkills
        foreach ($partialPost->getPostSkill_array() as $postSkill) {
            // Calls the postSkill data service delete method with the postSkill
            // flag is rows affected
            $flag = $postSkillDS->delete($postSkill);
            
            // If flag is not 1, rollback, sets db to null, and returns the flag
            if ($flag != 1) {
                Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag);
                return $flag;
            }
        }
        // End for each

        // Calls the post data service delete method with the post
        // flag2 is rows affected
        $flag2 = $postDS->delete($partialPost);

        // If flag2 is not 1, rollback, sets db to null, and returns the flag
        if ($flag2 != 1) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
            return $flag2;
        }

        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;

        // Returns flag2
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $flag2);
        return $flag2;
    }
    
    /**
     * Creates a new database model and gets the database from it
     * Begins a transaction
     * Creates post and postSkill data services
     * Calls the post data service readAllSearch method
     * If flag is empty, rollback, sets db to null, and returns the flag
     * For each of the posts found
     * Calls the postSkill data service readAllFor method with the post
     * If flag2 is empty, rollback, sets db to null, and returns the flag
     * Set the post's postSkills to the postSkills found
     * After for each
     * Commits changes to db and sets db to null
     * Returns array of found posts
     *
     * @return {@link Array} array of posts found
     */
    function searchPosts($partialPost)
    {
        Logger::info("\Entering " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . $partialPost);
        
        // Creates a new database model and gets the database from it
        $Database = new DatabaseModel();
        $db = $Database->getDb();
        
        // Begins a transaction
        $db->beginTransaction();
        
        // Creates post and postSkill data services
        $postDS = new PostDataService($db);
        $postSkillDS = new PostSkillDataService($db);
        
        // Calls the post data service readAllSearch method
        // flag is array Post models
        $flag = $postDS->readAllSearch($partialPost);
        
        // If flag is empty, rollback, sets db to null, and returns the flag
        if (empty($flag)) {
            Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
            $db->rollBack();
            $db = null;
            Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag));
            return $flag;
        }
        
        // For each of the posts found
        $posts_array = $flag;
        foreach ($posts_array as $post) {
            // Calls the postSkill data service readAllFor method with the post
            // flag2 is array of PostSkill models
            $flag2 = $postSkillDS->readAllFor($post);
            
            // If flag2 is empty, rollback, sets db to null, and returns the flag
            if (empty($flag2)) {
                Logger::info(substr(strrchr(__METHOD__, "\\"), 1) . " Rollback");
                $db->rollBack();
                $db = null;
                Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with " . implode($flag2));
                return $flag2;
            }
            
            // Set the post's postSkills to the postSkills found
            $postSkill_array = $flag2;
            $post->setPostSkill_array($postSkill_array);
        }
        // After for each
        
        // Commits changes to db and sets db to null
        $db->commit();
        $db = null;
        
        // Returns array of found posts
        Logger::info("/Exiting  " . substr(strrchr(__METHOD__, "\\"), 1) . " with PostModel array");
        return $posts_array;
    }
}



 
 
