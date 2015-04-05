<?php

require_once("Rest.inc.php");

class Test extends REST {

    public function __construct() {
        parent::__construct();    // Init parent contructor
    }
    
     /*
     * Public method for access api.
     * This method dynmically call the method based on the query string
     *
     */

    public function processApi($method) {
      
       $func = strtolower(trim(str_replace("/", "", $method)));

        if ((int) method_exists($this, $func) > 0) {
            $this->$func();
        } else {
            $this->response('', 404); // If the method not exist with in this class, response would be "Page not found".
        }
    }



   

    /* DISPLAY THE ADD THE MULIPLE DROP DOWNLISTING VALUE DISPLAY */
    private function index() {
        
        $query = "select parameter,value system_value,required_value,decode (value,required_value,'YES','NO') as Compliance from (
Select parameter,value,
case parameter
when 'Coalesce Index' Then 'TRUE'
when 'Connection multiplexing' Then 'TRUE'
when 'Connection pooling' Then 'TRUE'
when 'DICOM' Then 'TRUE'
when 'Database queuing' Then 'TRUE'
when 'Incremental backup and recovery' Then 'TRUE'
when 'Instead-of triggers' Then 'TRUE'
when 'Java' Then 'TRUE'
when 'OLAP Window Functions' Then 'TRUE'
when 'Objects' Then 'TRUE'
when 'Parallel load' Then 'TRUE'
when 'Plan Stability' Then 'TRUE'
when 'Proxy authentication/authorization' Then 'TRUE'
when 'Sample Scan' Then 'TRUE'
when 'Transparent Application Failover' Then 'TRUE'
when 'XStream' Then 'TRUE'
ELSE 'FALSE'
END as required_value
from V\$Option order by parameter asc)";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
           $this->response($this->json($res), 200);
        } else {
           $this->response($this->json($res), 200);
        }
         
        
    }

    
    private function singlerecord() {
        
        $query = "select BANNER,sysdate SNAP_TIME from v\$version";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
           $this->response($this->json($res), 200);
        } else {
            $this->response($this->json($res), 200);
        }
         
        
    }

   
    
   

           
}

// Initiiate Library
// http://132.132.2.113/newproject/add.php?method=index
$api = new Test;
$method = '';
switch (strtoupper($_GET['method'])) 
	{
            case 'INDEX':
                                    $method ='index';	
                                    break;
            case 'SINGLERECORD':
                                    $method ='singlerecord';	
                                    break;
            default:
                                    $method ='add';	
                                    break;                    
        }
        
 $api->processApi($method);


?>