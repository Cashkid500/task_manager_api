<?php

require '../../../config/bootstrap_file.php';
if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body);

    // Alldata sent in
    $trackid=$title=$description=$date="";
    if(isset($data->trackid)){
        $trackid=$utility_class_call::escape($data->trackid);
    }
    if(isset($data->title)){
        $title=$utility_class_call::escape($data->title);
    }
    if(isset($data->description)){
         $description=$utility_class_call::escape($data->description);
    }
    if(isset($data->date)){
         $date=$utility_class_call::escape($data->date);
    }

    // Validate input
    if ($utility_class_call::validate_input($trackid) || $utility_class_call::validate_input($title) || $utility_class_call::validate_input($description)) {
        $text = $api_response_class_call::$invalidDataSent;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to send valid data to the API fields."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode);
    }

    // validate date
    if ($utility_class_call::validateDate($date)) {
        $text = $api_response_class_call::$invalidDataSent;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to send valid start date."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode);
    }

    //call update task db
    $update = $api_tasks_table_class_call::updateTask($trackid, $title, $description, $date);

    if ( $update ){
        $maindata=[];
        $text = $api_response_class_call::$taskUpdated;
        $api_status_code_class_call->respondOK($maindata,$text);
    }
    $text = $api_response_class_call::$dbInsertError;
    $errorcode = $api_error_code_class_call::$internalUserWarning;
    $maindata = [];
    $hint = ["Ensure to send valid data to the API fields."];
    $linktosolve = "https://";
    $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode);
    } else {
        $text = $api_response_class_call::$methodUsedNotAllowed;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to use the method stated in the documentation."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondMethodNotAlowed($maindata, $text, $hint, $linktosolve, $errorcode);
    }
?>