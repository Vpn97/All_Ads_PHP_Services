<?php
header( 'Content-Type:application/json' );
require_once '../config.php';
require_once 'CommonService.php';
require_once '../../util/Errors.php';

$response = array();
$error_list = array();
$commonService=new CommonService($con);


$userTypes=$commonService->getUserTypes();
if(isset($userTypes)){
    $response['status'] = true;
    $response['user_types'] = $userTypes;
}else{
    $response['status'] = false;
}

mysqli_close( $con );
echo json_encode( $response );

?>