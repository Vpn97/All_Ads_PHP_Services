<?php
    //validate user_id is alredy reg or not. -- REG011
    header('Content-Type:application/json');
    require_once '../config.php';
    require_once '../../util/Errors.php';
    require_once 'RegistrationService.php';

    if(!empty($_GET)){
        
        $response=array();
        $error_list=array();

        if(!isset($_GET['mob_no'])){
            $error_list[]=new Errors('REG002','mob_no cannot be empty','REG');
        }
        
        
        if(!empty($error_list)){
            $response['status']=false;
            $response['errors']=$error_list;
            mysqli_close($con);	
            echo json_encode($response);
        }else{
            extract($_GET);
            //check email id already registrated
        $sqlExist = "SELECT uid FROM allads.user_mst WHERE mob_no = '$mob_no' and is_mob_verify=1 limit 1 ";
    
        $resultExist = mysqli_query($con,$sqlExist) or die(mysqli_error($con));
        
        if(mysqli_num_rows($resultExist)==1){
            $response['status']=true; 
        }else{
            $response['status']=false;
        }

            mysqli_close($con);	
            echo json_encode($response);
        }
        

    }else{
        $response = array();
        $response['status']=false;
        mysqli_close($con);	
        echo json_encode($response);
    }


?>