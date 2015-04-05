<?php

require_once("Rest.inc.php");

class Information extends REST {


    public function __construct() {
        parent::__construct();    // Init parent contructor
     }

    
    
     public function customer() {
         $query = "SELECT VC_CUSTOMER_NAME,NU_CUSTOMER_CODE FROM MST_CUSTOMER WHERE VC_COMP_CODE ='01'";
         $statement = oci_parse($this->dbmakess, $query);
         oci_execute($statement);
         oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
         $numrows = oci_num_rows($statement);
         oci_free_statement($statement);

        
          if ($numrows > 0) {
               $this->response($this->json($res), 200);
          }else{
              $this->response('', 406);
          }
    }
    
    public function fetchunit() {
         $query = "SELECT VC_REASON_DESC,VC_REASON_CODE FROM INVENT.MST_REASON WHERE VC_COMP_CODE ='01' AND VC_REASON_FLG ='U'";
         $statement = oci_parse($this->dbmakess, $query);
         oci_execute($statement);
         oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
         $numrows = oci_num_rows($statement);
         oci_free_statement($statement);

        
          if ($numrows > 0) {
               $this->response($this->json($res), 200);
          }else{
              $this->response('', 406);
          }
    }
    
    public function fetchproduct() {
         $query = "SELECT VC_CODE,VC_CODE_DESC  FROM TRANSPORT.MSTCODE WHERE VC_COMP_CODE ='01' AND VC_CODE LIKE 'P%'";
         $statement = oci_parse($this->dbmakess, $query);
         oci_execute($statement);
         oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
         $numrows = oci_num_rows($statement);
         oci_free_statement($statement);

        
          if ($numrows > 0) {
               $this->response($this->json($res), 200);
          }else{
              $this->response('', 406);
          }
    }
    
    
    public function fetchtransporter() {
         $query = "SELECT VC_SUPPLIER_NAME,NU_SUPPLIER_CODE FROM MAKESS.MST_SUPPLIER WHERE VC_COMP_CODE ='01' AND NVL(VC_TYPE,'S') <>'S'";
         $statement = oci_parse($this->dbmakess, $query);
         oci_execute($statement);
         oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
         $numrows = oci_num_rows($statement);
         oci_free_statement($statement);

        
          if ($numrows > 0) {
               $this->response($this->json($res), 200);
          }else{
              $this->response('', 406);
          }
    }
    
    
    public function fetchtdriverid() {
         $query = "SELECT VC_ID_NO,NU_DRIVER_CODE FROM TRANSPORT.MST_DRIVER WHERE VC_COMP_CODE ='01'";
         $statement = oci_parse($this->dbmakess, $query);
         oci_execute($statement);
         oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
         $numrows = oci_num_rows($statement);
         oci_free_statement($statement);

        
          if ($numrows > 0) {
               $this->response($this->json($res), 200);
          }else{
              $this->response('', 406);
          }
    }
    
    
     public function fetchtdrivername() {
         $query = "SELECT VC_DRIVER_NAME,NU_DRIVER_CODE VC_EMP_CODE FROM TRANSPORT.MST_DRIVER WHERE VC_COMP_CODE ='01'";
         $statement = oci_parse($this->dbmakess, $query);
         oci_execute($statement);
         oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
         $numrows = oci_num_rows($statement);
         oci_free_statement($statement);

        
          if ($numrows > 0) {
               $this->response($this->json($res), 200);
          }else{
              $this->response('', 406);
          }
    }
    
    
    public function fetchttrailororvehicle() {
         $query = "SELECT VC_VEHICLE_CODE, VC_VEHICLE_NO FROM TRANSPORT.MST_VEHICLE WHERE VC_COMP_CODE = '01'";
         $statement = oci_parse($this->dbmakess, $query);
         oci_execute($statement);
         oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
         $numrows = oci_num_rows($statement);
         oci_free_statement($statement);

        
          if ($numrows > 0) {
               $this->response($this->json($res), 200);
          }else{
              $this->response('', 406);
          }
    }
    
    
    public function fetchatlno() {
         $query = "SELECT VC_MANIFEST_NO,DT_MANIFEST_DATE,VC_REGISTRATION_NO  FROM TRANSPORT.HD_MANIFEST
                    WHERE VC_COMP_CODE ='01' AND  (VC_MANIFEST_NO,DT_MANIFEST_DATE ) NOT IN (SELECT A.VC_MANIFEST_NO,A.DT_MANIFEST_DATE  FROM  TRANSPORT.HD_TRIP A
                    WHERE VC_COMP_CODE ='01' AND  A.VC_MANIFEST_NO IS NOT NULL)";
         $statement = oci_parse($this->dbmakess, $query);
         oci_execute($statement);
         oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
         $numrows = oci_num_rows($statement);
         oci_free_statement($statement);

        
          if ($numrows > 0) {
               $this->response($this->json($res), 200);
          }else{
              $this->response('', 406);
          }
    }
    
     public function listing()
     {
         $this->fetchatlno();
     }
             

}

// Initiiate Library

$api = new Information;

 $api->listing();

?>