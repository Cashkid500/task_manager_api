<?php 

require_once '../../../config/bootstrap_file.php';
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){

        // Check for authorization
        $sortQuery = '';
        $searchQuery = "";
        $paramString ="";
        $params = [];

    
        
        if (isset($_GET['search'])) { 
            $search = $utility_class_call::escape($_GET['search']);
            $searchQuery = "AND (name LIKE ? OR description LIKE ?)";
            $searchParam = "%{$search}%";
            $paramString .= "ss";
            $params[]= $searchParam;
            $params[]=$searchParam;

        }
    
        if (!isset ($_GET['page']) ) {  
            $page_no = 1;  
        } else {  
            $page_no = $_GET['page'];  
        }

        $noPerPage = 4;  
        $offset = ($page_no - 1) * $noPerPage;

        
        $alltask = $api_tasks_table_class_call::getTask($page_no, $offset, $noPerPage, $sortQuery, $searchQuery,$paramString, $params = []);

        if ( $alltask  ){
            $maindata= $alltask;
            $text = $api_response_class_call::$getRequestFetched;
            $api_status_code_class_call->respondOK($maindata,$text);
        }else{
            $maindata= [];
            $text = $api_response_class_call::$getRequestNoRecords;
            $api_status_code_class_call->respondOK($maindata,$text);
        }


    }else {
        $text = $api_response_class_call::$methodUsedNotAllowed;
        $errorcode = $api_error_code_class_call::$internalUserWarning;
        $maindata = [];
        $hint = ["Ensure to use the method stated in the documentation."];
        $linktosolve = "https://";
        $api_status_code_class_call->respondMethodNotAlowed($maindata, $text, $hint, $linktosolve, $errorcode);
    }

?>