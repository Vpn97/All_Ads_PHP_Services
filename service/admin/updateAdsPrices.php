<?php

    header('Content-Type:application/json');
    require_once '../config.php';
    require_once '../../util/Errors.php';
    require_once 'AdminService.php';

    if(!empty($_GET)){
        
        $response=array();
        $error_list=array();

        if(!isset($_GET['admin_id'])){
            $error_list[]=new Errors('UPDATE001','admin_id cannot be empty','ADMIN');
        }
        

        if(!isset($_GET['admin_mob'])){
            $error_list[]=new Errors('UPDATE002','admin_mob cannot be empty','ADMIN');
        }
        
        
        if(!empty($error_list)){
            $response['status']=false;
            $response['errors']=$error_list;
            mysqli_close($con);	
            echo json_encode($response);
        }else{
            extract($_GET);
            // MySQL Start
            $con -> autocommit(FALSE);
            
            $adminService=new AdminService($con);

            $adminVal=$adminService->validateAdmin($admin_id,$admin_mob);

            if($adminVal==1){
               
                //update prices
                
                $dtl=json_decode(file_get_contents('php://input'), true);
                $rowEffect=$adminService->updateAdPricesDtl($dtl);
                if($rowEffect>=1){
                    $userType=(int)$dtl['user_type_id'];
                    $response['status']=true;
                    $response['ads_price_mst']=$adminService->getActiveAdsPriceMstFromUserType($userType);

                }else{
                    $response['status']=false;
                    $error_list[]=new Errors("UPDATE003","Record not found or invalid request",'ADMIN'); 
                    $response['errors']=$error_list;
                }

            }else{
                $response['status']=false;
                $error_list[]=new Errors("UPDATE004","Request not exist",'ADMIN'); 
                $response['errors']=$error_list;
            }


            mysqli_close($con);	
            echo json_encode($response);
        }
    }else{
        $response = array();
        $response['status']=false;
        $error_list[]=new Errors("UPDATE004","Invalid request",'ADMIN'); 
        $response['errors']=$error_list;
        mysqli_close($con);	
        echo json_encode($response);
    }

?>