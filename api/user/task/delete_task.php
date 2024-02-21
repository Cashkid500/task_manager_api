<?php

require_once '../../../config/bootstrap_file.php';
if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body);

    // Alldata sent in
    $trackid= "";
    if(isset($data->trackid)){
        $trackid=$utility_class_call::escape($data->trackid);
    }

    // Validate input
    if ($utility_class_call::validate_input($trackid)) {
        $text = $api_response_class_call::$invalidDataSent;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to send valid data to the API fields."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode);
    }

    //check if the trackid exist in db
    $check = $utility_class_call::checkIfExist("tasks", "trackid" ,$trackid);

    if(!$check){
        $text = $api_response_class_call::$getRequestNoRecords;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to send valid data to the API fields."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode); 
    } 

    //call delete task db
    $delete = $api_tasks_table_class_call::deleteTask($trackid);

    if ( $delete ){
        $maindata=[];
        $text = $api_response_class_call::$taskDeleted;
        $api_status_code_class_call->respondOK($maindata,$text);
    }
        $text = $api_response_class_call::$dbInsertError;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to send valid data to the API fields."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode);
    } else {
        $text = $api_response_class_call::$dbInsertError;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to send valid data to the API fields."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode);
    }

?>