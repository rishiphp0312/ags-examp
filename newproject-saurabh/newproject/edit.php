<?php

require_once("Rest.inc.php");

class Edit extends REST {

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
    
    public function customer($emp_code = NULL, $comp_code = NULL) {
        $query = "SELECT VC_CUSTOMER_NAME,NU_CUSTOMER_CODE FROM MST_CUSTOMER WHERE VC_COMP_CODE ='$comp_code'";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
            return $res;
        } else {
            return '0';
        }
    }

    public function fetchunit($emp_code = NULL, $comp_code = NULL) {
        $query = "SELECT VC_REASON_DESC UNIT_NAME , VC_REASON_CODE UNIT_CODE FROM INVENT.MST_REASON WHERE VC_COMP_CODE ='$comp_code' AND VC_REASON_FLG ='U'";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
            return $res;
        } else {
            return '0';
        }
    }

    public function fetchproduct($emp_code = NULL, $comp_code = NULL) {
        $query = "SELECT VC_CODE PRODUCT_CODE , VC_CODE_DESC PRODUCT_NAME  FROM TRANSPORT.MSTCODE WHERE VC_COMP_CODE ='$comp_code' AND VC_CODE LIKE 'P%'";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
            return $res;
        } else {
            return '0';
        }
    }

    public function fetchtransporter($emp_code = NULL, $comp_code = NULL) {
        $query = "SELECT VC_SUPPLIER_NAME TRANSPORTER_NAME,NU_SUPPLIER_CODE TRANSPORTER_CODE FROM MAKESS.MST_SUPPLIER WHERE VC_COMP_CODE ='$comp_code' AND NVL(VC_TYPE,'S') <>'S'";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
            return $res;
        } else {
            return '0';
        }
    }

    public function fetchtdriveinfo($emp_code = NULL, $comp_code = NULL) {
        $query = "SELECT VC_ID_NO DRIVER_ID , VC_DRIVER_NAME, NU_DRIVER_CODE  FROM TRANSPORT.MST_DRIVER WHERE VC_COMP_CODE ='$comp_code'";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
            return $res;
        } else {
            return '0';
        }
    }

    public function fetchttrailororvehicle($emp_code = NULL, $comp_code = NULL) {
        $query = "SELECT VC_VEHICLE_CODE, VC_VEHICLE_NO FROM TRANSPORT.MST_VEHICLE WHERE VC_COMP_CODE = '$comp_code'";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
            return $res;
        } else {
            return '0';
        }
    }

    public function fetchatlno($emp_code = NULL, $comp_code = NULL) {
        $query = "SELECT VC_MANIFEST_NO ALT_NO , DT_MANIFEST_DATE ALT_DATE , VC_REGISTRATION_NO  FROM TRANSPORT.HD_MANIFEST
                    WHERE VC_COMP_CODE ='$comp_code' AND  (VC_MANIFEST_NO,DT_MANIFEST_DATE ) NOT IN (SELECT A.VC_MANIFEST_NO,A.DT_MANIFEST_DATE  FROM  TRANSPORT.HD_TRIP A
                    WHERE VC_COMP_CODE ='$comp_code' AND  A.VC_MANIFEST_NO IS NOT NULL AND  VC_AUTH_CODE='$emp_code')
                    AND VC_AUTH_CODE='$emp_code'";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
            return $res;
        } else {
            return '0';
        }
    }

    public function fetchrouter($emp_code = NULL, $comp_code = NULL) {
        $query = "SELECT NU_ROUTE_NO, VC_ROUTE_NAME FROM TRANSPORT.MST_ROUTE WHERE VC_COMP_CODE = '$comp_code'";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        if ($numrows > 0) {
            return $res;
        } else {
            return '0';
        }
    }

    
    /* FIND TRIP NO BY FETCH THE DATA FOR MULIPLLE TABLE */
    private function getinfo($orno = NULL, $comp_code = NULL) {
       /* $query = "SELECT HT.VC_COMP_CODE, HT.NU_TRIP_NO, HT.VC_VEHICLE_CODE , HT.DT_TRIP_START_DATE,
                    HT.VC_DEFAULT_COMP,HT.VC_AUTH_CODE ,HT.DT_MOD_DATE,HT.VC_TRANSPORTER_COD,HT.VC_DRIVER_CODE1,
                    HT.NU_ROUTE_NO, HT.VC_TRAILOR_CODE, HT.NU_CUSTOMER_CODE,HT.VC_MANIFEST_NO, HT.DT_MANIFEST_DATE,
                    DT.DT_TRIP_DATE, DT.VC_REMARKS, DT.VC_PRODUCT_CODE, DT.VC_FLOW, DT.DT_RET_DATE,
                    DT.NU_QTY, DT.VC_STATION_TO, DT.VC_STATION_FROM, DT.VC_LPO, DT.VC_DEL 
                    FROM TRANSPORT.HD_TRIP HT, TRANSPORT.DT_TRIP DT
                    WHERE HT.VC_COMP_CODE = DT.VC_COMP_CODE
                    AND HT.NU_TRIP_NO= DT.NU_TRIP_NO
                    AND HT.NU_TRIP_NO='$orno'";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);*/
        
        
        $query1 = "SELECT HT.NU_TRIP_NO, HT.DT_TRIP_START_DATE,HT.VC_MANIFEST_NO, HT.DT_MANIFEST_DATE,HT.VC_VEHICLE_CODE , 
                                     V.VC_VEHICLE_NO, HT.VC_TRAILOR_CODE, HT.VC_DRIVER_CODE1,MD.VC_DRIVER_NAME,MD.VC_ID_NO,HT.NU_ROUTE_NO,
                                     MR.VC_ROUTE_NAME, HT.NU_CUSTOMER_CODE, C.VC_CUSTOMER_NAME,HT.VC_TRANSPORTER_COD,S.VC_SUPPLIER_NAME
                              FROM   TRANSPORT.HD_TRIP HT,TRANSPORT.MST_DRIVER MD,
                                     TRANSPORT.MST_VEHICLE V, MAKESS.MST_CUSTOMER C, MAKESS.MST_SUPPLIER S,
                                     TRANSPORT.MST_ROUTE MR
                               WHERE HT.VC_COMP_CODE= MD.VC_COMP_CODE
                                    AND HT.VC_DRIVER_CODE1= MD.NU_DRIVER_CODE
                                    AND HT.VC_COMP_CODE= MR.VC_COMP_CODE
                                    AND HT.NU_ROUTE_NO= MR.NU_ROUTE_NO
                                    AND HT.VC_COMP_CODE= V.VC_COMP_CODE
                                    AND HT.VC_VEHICLE_CODE = V.VC_VEHICLE_CODE
                                    AND HT.VC_COMP_CODE= C.VC_COMP_CODE
                                    AND HT.NU_CUSTOMER_CODE= C.NU_CUSTOMER_CODE
                                    AND HT.VC_COMP_CODE= S.VC_COMP_CODE
                                    AND NVL(S.VC_TYPE,'S') <>'S'
                                    AND S.NU_SUPPLIER_CODE=HT.VC_TRANSPORTER_COD
                                    AND HT.NU_TRIP_NO='$orno'";
                    $statement1 = oci_parse($this->dbmakess, $query1);
                    oci_execute($statement1);
                    oci_fetch_all($statement1, $res1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    $numrows1 = oci_num_rows($statement1);
                    oci_free_statement($statement1);
        
        
         $query2 = "SELECT DT.NU_TRIP_NO, DT.DT_TRIP_DATE, DT.VC_PRODUCT_CODE, MC.VC_CODE_DESC, DT.NU_QTY, DT.VC_REMARKS, 
                                      DT.VC_STATION_FROM, DT.VC_STATION_TO, DT.VC_FLOW, DT.VC_DEL ,DT.VC_LPO, DT.DT_RET_DATE
                               FROM   TRANSPORT.DT_TRIP DT, TRANSPORT.MSTCODE MC
                               WHERE DT.VC_PRODUCT_CODE = MC.VC_CODE AND MC.VC_CODE LIKE 'P%'AND DT.NU_TRIP_NO='$orno'";
                    $statement2 = oci_parse($this->dbmakess, $query2);
                    oci_execute($statement2);
                    oci_fetch_all($statement2, $res2, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    $numrows2 = oci_num_rows($statement2);
                    oci_free_statement($statement2);
         
           if ($numrows2 > 0) {
                        $row['ProductInformation'] = $res2;
                    } else {
                        $row['ProductInformation'] = '';
                    }
                    
                    if ($numrows1 > 0) {
                        $row['Information'] = $res1;
                    } else {
                        $row['Information'] = '';
                    }          
                    
        if ($numrows1 > 0) {
            return $row;
        } else {
            return '0';
        }
    }

    /* EDIT PAGE DISPLAY THE DETILES BY TRIP NO */
    private function edit() {
       if ($_POST['status'] == 1) {
            $ch_user_code = $_POST['ch_user_code'];
            $comp_code = $_POST['vc_comp_code'];
            if (!empty($_POST['nu_trip_no']) && isset($_POST['nu_trip_no'])) {
                $trno = $_POST['nu_trip_no'];
                $info = $this->getinfo($trno, $comp_code);
                $this->response($this->json($info), 200);
            } else {
                $this->response('', 204);
            }
        } else {
            $this->response('', 401);
        }
    }

    /* EDIT CASE UPDATE THE INFOMATION FOR HD_TRIP TABLE*/
    private function updateinfomation() {
        
         $email         = $_POST['email'];
         $password      = $_POST['pwd']; 
         //$this->response($this->json($_POST), 200);
         if (!empty($email) and !empty($password)) {
                $query = "SELECT VC_COMP_CODE, CH_USER_CODE, CH_USER_ACTIVE FROM MK_USERS WHERE VC_USER_NAME = '$email' AND DECRYPT(VC_PASSWORD) = '$password'";
                $statement = oci_parse($this->dbmakess, $query);
                oci_execute($statement);
                oci_fetch_all($statement, $res);
                $numrows1 = oci_num_rows($statement);
                unset($res);
                oci_free_statement($statement);
                $statement = oci_parse($this->dbmakess, $query);
                oci_execute($statement);
                $row = oci_fetch_array($statement, OCI_ASSOC);
                 if ($numrows1 > 0) {
                            $row['status'] = '1';
                            $row['msg'] = 'Login Successfully.';
                            if ($row['status'] == 1) {
                                    $ch_user_code   = $row['CH_USER_CODE'];
                                    $comp_code      = $row['VC_COMP_CODE'];
                                    $currentdate    = date('d-m-Y');
                                    $maxid          = $_POST['nu_trip_no'];
                                    $vc_field1      =$row['CH_USER_ACTIVE'];
                                    $querych        = "SELECT NU_TRIP_NO FROM TRANSPORT.HD_TRIP WHERE NU_TRIP_NO = '$maxid' ";
                                    $statement = oci_parse($this->dbmakess, $querych);
                                    oci_execute($statement);
                                    oci_fetch_all($statement, $res);
                                    $numrows = oci_num_rows($statement);
                                    unset($res);
                                    oci_free_statement($statement);

                                    if($numrows > 0)
                                    { 
                                       $SQLUpdate = "UPDATE TRANSPORT.HD_TRIP  SET 
                                                                VC_COMP_CODE            ='".$comp_code."',
                                                                NU_TRIP_NO              ='".$maxid."',
                                                                VC_VEHICLE_CODE         = '".$_POST['vc_vehicle_code']."',
                                                                VC_DEFAULT_COMP         = '".$comp_code."',
                                                                VC_AUTH_CODE            = '".$ch_user_code."' ,
                                                                DT_MOD_DATE             = to_date('".$currentdate."', 'dd-mm-rrrr'),
                                                                VC_TRANSPORTER_COD      = '".$_POST['vc_transporter_cod']."' ,
                                                                VC_DRIVER_CODE1         = '".$_POST['vc_driver_code1']."',
                                                                NU_ROUTE_NO             = '".$_POST['nu_route_no']."',
                                                                VC_TRAILOR_CODE         = '".$_POST['vc_trailor_code']."',
                                                                NU_CUSTOMER_CODE        = '".$_POST['nu_customer_code']."',
                                                                VC_MANIFEST_NO          = '".$_POST['vc_manifest_no']."',
                                                                DT_MANIFEST_DATE        = to_date('".$_POST['dt_manifest_date']."', 'dd-mm-rrrr'),
                                                                VC_FIELD1               = '".$vc_field1."'
                                                       WHERE    NU_TRIP_NO              ='".$maxid."' ";

                                        $statement = oci_parse($this->dbmakess, $SQLUpdate);
                                        $s = oci_execute($statement);
                                        $r =oci_commit($this->dbmakess);
                                        oci_free_statement($statement);
                                        if (!r && !$s) {
                                                    $e = oci_error($this->dbmakess);
                                                    trigger_error(htmlentities($e['message']), E_USER_ERROR);
                                        }  else {
                                          $update = $this->multiplerowupdate($comp_code,$maxid,$currentdate,$ch_user_code);
                                            if($update==1)
                                            {
                                                $success[] = array('update'=>'1',"msg" => "All Data Update Successfully.");
                                                    $this->response($this->json($success), 200);
                                            }else{
                                                $success[] = array('update'=>'1',"msg" => "Update Successfully.");
                                                    $this->response($this->json($success), 200);
                                            }   

                                        }
                                        }else{
                                        $error[] = array('update'=>'0',"msg" => "No Record Exist.");
                                        $this->response($this->json($error), 304);
                                    }

                            } else {
                                $this->response('', 401);
                            }
                  }else{
                        $error = array('status' => "0", "msg" => "Invalid User Name or Password");
                        $this->response($this->json($error), 400); // If no records "No Content" status
                  }
            } else {
                $this->response('', 401);
            }
    }
    
    private function multiplerowupdate($comp_code=NULL,$maxid,$currentdate,$ch_user_code)
    {
         // echo "<pre>"; print_r($_POST); die;
        $productlist=$_POST['productlist'];
        foreach($productlist as $p)
        {
                 $comp_code                     =  $comp_code;
                 $tripno                        =  $maxid;
                 $tripstartdate                 =  $currentdate;
                 $tripdate                      =  $currentdate;
                 $auth_code                     =  $ch_user_code;
                 $modifDate                     =  $currentdate;
                 $product                       =  $p ['vc_product_code'];
                 $qty                           =  $p['nu_qty'];
                 $remarks                       =  $p['vc_remarks'];
                 $stationfrom                   =  $p['vc_station_from'];
                 $stationto                     =  $p['vc_station_to'];
                 $del                           =  $p['vc_del'];
                 $lpo                           =  $p['vc_lpo'];
                // $flow                          =  $p['vc_flow'];
                 $productStatus                 =  $p['vc_product_status'];
               if(isset($productStatus)&& !empty($productStatus))
               {
                   $SQLnew      = "INSERT INTO TRANSPORT .DT_TRIP 
                                            (   VC_COMP_CODE, NU_TRIP_NO, DT_TRIP_START_DATE, DT_TRIP_DATE,
                                                VC_DEFAULT_COMP, VC_AUTH_CODE, DT_MOD_DATE, VC_PRODUCT_CODE, 
                                                NU_QTY, VC_REMARKS, VC_STATION_FROM, VC_STATION_TO,
                                                VC_DEL,  VC_LPO) 
                                        VALUES 
                                            (  '".$comp_code."', '".$tripno."',  to_date('".$tripstartdate."', 'dd-mm-rrrr'),  to_date('".$tripdate."', 'dd-mm-rrrr'),
                                               '".$comp_code."', '".$auth_code."', to_date('".$modifDate."', 'dd-mm-rrrr'), '".$product."',
                                               '".$qty."', '".$remarks."', '".$stationfrom."', '".$stationto."',
                                               '".$del."', '".$lpo."')" ;
               }else{
                   $SQLnew = "UPDATE TRANSPORT.DT_TRIP  SET 
                                    VC_COMP_CODE                ='".$comp_code."',
                                    NU_TRIP_NO                  ='".$tripno."',
                                    DT_TRIP_START_DATE          = to_date('".$tripstartdate."', 'dd-mm-rrrr'),
                                    DT_TRIP_DATE                = to_date('".$tripdate."', 'dd-mm-rrrr'),
                                    VC_DEFAULT_COMP             = '".$comp_code."' ,
                                    VC_AUTH_CODE                = '".$auth_code."',
                                    DT_MOD_DATE                 = to_date('".$modifDate."', 'dd-mm-rrrr'),
                                    VC_PRODUCT_CODE             = '".$product."',
                                    NU_QTY                      = '".$qty."',
                                    VC_REMARKS                  = '".$remarks."',
                                    VC_STATION_FROM             = '".$stationfrom."',
                                    VC_STATION_TO               = '".$stationto."',
                                    VC_DEL                      = '".$del."',
                                    VC_LPO                      = '".$lpo."'
                                    WHERE NU_TRIP_NO  ='".$maxid."' AND  VC_PRODUCT_CODE  ='".$product."'  ";
               }   
                
                            $statement = oci_parse($this->dbmakess, $SQLnew);
                            $st = oci_execute($statement);
                            $rt =oci_commit($this->dbmakess);
                            oci_free_statement($statement);
        }
        if (!$rt && !$st) {
            return 0;
        }else{
            return 1;
        }
    }

}

// Initiiate Library

$api = new Edit;

$method = '';
switch (strtoupper($_GET['method'])) 
	{
            case 'GETINFO':
                                    $method ='getinfo';	
                                    break;
            case 'UPDATEINFOMATION':
                                    $method ='updateinfomation';	
                                    break;
            default:
                                    $method ='edit';	
                                    break;                      
        }
        
 $api->processApi($method);

?>