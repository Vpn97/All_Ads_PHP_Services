<?php

header('Content-Type:application/json');
    require_once '../config.php';
    require_once '../../util/Errors.php';
    require_once 'AdminService.php';


    $response=array();
    $adminService=new AdminService($con);
    $response['Total Users']=$adminService->getActiveUsers();
    $response['Active Ads']=$adminService->getActiveAds();
    $response['Total Ads']=$adminService->getTotalAds();
    $response['Total Eaning']=$adminService->getTotalEarning();


    $rtn=$adminService->getTotalEarningByType();
    $response=array_merge($response,$rtn);

    mysqli_close($con);	
    echo json_encode($response);
?>