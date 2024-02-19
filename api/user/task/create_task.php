<?php

require_once '../../../config/bootstrap_file.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // add try and catch
    // Get the request body
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body);
    // Alldata sent in
    $name=$description=$startDate=$endDate="";
    if(isset($data->name)){
        $name=$utility_class_call::escape($data->name);
    }
    if(isset($data->description)){
         $description=$utility_class_call::escape($data->description);
    }
    if(isset($data->startDate)){
         $startDate=$utility_class_call::escape($data->startDate);
    }
    if(isset($data->endDate)){
         $endDate=$utility_class_call::escape($data->endDate);
    }

    // Validate input
    if ($utility_class_call::validate_input($name) || $utility_class_call::validate_input($description)) {
        $text = $api_response_class_call::$invalidDataSent;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to send valid data to the API fields."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode);
    }

    // validate start date
    if ($startDate && $utility_class_call::validateDate($startDate)) {
        $text = $api_response_class_call::$invalidDataSent;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to send valid start date."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode);
    }

    // validate end date
    if ($endDate && $utility_class_call::validateDate($endDate)) {
        $text = $api_response_class_call::$invalidDataSent;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to send valid end date."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondBadRequest($maindata,$text,$hint,$linktosolve,$errorcode);
    }

    //call add task db    
    $track_id = $utility_class_call::generateUniqueShortKey("tasks", "trackid");
    $add = $api_tasks_table_class_call::createTask($track_id, $name, $description, $startDate, $endDate);
    
    if ( $add ){
        $maindata=[];
        $text = $api_response_class_call::$taskCreated;
        $api_status_code_class_call->respondOK($maindata,$text);
    }
    $text = $api_response_class_call::$dbInsertError;
    $errorcode = $api_error_code_class_call::$internalInsertDBFatal;
    $maindata = [];
    $hint = ["Check DB connection or query to check for manipulation"];
    $linktosolve = "https://";
    $api_status_code_class_call->respondInternalError($maindata, $text, $hint, $linktosolve, $errorcode);

} else {
    $text = $api_response_class_call::$methodUsedNotAllowed;
    $errorcode = $api_error_code_class_call::$internalHackerWarning;
    $maindata = [];
    $hint = ["Ensure to use the method stated in the documentation."];
    $linktosolve = "https://";
    $api_status_code_class_call->respondMethodNotAlowed($maindata, $text, $hint, $linktosolve, $errorcode);
}