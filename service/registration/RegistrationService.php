<?php

    class RegistrationService{
        public $con;

        public function __construct($con){
           $this->con=$con;
        }
    
        public function getUserMstFromMobileNo($mob){
            $sql="SELECT * FROM allads.user_mst WHERE mob_no=$mob";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            $row=mysqli_fetch_assoc($result);
            return $row;
        }


        public function getUIDFromMobNo($mob){
            # code...
           $sql="SELECT uid as 'uid' FROM allads.user_mst where mob_no='$mob'";
           $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
           $row=mysqli_fetch_assoc($result);
           return (int)$row['uid'];
        }


        public function setMobIsVerifyed($mob_no){
            # code...
            $this->con-> autocommit( FALSE );

            $sql = "UPDATE user_mst SET is_mob_verify = '1', update_date = current_timestamp() WHERE mob_no = $mob_no";
            $result = $this->con->query($sql) or die(mysqli_error($this->con));
            
            if(mysqli_affected_rows($this->con)>=1){
                if ($this->con -> commit()) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 1;
            }

        }


        public function updateDeviceID($deviceId,$mob_no){
            # code...
            $this->con-> autocommit( FALSE );

            $sql = "UPDATE user_mst SET device_id = '$deviceId', update_date = current_timestamp() WHERE mob_no = $mob_no";
            $result = $this->con->query($sql) or die(mysqli_error($this->con));
            
            if(mysqli_affected_rows($this->con)>=1){
                if ($this->con -> commit()) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 1;
            }

        }


    }


?>