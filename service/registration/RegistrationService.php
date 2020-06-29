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

    }


?>