<?php

namespace DatabaseCall;

use Config;

/**
 * Post model
 *
 * PHP version 5.4
 */
class Tasks_Table extends Config\DB_Connect
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
    public static function getTask($page, $offset, $noPerPage, $sort ,$search,$paramString, $params = [])
    {
        //input type checks if its from post request or just normal function call
        $connect = static::getDB();
        $alldata = [];
        $minid = 0;


        // echo json_encode($params);
        $query = "SELECT * FROM tasks WHERE id > ? $search";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s".$paramString, $minid, ...$params);
        $stmt->execute();
        $getResult = $stmt->get_result();
        $total_numRow = $getResult->num_rows;
        $total_pages = ceil($total_numRow / $noPerPage);

        $paramString .= "ss";
        $params[] = $offset;
        $params[] = $page;
        $query = "$query ORDER BY id DESC LIMIT ?,?";
        $stmt= $connect->prepare($query);
        $stmt->bind_param("s".$paramString, $minid, ...$params);
        $stmt->execute();
        $result= $stmt->get_result();
        $numRow = $result->num_rows;

        if($numRow > 0){
            while($row = $result->fetch_assoc()){
                // unset all variables needed
                if ( $row['status'] == 1 ){
                    $row['status_value'] = "Completed";

                }elseif( $row['status'] == 0 ){
                    $row['status_value'] = "Ongoing";

                }else{
                    $row['status_value'] = "";
                }
                $data = json_decode(json_encode($row), true);
                array_push($alldata, $data);
            }

            $allTask = [
                'page' => $page,
                'per_page' => $noPerPage,
                'total_data' => $total_numRow,
                'totalPage' => $total_pages,
                'invites'=> $alldata,
            ];
            return $allTask;
        }
        return $alldata;

    }
    public static function createTask($trackid, $title, $description, $date){
        //SELECT `id`, `trackid`, `title`, `description`, `start_date`, `end_date`, `status`, `created_at`, `updated_at` FROM `tasks` WHERE 1
        $connect = static::getDB();
        $alldata = [];
        $status = 0;
        // $date = $date : date("Y-m-d H:i:s");

        $query = "INSERT INTO `tasks`(`trackid`, `title`, `description`, `date`,  `status`) VALUES (? , ?, ?,  ?, ?)";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("sssss", $trackid, $title, $description, $date, $status);
        $exe = $stmt->execute();
        
        if ( $exe){
            return true;
        }

        return false;

    }

    public static function updateTask($trackid, $title, $description, $date){
        //SELECT `id`, `trackid`, `title`, `description`, `start_date`, `end_date`, `status`, `created_at`, `updated_at` FROM `tasks` WHERE 1
        $connect = static::getDB();
        $alldata = [];
        $status = 0;
        // $date = $date : date("Y-m-d H:i:s");

        $query = "UPDATE `tasks` SET `title` = ?, `description` = ?, `date` =? WHERE trackid = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("ssss", $title, $description, $date, $trackid);
        $exe = $stmt->execute();
        
        if ( $exe){
            return true;
        }

        return false;

    }

    public static function deleteTask($trackid){
   
        $connect = static::getDB();
        $alldata = [];
        $query = "DELETE FROM `tasks` WHERE trackid = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $trackid);
        $exe = $stmt->execute();
        
        if ( $exe){
            return true;
        }

        return false;
    }



}
