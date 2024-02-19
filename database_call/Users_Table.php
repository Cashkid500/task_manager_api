<?php

namespace DatabaseCall;

use Config;

/**
 * Post model
 *
 * PHP version 5.4
 */
class Users_Table extends Config\DB_Connect
{
    /**
     * Get all the posts as an associative array
     *
     * @return array
     */

    /*
    If a data is not needed send empty to it, bank name and namk code should be join as bankname^bankcode

     */
    // APi functions
    public static function getUserByUsername($username = "",$data="*")
    {
        //input type checks if its from post request or just normal function call
        $connect = static::getDB();
        $alldata = [];

        $checkdata = $connect->prepare("SELECT $data FROM users WHERE username = ?");
        $checkdata->bind_param("s", $username);
        $checkdata->execute();
        $getresultemail = $checkdata->get_result();
        if ($getresultemail->num_rows > 0) {
            $getthedata = $getresultemail->fetch_assoc();
            $alldata = $getthedata;
        }
        return $alldata;

    }
    public static function getUserByIdAndEmail($username = "",$data="*")
    {
        //input type checks if its from post request or just normal function call
        $connect = static::getDB();
        $alldata = [];

        $checkdata = $connect->prepare("SELECT  $data FROM users WHERE id = ? || email=?");
        $checkdata->bind_param("ss", $username, $username);
        $checkdata->execute();
        $getresultemail = $checkdata->get_result();
        if ($getresultemail->num_rows > 0) {
            $getthedata = $getresultemail->fetch_assoc();
            $alldata = $getthedata;
        }
        return $alldata;

    }



}
