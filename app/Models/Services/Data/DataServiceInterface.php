<?php
/**
 * Interface | app/Models/Services/Data/DataServiceInterface.php
 * Interface for data services
 *
 * @package     cst256_milestone
 * @author      Henry Harvey & Jacob Taylor
 */
namespace App\Models\Services\Data;

interface DataServiceInterface
{

    /**
     * Takes in an object
     * Sets variables for each parameter of the object
     * Creates an INSERT sql statement from the database
     * Binds the paramters of the sql statement equal to the variables
     * Executes the sql statement
     * Returns a boolean indicating success or failure
     *
     * @param
     *            newModel object to be created
     * @return {@link Integer} int of row(s) affected
     */
    function create($newModel);

    /**
     * Takes in an object
     * Sets id of the object to a variable
     * Creates a SELECT sql statement from the database
     * Binds the id paramter of the sql statement
     * Executes the sql statement
     * If a row was found, set variables for all column data
     * Create an object from the variables
     * Returns the object
     * Else return null
     *
     * @param
     *            newModel object to search for
     * @return {@link Object} object that is found
     */
    function read($searchModel);

    /**
     * Creates a SELECT sql statement from the database
     * Executes the sql statement
     * If rows were found, set variables for all column data
     * Create an object from the variables
     * Add the object to array
     * Repeat for each row
     * Returns the array
     *
     * @return {@link Array} array of objects found
     */
    function readAll();

    /**
     * Takes in an updated object
     * Sets variables for each parameter of the object
     * Creates an UPDATE sql statement from the database
     * Binds the paramters of the sql statement equal to the variables
     * Executes the sql statement
     * Returns the number of row(s) affected
     *
     * @param
     *            updatedModel object to update
     * @return {@link Integer} number of row(s) affected
     */
    function update($updatedModel);

    /**
     * Takes in an object to delete
     * Sets id of the object to a variable
     * Creates a DELETE sql statement from the database
     * Binds the id paramter of the sql statement
     * Executes the sql statement
     * Returns the number of row(s) affected
     *
     * @param
     *            deleteModel object to delete
     * @return {@link Integer} number of row(s) affected
     */
    function delete($deleteModel);
}

