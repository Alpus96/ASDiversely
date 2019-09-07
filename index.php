<?php
    /**
    *   @uses           Response
    *
    *   This script handels setting the root path global variable and
    *   instansing the response handler with the requested method and
    *   url. It will send the error page if no response is reached.
    *
    *   @category       Request handling
    *   @package        Server index
    *   @subpackage     Request recieving
    *   @version        1.2.2
    *   @since          1.0
    *   @deprecated     -
    * */

    //  Define the root path in project and get required files.
    define('ROOT_PATH', __DIR__.'/');
    require_once ROOT_PATH.'proc/response.php';

    //  Start response handling.
    $response = null;
    //  Try to create the response.
    try {
        $response = new Response(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD']
        );
    //  Catch any exceptions.
    } catch (Exception $e) {
        $response = $e;
        //  Write the exception to a log file.
        require ROOT_PATH.'lib/debug/logger.php';
        $logger = new Logger('RequestExceptions');
        //  Log the caught error.
        $logger->log($response);
    }

    //  If response resulted in false or is an exception handle it.
    if (!$response || $response instanceof Exception) {
        //  If request method is GET, respond with the error page.
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            echo file_get_contents(ROOT_PATH.'error/error.html');
        //  If the request method was not GET the return a
        //      json object with a parameter success = false.
        } else { echo json_encode((object)['success' => false]); }
    }
 ?>
