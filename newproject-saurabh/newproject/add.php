<?php

require_once("Rest.inc.php");

class Add extends REST {

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
                   ";
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

    private function add() {
        $this->fetchatlno();
    }

    /* DISPLAY THE ADD THE MULIPLE DROP DOWNLISTING VALUE DISPLAY */
    private function index() {
        
        if ($_POST['status'] == 1) {
            $ch_user_code = $_POST['ch_user_code'];
            $comp_code = $_POST['vc_comp_code'];
            //$emp_code = 16;
            $emp_code = $ch_user_code;
        
            $ALTno = $this->fetchatlno($emp_code, $comp_code);
            $vehicleno = $this->fetchttrailororvehicle($emp_code, $comp_code);
            $driverinfo = $this->fetchtdriveinfo($emp_code, $comp_code);
            $customerinfo = $this->customer($emp_code, $comp_code);
            $transport = $this->fetchtransporter($emp_code, $comp_code);
            $productinfo = $this->fetchproduct($emp_code, $comp_code);
           // $unit = $this->fetchunit($emp_code, $comp_code);
            $router = $this->fetchrouter($emp_code, $comp_code);
            //echo "<pre>"; print_r($driverinfo);die;
            if ($ALTno != 0) {
                $row['ATLInformation'] = $ALTno;
            } else {
                $row['ATLInformation'] = '';
            }

            if ($vehicleno != 0) {
                $row['VehicleInfomation'] = $vehicleno;
            } else {
                $row['VehicleInfomation'] = '';
            }

            if ($driverinfo != 0) {
                $row['DriverInfomation'] = $driverinfo;
            } else {
                $row['DriverInfomation'] = '';
            }

            $row['CustomerInfomation'] = $customerinfo;

            if ($transport != 0) {
                $row['TransportInfomation'] = $transport;
            } else {
                $row['TransportInfomation'] = '';
            }

            if ($productinfo != 0) {
                $row['ProductInformation'] = $productinfo;
            } else {
                $row['ProductInformation'] = '';
            }

           /* if ($unit != 0) {
                $row['UnitInformation'] = $unit;
            } else {
                $row['UnitInformation'] = '';
            }*/

            if ($router != 0) {
                $row['RouterInformation'] = $router;
            } else {
                $row['RouterInformation'] = '';
            }
            
            $this->response($this->json($row), 200);
            
            }else{
                $this->response('', 401);
            }
    }

