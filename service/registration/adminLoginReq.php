<?php

    header('Content-Type:application/json');
    require_once '../config.php';
    require_once '../../util/Errors.php';
    require_once 'RegistrationService.php';

    if(!empty($_GET)){
        
        $response=array();
        $error_list=array();

        if(!isset($_GET['mob_no'])){
            $error_list[]=new Errors('ADMIN001','mob_no cannot be empty','REG');
        }
        

        if(!isset($_GET['push_data'])){
            //$error_list[]=new Errors('ADMIN002','push_data cannot be empty','REG');
            $push_data='N';
        }
        
        
        if(!empty($error_list)){
            $response['status']=false;
            $response['errors']=$error_list;
            mysqli_close($con);	
            echo json_encode($response);
        }else{
            extract($_GET);
           // $password=md5($password);
            // MySQL Start
            $con -> autocommit(FALSE);
            $sqlExist = "SELECT uid FROM allads.admin_mst WHERE mob_no = '$mob_no' limit 1";
    
            $resultExist = mysqli_query($con,$sqlExist) or die(mysqli_error($con));
        
                if(mysqli_num_rows($resultExist)==1){

                    if($push_data=='Y'){

                        $SqlReq="SELECT * FROM allads.admin_mst WHERE mob_no = '$mob_no' limit 1";
                        $resultReq = mysqli_query($con,$SqlReq) or die(mysqli_error($con));
                        $row=mysqli_fetch_assoc($resultReq); 
                            
                            if(mysqli_num_rows($resultReq)==1){
                                    $response['status']=TRUE;
                                    $response['admin']=$row;

                            }else{
                                $response['status']=FALSE;
                                $error_list[]=new Errors('ADMIN003','Mobile Number not register with allads','REG');
                                $response['errors']=$error_list;
                            }

                    }else{
                        $response['status']=TRUE;
                    }
                    
                }else{
                    $response['status']=false;
                    $error_list[]=new Errors("ADMIN003","Mobile Number not register with allads",'REG');   
                    $response['errors']=$error_list; 
                }
                mysqli_close($con);	
                echo json_encode($response);
        }
    }else{
        $response = array();
        $response['status']=false;
        $error_list[]=new Errors("LOGIN005","Invalid request",'REG');
        $response['errors']=$error_list; 
        mysqli_close($con);	
        echo json_encode($response);
    }

?>