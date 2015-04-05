<?php

require_once("Rest.inc.php");

class Dashboard extends REST {

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
    
    /* USER LOGIN THEN DISPLAY THE FIRST PAGE LISTING THE RESPONSE */
    private function index() {
        if ($_POST['status'] == 1) {
            $ch_user_code = $_POST['ch_user_code'];
            $comp_code = $_POST['vc_comp_code'];

            //$ch_user_code = 17;
            $query = "SELECT M.CH_USER_CODE, T.NU_TRIP_NO,T.VC_VEHICLE_CODE,T.VC_TRANSPORTER_COD, T.NU_CUSTOMER_CODE,
                         V.VC_VEHICLE_NO,C.VC_CUSTOMER_NAME, S.VC_SUPPLIER_NAME 
                    FROM MK_USERS M, TRANSPORT.HD_TRIP T, TRANSPORT.MST_VEHICLE V, MAKESS.MST_CUSTOMER C, MAKESS.MST_SUPPLIER S
                    WHERE M.CH_USER_CODE= T.VC_AUTH_CODE
                        AND M.VC_COMP_CODE= T.VC_COMP_CODE 
                        AND M.VC_COMP_CODE= V.VC_COMP_CODE
                        AND T.VC_VEHICLE_CODE = V.VC_VEHICLE_CODE
                        AND M.VC_COMP_CODE= C.VC_COMP_CODE
                        AND T.NU_CUSTOMER_CODE= C.NU_CUSTOMER_CODE
                        AND M.VC_COMP_CODE= S.VC_COMP_CODE
                        AND NVL(S.VC_TYPE,'S') <>'S'
                        AND S.NU_SUPPLIER_CODE=T.VC_TRANSPORTER_COD
                        AND M.CH_USER_CODE='$ch_user_code'
                        AND NVL(T.VC_FIELD1,'N') ='Y'
                    ORDER BY M.CH_USER_CODE";
            $statement = oci_parse($this->dbmakess, $query);
            oci_execute($statement);
            oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            $numrows = oci_num_rows($statement);
            oci_free_statement($statement);


            if ($numrows > 0) {
                $this->response($this->json($res), 200);
            } else {

                 $error[]= array("msg" => "No Record Found");
                $this->response($this->json($res), 200);
            }
        } else {
            $this->response('', 401);
        }
    }
    
    
     private function fetchtripnobycustomer($emp_code = NULL, $comp_code = NULL) {
       $query = "SELECT  T.NU_CUSTOMER_CODE, C.VC_CUSTOMER_NAME
                    FROM TRANSPORT.HD_TRIP T, MAKESS.MST_CUSTOMER C
                    WHERE 
                         T.VC_COMP_CODE= C.VC_COMP_CODE 
                         AND T.NU_CUSTOMER_CODE= C.NU_CUSTOMER_CODE
                         AND T.VC_AUTH_CODE='$emp_code'
                    GROUP BY   T.NU_CUSTOMER_CODE, C.VC_CUSTOMER_NAME          
                         ORDER BY C.VC_CUSTOMER_NAME";
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
    
    private function fetchtripnobyvehicleno($emp_code = NULL, $comp_code = NULL) {
        $query = "SELECT  T.VC_VEHICLE_CODE, V.VC_VEHICLE_NO
                    FROM TRANSPORT.HD_TRIP T, TRANSPORT.MST_VEHICLE V
                    WHERE T.VC_COMP_CODE= V.VC_COMP_CODE
                        AND T.VC_VEHICLE_CODE = V.VC_VEHICLE_CODE
                        AND T.VC_AUTH_CODE='$emp_code'
                    GROUP BY  T.VC_VEHICLE_CODE, V.VC_VEHICLE_NO       
                      ORDER BY T.VC_VEHICLE_CODE";
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
    
    /* DISPLAY THE SEARCH PAGE  FOR RESPONSE */
    private function search() {
        if ($_POST['status'] == 1) {
            $ch_user_code = $_POST['ch_user_code'];
            $comp_code = $_POST['vc_comp_code'];

           // $ch_user_code = 17;
            $query = "SELECT  T.NU_TRIP_NO FROM TRANSPORT.HD_TRIP T WHERE T.VC_AUTH_CODE='$ch_user_code' ORDER BY T.NU_TRIP_NO ";
            $statement = oci_parse($this->dbmakess, $query);
            oci_execute($statement);
            oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            $numrows = oci_num_rows($statement);
            oci_free_statement($statement);
            
             $vehicleno         = $this->fetchtripnobycustomer($ch_user_code, $comp_code);
             $customerinfo      = $this->fetchtripnobyvehicleno($ch_user_code, $comp_code);
            if ($numrows != 0) {
                $row['TransportInfomation'] = $res;
            } else {
                $row['TransportInfomation'] = '';
            }

            if ($customerinfo != 0) {
                $row['CustomerInfomation'] = $customerinfo;
            } else {
                $row['CustomerInfomation'] = '';
            }

            if ($vehicleno != 0) {
                $row['VehicleInfomation'] = $vehicleno;
            } else {
                $row['VehicleInfomation'] = '';
            }

            if ($numrows > 0) {
                $this->response($this->json($row), 200);
            } else {

                // $error[] = array("msg" => "No Record Found");
                $this->response('', 406);
            }
        } else {
            $this->response('', 401);
        }
    }
    
    
    /* FIND LISTING FOR SEARCHING TO TRIP NO, CUSTOMER AND VEHICLE REG NO */
    private function searchlisting() {
        if ($_POST['status'] == 1) {
            $ch_user_code = $_POST['ch_user_code'];
            $comp_code = $_POST['vc_comp_code'];

           // $ch_user_code = 17;
             $query = "SELECT M.CH_USER_CODE, T.NU_TRIP_NO,T.VC_VEHICLE_CODE,T.VC_TRANSPORTER_COD, T.NU_CUSTOMER_CODE,
                              V.VC_VEHICLE_NO,C.VC_CUSTOMER_NAME, S.VC_SUPPLIER_NAME 
                        FROM MK_USERS M, TRANSPORT.HD_TRIP T, TRANSPORT.MST_VEHICLE V, MAKESS.MST_CUSTOMER C, MAKESS.MST_SUPPLIER S
                        WHERE M.CH_USER_CODE= T.VC_AUTH_CODE
                            AND M.VC_COMP_CODE= T.VC_COMP_CODE 
                            AND M.VC_COMP_CODE= V.VC_COMP_CODE
                            AND T.VC_VEHICLE_CODE = V.VC_VEHICLE_CODE
                            AND M.VC_COMP_CODE= C.VC_COMP_CODE
                            AND T.NU_CUSTOMER_CODE= C.NU_CUSTOMER_CODE
                            AND M.VC_COMP_CODE= S.VC_COMP_CODE
                            AND NVL(S.VC_TYPE,'S') <>'S'
                            AND S.NU_SUPPLIER_CODE=T.VC_TRANSPORTER_COD
                            AND M.CH_USER_CODE='$ch_user_code'
                            AND NVL(T.VC_FIELD1,'N') ='Y'";
             if(!empty($_POST['nu_trip_no']) && isset($_POST['nu_trip_no']))
             {
                 $trno   = $_POST['nu_trip_no'];
                 $query .= " AND T.NU_TRIP_NO ='$trno' ";
             } 
             if(!empty($_POST['nu_customer_code']) && isset($_POST['nu_customer_code']))
             {
                 $cust_code   = $_POST['nu_customer_code'];
                 $query .= " AND T.NU_CUSTOMER_CODE = '$cust_code' ";
             }
             if(!empty($_POST['vc_vehcle_no']) && isset($_POST['vc_vehcle_no']))
             {
                 $vehicle_no   = $_POST['vc_vehcle_no'];
                 $query .= " AND V.VC_VEHICLE_NO = '$vehicle_no' ";
             }
               $query .= "ORDER BY M.CH_USER_CODE";
             //echo $query; die;
            $statement = oci_parse($this->dbmakess, $query);
            oci_execute($statement);
            oci_fetch_all($statement, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
            $numrows = oci_num_rows($statement);
            oci_free_statement($statement);


            if ($numrows > 0) {
                $this->response($this->json($res), 200);
            } else {

                // $error[] = array("msg" => "No Record Found");
                $this->response($this->json($res), 200);
            }
        } else {
            $this->response('', 401);
        }
    }
    
    /* DELETE THE FUNCTIONALITY WITH TRIP NO OF HD_TRIP TABLE */
    private function delete()
    {
         if ($_POST['status'] == 1) {
                    $ch_user_code = $_POST['ch_user_code'];
                    $comp_code = $_POST['vc_comp_code'];
                    if(!empty($_POST['nu_trip_no']) && isset($_POST['nu_trip_no']))
                    {
                        $trno   = $_POST['nu_trip_no'];
                    } 
                    $querych = "SELECT NU_TRIP_NO FROM TRANSPORT.HD_TRIP WHERE NU_TRIP_NO = '$trno' ";
                    $statement = oci_parse($this->dbmakess, $querych);
                    oci_execute($statement);
                    oci_fetch_all($statement, $res);
                    $numrows = oci_num_rows($statement);
                    unset($res);
                    oci_free_statement($statement);
                  
                if($numrows > 0)
                {   
                            $query = "DELETE FROM TRANSPORT.HD_TRIP WHERE NU_TRIP_NO = '$trno' ";
                            $statement = oci_parse($this->dbmakess, $query);
                            $ext = oci_execute($statement);
                            $r =oci_commit($this->dbmakess);
                            oci_free_statement($statement);
                            if (!$r) {
                                        $e = oci_error($this->dbmakess);
                                        trigger_error(htmlentities($e['message']), E_USER_ERROR);
                            }  else {
                                        if($this->checkvalue($trno)==1)
                                        {
                                            $success[] = array('delete'=>'1',"msg" => "All Data Deleted Successfully.");
                                            $this->response($this->json($success), 200);
                                        }else{
                                            $success[] = array('delete'=>'1',"msg" => "Deleted Successfully.");
                                            $this->response($this->json($success), 200);
                                        }  
                                        
                            } 
                }else{
                            $success[] = array('delete'=>'0',"msg" => "No Record Found.");
                            $this->response($this->json($success), 200);
                }   
         } else {
                     $this->response('', 401);
        }
    }    
    
    private function checkvalue($trno=NULL)
    {
        if(!empty($trno))
        {
                    $queryproduct = "SELECT VC_PRODUCT_CODE FROM TRANSPORT.DT_TRIP WHERE NU_TRIP_NO = '$trno'";
                    $statement = oci_parse($this->dbmakess, $queryproduct);
                    oci_execute($statement);
                    oci_fetch_all($statement, $res);
                    $numrows = oci_num_rows($statement);
                    unset($res);
                    oci_free_statement($statement);
                    if($numrows > 0)
                     {   
                            $query = "DELETE FROM TRANSPORT.DT_TRIP WHERE NU_TRIP_NO = '$trno' ";
                            $statement = oci_parse($this->dbmakess, $query);
                            $ext = oci_execute($statement);
                            $commit =oci_commit($this->dbmakess);
                            oci_free_statement($statement);
                            if (!$commit) {
                                        return 0;
                            }  else {
                                        return 1;
                            } 
                          
                     }else{
                          return 0;
                     }
           
        }else{
            return 0;
        }    
    }       






    /* PRODUCT DELETE THE FUNCTIONALITY WITH TRIP NO OF HD_TRIP TABLE */
    private function productdelete()
    {
         if ($_POST['status'] == 1) {
                    $ch_user_code = $_POST['ch_user_code'];
                    $comp_code = $_POST['vc_comp_code'];
                    if(!empty($_POST['nu_trip_no']) && isset($_POST['nu_trip_no']))
                    {
                        $trno   = $_POST['nu_trip_no'];
                    } 
                    if(!empty($_POST['vc_product_code']) && isset($_POST['vc_product_code']))
                    {
                        $productcode   = $_POST['vc_product_code'];
                    } 
                    $querych = "SELECT VC_PRODUCT_CODE FROM TRANSPORT.DT_TRIP WHERE NU_TRIP_NO = '$trno' AND VC_PRODUCT_CODE='$productcode' ";
                    $statement = oci_parse($this->dbmakess, $querych);
                    oci_execute($statement);
                    oci_fetch_all($statement, $res);
                    $numrows = oci_num_rows($statement);
                    unset($res);
                    oci_free_statement($statement);
                  
                if($numrows > 0)
                {   
                            $query = "DELETE FROM TRANSPORT.DT_TRIP WHERE NU_TRIP_NO = '$trno' AND VC_PRODUCT_CODE='$productcode' ";
                            $statement = oci_parse($this->dbmakess, $query);
                            $ext = oci_execute($statement);
                            $r =oci_commit($this->dbmakess);
                            oci_free_statement($statement);
                            if (!$r) {
                                        $e = oci_error($this->dbmakess);
                                        trigger_error(htmlentities($e['message']), E_USER_ERROR);
                            }  else {
                                        $success[] = array('pdelete'=>'1',"msg" => "Deleted Successfully.");
                                        $this->response($this->json($success), 200);
                            } 
                }else{
                            $success[] = array('pdelete'=>'0',"msg" => "No Record Found.");
                            $this->response($this->json($success), 200);
                }   
         } else {
                     $this->response('', 401);
        }
    }  
    
    
    private function printview()
    {
         if ($_POST['status'] == 1) {
                    $ch_user_code = $_POST['ch_user_code'];
                    $comp_code = $_POST['vc_comp_code'];
                    if(!empty($_POST['nu_trip_no']) && isset($_POST['nu_trip_no']))
                    {
                        $trno   = $_POST['nu_trip_no'];
                    } 
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
                                    AND HT.NU_TRIP_NO='$trno'";
                    $statement1 = oci_parse($this->dbmakess, $query1);
                    oci_execute($statement1);
                    oci_fetch_all($statement1, $res1, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    $numrows1 = oci_num_rows($statement1);
                    oci_free_statement($statement1);
                    
                    
                    $query2 = "SELECT DT.DT_TRIP_DATE, DT.VC_PRODUCT_CODE, MC.VC_CODE_DESC, DT.NU_QTY, DT.VC_REMARKS, 
                                      DT.VC_STATION_FROM, DT.VC_STATION_TO, DT.VC_FLOW, DT.VC_DEL ,DT.VC_LPO, DT.DT_RET_DATE
                               FROM   TRANSPORT.DT_TRIP DT, TRANSPORT.MSTCODE MC
                               WHERE DT.VC_PRODUCT_CODE = MC.VC_CODE AND MC.VC_CODE LIKE 'P%'AND DT.NU_TRIP_NO='$trno'";
                    $statement2 = oci_parse($this->dbmakess, $query2);
                    oci_execute($statement2);
                    oci_fetch_all($statement2, $res2, null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    $numrows2 = oci_num_rows($statement2);
                    oci_free_statement($statement2);
                    $row=array();
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
                        $this->response($this->json($row), 200);
                    } else {
                       $this->response($this->json($row), 204);
                    }
         }else{
             $row=array();
              $this->response('', 401);
         }          
        
    }       

}

// Initiiate Library

$api = new Dashboard;
$method = '';
switch (strtoupper($_GET['method'])) 
	{
            case 'SEARCHLISTING':
                                    $method ='searchlisting';	
                                    break;
            case 'SEARCH':
                                    $method ='search';	
                                    break;
            case 'DELETE':
                                    $method ='delete';	
                                    break;
            case 'PRODUCTDELETE':
                                    $method ='productdelete';	
                                    break;
            case 'PRINTVIEW':
                                    $method ='printview';	
                                    break;
            default:
                                    $method ='index';	
                                    break;
        }
        
 $api->processApi($method);

?>