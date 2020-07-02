<?php

//login user service
header('Content-Type:application/json');
require_once '../config.php';
require_once '../../util/Errors.php';
require_once 'RegistrationService.php';

if ( !empty( $_GET ) ) {

    $response = array();
    $error_list = array();

    if ( !isset( $_GET['password'] ) ) {
        $error_list[] = new Errors( 'PASS001', 'password cannot be empty', 'REG' );
    }

    if ( !isset( $_GET['mob_no'] ) ) {
        $error_list[] = new Errors( 'PASS005', 'mob_no cannot be empty', 'REG' );
    }

    if ( !empty( $error_list ) ) {
        $response['status'] = false;
        $response['errors'] = $error_list;
        mysqli_close( $con );
        echo json_encode( $response );

    } else {

        extract( $_GET );
        $password = md5( $password );

        // MySQL Start
        $con -> autocommit( FALSE );
        //update pasword

            $sql = "UPDATE `allads`.`user_mst` SET `password_hash` = '$password', update_date=current_timestamp() WHERE mob_no='$mob_no';";
            $result = $con->query($sql) or die(mysqli_error($con));
            
            if(mysqli_affected_rows($con)>=1){
                if ($con -> commit()) {
                    $response['status'] = true;
                } else {
                    $error_list[] = new Errors( 'PASS007', 'server error please try again', 'REG' );
                    $response['errors'] = $error_list;
                    $con -> rollback();
                }
            }
   

        mysqli_close( $con );
        echo json_encode( $response );
    }

}else{
    $error_list[]=new Errors("PASS006","Invalid request",'REG'); 
    $response['status'] = false;
    $response['errors'] = $error_list;
    mysqli_close( $con );
    echo json_encode( $response );

}
?>