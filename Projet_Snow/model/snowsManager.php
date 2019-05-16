<?php
/**
 * This file contain all functions to manage the snows
 * Created by PhpStorm.
 * User: Kevin Alves
 * Date: 06.05.2017
 * Time: 09:10
 */

/**
 * This function is designed to get all active snows
 * @return array : containing all information about snows. Array can be empty.
 */
function getSnows(){
    $snowsQuery = 'SELECT code, brand, model, snowLength, dailyPrice, qtyAvailable, photo, active FROM snows';

    require_once 'model/dbConnector.php';
    $snowResults = executeQuerySelect($snowsQuery);

    return $snowResults;
}

/**
 * This function is designed to get only one snow
 * @param $snow_code : snow code to display (selected by the user)
 * @return array|null : snow to display. Can be empty.
 */
function getASnow($snow_code){
    $strgSeparator = '\'';

    // Query to get the selected snow. The active code must be set to 1 to display only snows to display. It avoids possibilty to user selecting a wrong code (get paramater in url)
    $snowQuery = 'SELECT code, brand, model, snowLength, dailyPrice, qtyAvailable, description, photo FROM snows WHERE code='.$strgSeparator.$snow_code.$strgSeparator.'AND active=1';

    require_once 'model/dbConnector.php';
    $snowResults = executeQuerySelect($snowQuery);

    return $snowResults;
}