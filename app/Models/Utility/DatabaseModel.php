<?php
/**
 * Model | app/Models/Utility/DatabaseModel.php
 * Model for holding database information
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Utility;
//This model is for retrieving a new database. Used in business services

use \PDO;

class DatabaseModel{
     
    /**
     * Creates a new pdo database
     * returns the database
     *
     * @return {@link PDO}		database that was created
     */
    function getDb(){
        $servername = config("database.connections.mysql.host");
        $port = config("database.connections.mysql.port");
        $dbname = config("database.connections.mysql.database");
        $username = config("database.connections.mysql.username");
        $password = config("database.connections.mysql.password");
        
        $db = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $db;           
    }
        
}