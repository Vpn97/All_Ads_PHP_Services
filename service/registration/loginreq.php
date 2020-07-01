<?php

    header('Content-Type:application/json');
    require_once '../config.php';
    require_once '../../util/Errors.php';
    require_once 'RegistrationService.php';

    if(!empty($_GET)){
        
        $response=array();
        $error_list=array();

        if(!isset($_GET['mob_no'])){
            $error_list[]=new Errors('LOGIN001','mob_no cannot be empty','REG');
        }

        if(!isset($_GET['password'])){
            $error_list[]=new Errors('LOGIN002','password cannot be empty','REG');
        }
        
        
        if(!empty($error_list)){
            $response['status']=false;
            $response['errors']=$error_list;
            mysqli_close($con);	
            echo json_encode($response);
        }else{
            extract($_GET);
            $password=md5($password);
            // MySQL Start
            $con -> autocommit(FALSE);
            $sqlExist = "SELECT uid FROM allads.user_mst WHERE mob_no = '$mob_no' limit 1";
    
            $resultExist = mysqli_query($con,$sqlExist) or die(mysqli_error($con));
        
                if(mysqli_num_rows($resultExist)==1){

                    $SqlReq="SELECT * FROM allads.user_mst WHERE mob_no = '$mob_no' and password_hash='$password' limit 1";
                    $resultReq = mysqli_query($con,$SqlReq) or die(mysqli_error($con));
                    $row=mysqli_fetch_assoc($resultReq); 
                        
                        if(mysqli_num_rows($resultReq)==1){
                                $response['status']=TRUE;
                                $response['user']=$row;
                                //update device id
                                if(!isset($_GET['device_id'])){
                                   // $error_list[]=new Errors('LOGIN002','password cannot be empty','REG');
                                   $response['user']=$row;
                                }else{
                                    $regService=new RegistrationService($con);
                                    $regService->updateDeviceID($device_id,$mob_no);
                                    $response['user']=$regService->getUserMstFromMobileNo($mob_no);
                                }

                        }else{
                            $response['status']=FALSE;
                            $error_list[]=new Errors('LOGIN004','invalid user id and password.','REG');
                            $response['errors']=$error_list;
                        }
                    
                }else{
                    $response['status']=false;
                    $error_list[]=new Errors("LOGIN003","mobile not register with allads",'REG');   
                    $response['errors']=$error_list; 
                }
                mysqli_close($con);	
                echo json_encode($response);
        }
    }else{
        $response = array();
        $response['status']=false;
        $error_list[]=new Errors("LOGIN005","Invalid request",'REG'); 
        mysqli_close($con);	
        echo json_encode($response);
    }

?>