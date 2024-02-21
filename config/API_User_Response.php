<?php

namespace Config;

use Config\Constants;
/**
 * System Messages Class
 *
 * PHP version 5.4
 */
class API_User_Response
{

    /**
     * Welcome message
     *
     * @var string
     */
    // General errors
    public  static $methodUsedNotAllowed="Method Used is not valid";
    public  static $invalidDataSent="Please send correct data";
    public  static $invalidUserDetail="Invalid username or password";
    public  static $taskCreated="Task created successfully";
    public  static $taskDeleted="Task deleted successfully";
    public  static $taskUpdated="Task updated successfully";
    public  static $unauthorized_token="Unauthorized";

    public static $welcomeMessage = "Welcome to " . Constants::APP_NAME;
   
    //  login fail  
    public  static $loginFailedError="one or both of the data provided is invalid";

    // forgot password
    public  static $forgotMailSent="Recovery Mail sent successfully, kindly check your mail";
    public  static $errorOccured="An Error occured, Please contact support";
    public static $dbInsertError="An Error occured, Please contact support";
    public static $getRequestFetched="Data fetched successfully";
    public static $getRequestNoRecords="No records found";


    
}