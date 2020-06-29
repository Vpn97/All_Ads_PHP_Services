<?php

    class CommonService{
        public $con;

        public function __construct($con){
           $this->con=$con;
        }

        public function getUserTypes(){
            $userTypes=array();

            $sql="SELECT * FROM allads.user_type";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            
            while($row = $result->fetch_assoc()) {
                $userTypes[] = $row;
            }

            return $userTypes;
        }

        
    }

?>