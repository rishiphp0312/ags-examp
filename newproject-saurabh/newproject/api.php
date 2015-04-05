<?php

ob_start();
session_start();
require_once("Rest.inc.php");

class API extends REST {

    
    public function __construct() {
        parent::__construct();    // Init parent contructor
       
    }
    
    
    /*
     * Public method for access api.
     * This method dynmically call the method based on the query string
     *
     */

    public function processApi() {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));

        if ((int) method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            $this->response('', 404); // If the method not exist with in this class, response would be "Page not found".
        }
    }

    /*
     *  Simple login API
     *  Login must be POST method
     *  email : <USER EMAIL>
     *  pwd : <USER PASSWORD>
     */

    private function login() {
        // Cross validation if the request method is POST else it will return "Not Acceptable" status
        if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }


        $email = $this->_request['email'];
        $password = $this->_request['pwd'];


        // Input validations
        if (!empty($email) and !empty($password)) {

            $query = "SELECT VC_COMP_CODE, CH_USER_CODE, CH_USER_ACTIVE FROM MK_USERS WHERE VC_USER_NAME = '$email' AND DECRYPT(VC_PASSWORD) = '$password'";
            $statement = oci_parse($this->dbmakess, $query);
            oci_execute($statement);
            oci_fetch_all($statement, $res);
            $numrows = oci_num_rows($statement);
            unset($res);
            oci_free_statement($statement);

            $statement = oci_parse($this->dbmakess, $query);
            oci_execute($statement);
            $row = oci_fetch_array($statement, OCI_ASSOC);

            if ($numrows > 0) {
                $row['status'] = '1';
                $row['msg'] = 'Login Successfully.';
                // If success everythig is good send header as "OK" and user details
                $success = array('status' => "Success", "msg" => "Login Successfully.");
                $this->response($this->json($row), 200);
            }else{
            $error = array('status' => "0", "msg" => "Invalid User Name or Password");
            $this->response($this->json($error), 400); // If no records "No Content" status
            }
        }

        // If invalid inputs "Bad Request" status message and reason
        //echo "test";die;
        $error = array('status' => "0", "msg" => "Invalid User Name or Password");
        $this->response($this->json($error), 400);
    }

   

}

// Initiiate Library

$api = new API;

$api->processApi();
?>