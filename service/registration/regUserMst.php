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
        

        if(!isset($_GET['user_name'])){
            $error_list[]=new Errors('REG003','user name cannot be empty','REG');
        }

        if(!isset($_GET['password'])){
            $error_list[]=new Errors('REG004','password cannot be empty','REG');
        }

         if(!isset($_GET['device_id'])){
            //$error_list[]=new Errors('REG004','device_id cannot be empty','REG');
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


            //check email id already registrated
        $sqlExist = "SELECT uid FROM allads.user_mst WHERE mob_no = '$mob_no' limit 1";
    
        $resultExist = mysqli_query($con,$sqlExist) or die(mysqli_error($con));
        
        if(mysqli_num_rows($resultExist)==1){
            $response['status']=false;
            $error_list[]=new Errors("REG001","mobile already exist",'REG');   
            $response['errors']=$error_list; 
        }else{

            $sql = "INSERT INTO `allads`.`user_mst` (`mob_no`, `user_name`, `password_hash`,`device_id`) VALUES 
            ('$mob_no', '$user_name', '$password', '$device_id')";


                if(mysqli_query($con,$sql)){
                // insert data in all table for user
                    if ($con -> commit()) {
                        $regService=new RegistrationService($con);
                        $userMst=$regService->getUserMstFromMobileNo($mob_no);
                        
                        if(isset($userMst)){
                            $response['status'] = true;
                            $response['user'] = $userMst;
                        }

                    }else{
                        $error_list[]=new Errors('REG007','Registration fail','REG');
                        $response['errors']=$error_list; 
                        $con -> rollback();
                    }
                }


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