    /* DATA SAVE THE ADD THE FUNCTIONALITY WITH TRIP NO AND OTHER INFORMATION*/
    private function saveinformation() {
       
       // $_POST['email']='lenny';
	//	 $_POST['pwd']='lenny';
         $email         = $_POST['email'];
         $password      = $_POST['pwd'];
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
            //echo "<pre>"; print_r($row); die;
                        $ch_user_code   = $row['CH_USER_CODE'];
                        $comp_code      = $row['VC_COMP_CODE'];
                        $currentdate    =date('d-m-Y');
                        $autoid          = $this->maximumID();
                        if(isset($_POST['vc_router_name'])&& !empty($_POST['vc_router_name']))
                        {
                            
                            
                            $getfirstchar = substr($_POST['vc_router_name'], 0, 1);
                            $maxid=$getfirstchar.$autoid;
                        }else{
                            $maxid=$autoid;
                        }   
                        $vc_field1 =$row['CH_USER_ACTIVE'];
                       // $this->multiplerow($comp_code,$maxid,$currentdate,$ch_user_code);
                        $querych = "SELECT NU_TRIP_NO FROM TRANSPORT.HD_TRIP WHERE NU_TRIP_NO = '$maxid' ";
                        $statement = oci_parse($this->dbmakess, $querych);
                        oci_execute($statement);
                        oci_fetch_all($statement, $res);
                        $numrows = oci_num_rows($statement);
                        unset($res);
                        oci_free_statement($statement);

                        if($numrows == 0)
                        {  
                             $SQLInsert      = "INSERT INTO TRANSPORT .HD_TRIP 
                                            (   VC_COMP_CODE, NU_TRIP_NO, VC_VEHICLE_CODE, DT_TRIP_START_DATE, 
                                                VC_DEFAULT_COMP, VC_AUTH_CODE, DT_MOD_DATE, VC_TRANSPORTER_COD, 
                                                VC_DRIVER_CODE1, NU_ROUTE_NO, VC_TRAILOR_CODE, NU_CUSTOMER_CODE,
                                                VC_MANIFEST_NO,  DT_MANIFEST_DATE, VC_FIELD1 ) 
                                        VALUES 
                                            (  '".$comp_code."', '".$maxid."', '".$_POST['vc_vehicle_code']."', to_date('".$currentdate."', 'dd-mm-rrrr'),
                                               '".$comp_code."', '".$ch_user_code."', to_date('".$currentdate."', 'dd-mm-rrrr'), '".$_POST['vc_transporter_cod']."',
                                               '".$_POST['vc_driver_code1']."', '".$_POST['nu_route_no']."', '".$_POST['vc_trailor_code']."', '".$_POST['nu_customer_code']."',
                                               '".$_POST['vc_manifest_no']."' ,to_date('".$_POST['dt_manifest_date']."', 'dd-mm-rrrr'), '".$vc_field1."')" ;
                            $statement = oci_parse($this->dbmakess, $SQLInsert);
                            $s = oci_execute($statement);
                            $r =oci_commit($this->dbmakess);
                            oci_free_statement($statement);
                            
                            
                            if (!$r && !$s) {
                                        $e = oci_error($this->dbmakess);
                                        trigger_error(htmlentities($e['message']), E_USER_ERROR);
                            }  else {
                                      $scound = $this->multiplerow($comp_code,$maxid,$currentdate,$ch_user_code);
                                      if($scound==1)
                                      {
                                          $success[] = array('insert'=>'1',"msg" => "All Data Saved Successfully.",'nu_trip_no'=>$maxid);
                                          $this->response($this->json($success), 201);
                                      }else{
                                          $success[] = array('insert'=>'1',"msg" => "Saved Successfully.",'nu_trip_no'=>$maxid);
                                        $this->response($this->json($success), 201);
                                      }   
                                        
                            }
                        }else{
                            $error[] = array('insert'=>'0',"msg" => "Already Record Exist.");
                            $this->response($this->json($error), 400);
                        }
           
            } else {
            $this->response('', 401);
        }
            }else{
                    $error[] = array('status' => "0", "msg" => "Invalid User Name or Password");
                    $this->response($this->json($error), 400); // If no records "No Content" status
               }
         }
      
    } 
    
    private function multiplerow($comp_code=NULL,$maxid,$currentdate,$ch_user_code)
    {
         // echo "<pre>"; print_r($_POST); die;
          $productlist=$_POST['productlist'];
          // echo"<pre>"; print_r($productlist);die;
        foreach($productlist as $p)
        {
                 $comp_code                     =  $comp_code;
                 $tripno                        =  $maxid;
                 $tripstartdate                 =  $currentdate;
                 $tripdate                      =  $currentdate;
                 $auth_code                     =  $ch_user_code;
                 $modifDate                     =  $currentdate;
                 $product                       =  $p['vc_product_code'];
                 $qty                           =  $p['nu_qty'];
                 $remarks                       =  $p['vc_remarks'];
                 $stationfrom                   =  $p['vc_station_from'];
                 $stationto                     =  $p['vc_station_to'];
                 $del                           =  $p['vc_del'];
                 $lpo                           =  $p['vc_lpo'];
                 //$flow                          =  $p['vc_flow'];
                 
                $SQLPInsert      = "INSERT INTO TRANSPORT .DT_TRIP 
                                            (   VC_COMP_CODE, NU_TRIP_NO, DT_TRIP_START_DATE, DT_TRIP_DATE,
                                                VC_DEFAULT_COMP, VC_AUTH_CODE, DT_MOD_DATE, VC_PRODUCT_CODE, 
                                                NU_QTY, VC_REMARKS, VC_STATION_FROM, VC_STATION_TO,
                                                VC_DEL,  VC_LPO ) 
                                        VALUES 
                                            (  '".$comp_code."', '".$tripno."',  to_date('".$tripstartdate."', 'dd-mm-rrrr'),  to_date('".$tripdate."', 'dd-mm-rrrr'),
                                               '".$comp_code."', '".$auth_code."', to_date('".$modifDate."', 'dd-mm-rrrr'), '".$product."',
                                               '".$qty."', '".$remarks."', '".$stationfrom."', '".$stationto."',
                                               '".$del."', '".$lpo."')" ;
                            $statement = oci_parse($this->dbmakess, $SQLPInsert);
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

    /* GET THE MAX TRIP NO FOR HD_TRIP TABLE */
    private function maximumID()
    {
        $query = "SELECT NVL(MAX (TO_NUMBER
                    (CASE WHEN SUBSTR(NU_TRIP_NO,1,1) NOT IN ('0','1','2','3','4','5','6,7','8','9')
                          THEN SUBSTR(NU_TRIP_NO,2)
                          ELSE NU_TRIP_NO
                          END)
                    ),0) AS STR
                 FROM TRANSPORT.HD_TRIP";
        $statement = oci_parse($this->dbmakess, $query);
        oci_execute($statement);
        oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
        $numrows = oci_num_rows($statement);
        oci_free_statement($statement);
        $numberid = $res[0]['STR']+1;
        return $numberid;

    }       
}

// Initiiate Library
// http://132.132.2.113/newproject/add.php?method=index
$api = new Add;
$method = '';
switch (strtoupper($_GET['method'])) 
	{
            case 'INDEX':
                                    $method ='index';	
                                    break;
            case 'SAVEINFORMATION':
                                    $method ='saveinformation';	
                                    break;
            default:
                                    $method ='add';	
                                    break;                    
        }
        
 $api->processApi($method);


?>