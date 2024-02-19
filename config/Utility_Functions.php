<?php


namespace Config;
use DateTime;

/**
 * View 
 *
 * PHP version 5.4
 */
class Utility_Functions  extends DB_Connect
{
    public static function escape($data)
    {
        $conn = static::getDB();
        $input = $data;
        // This removes all the HTML tags from a string. This will sanitize the input string, and block any HTML tag from entering into the database.
        // filter_var($geeks, FILTER_SANITIZE_STRING);
        // $input = filter_var($input, FILTER_SANITIZE_STRING);
        $input = trim($input, " \t\n\r");
        // htmlspecialchars() convert the special characters to HTML entities while htmlentities() converts all characters.
        // Convert the predefined characters "<" (less than) and ">" (greater than) to HTML entities:
        $input = htmlspecialchars($input, ENT_QUOTES,'UTF-8');
        // prevent javascript codes, Convert some characters to HTML entities:
        $input = htmlentities($input, ENT_QUOTES, 'UTF-8');
        $input = stripslashes(strip_tags($input));
        $input = mysqli_real_escape_string($conn, $input);

        return $input;
    }
    public static function getCurrentFullURL(){
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
        // Get the server name and port
        $servername = $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'];
        // Get the path to the current script
        $path = $_SERVER['PHP_SELF'];
        // Combine the above to form the full URL
        $endpoint = $protocol . $servername . ":" . $port . $path;
        return $endpoint;
    }
    public static function validate_input($data)
    {
        $incorrectdata=false;
        if(strlen($data)==0){
            $incorrectdata=true;
        }else if($data==null){
            $incorrectdata=true;
        }else if(empty($data)){
            $incorrectdata=true;
        }

        return $incorrectdata;
    }

    public static function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    public static  function greetUsers(){
        $welcome_string="Welcome!";
        $numeric_date=date("G");

        //Start conditionals based on military time
        if($numeric_date>=0&&$numeric_date<=11)
        $welcome_string="🌅 Good Morning";
        else if($numeric_date>=12&&$numeric_date<=17)
        $welcome_string="☀️ Good Afternoon";
        else if($numeric_date>=18&&$numeric_date<=23)
        $welcome_string="😴 Good Evening";

        return $welcome_string;
    }

    public static function generateUniqueShortKey($tableName, $field, $shortKey = "GNG"){
        $loop = 0;
        while ($loop == 0){
            $userKey = $shortKey.static::generateShortKey(5);
            if ( static::checkIfCodeisInDB($tableName, $field ,$userKey) ){
                $loop = 0;
            }else {
                $loop = 1;
                break;
            }
        }
        return $userKey;
    }

    public static function checkIfCodeisInDB($tableName, $field ,$pubkey) {
        $connect = static::getDB();
        $alldata = [];
        // Check if the email or phone number is already in the database
        $query = "SELECT $field FROM $tableName WHERE $field = ?";
        $stmt = $connect->prepare($query);
        $stmt->bind_param("s", $pubkey);
        $stmt->execute();
        $result = $stmt->get_result();
        $num_row = $result->num_rows;
        if ($num_row > 0){
            return true;
        }
        return $alldata;
    }

    public static function generateShortKey($strength){
        $input = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $output = static::generate_string($input, $strength);
        return $output;
    }

    public static function generate_string($input, $strength){
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }
    public static function exceptionHandler($exception)
    {
        // Code is 404 (not found) or 500 (general error)
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500; 
        }
        http_response_code($code);

        $error = error_get_last();
        $errno   ="";
        $errfile = "";
        $errline = "";
        $errstr  = "";
        if ($error !== null) {
            $errno   = $error["type"];
            $errfile = $error["file"];
            $errline = $error["line"];
            $errstr  = $error["message"];
        }
 
        if (Constants::SHOW_ERRORS) {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        } else {
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();
            $message .=  "\nOTHER ERRORS'" .$errno." ".$errfile." ".$errline." ". $errstr;

            error_log($message);
        }
    }
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

}




?>