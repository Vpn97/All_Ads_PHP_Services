<?php


header('Content-Type:application/json');
require_once '../config.php';
require_once '../../util/Errors.php';
require_once 'AdService.php';
$response=array();
$paginationData=json_decode(file_get_contents('php://input'), true);
$adService =new AdService($con);
$response = $adService->getAdsPagination($paginationData);

mysqli_close($con);	
echo json_encode($response);
?>