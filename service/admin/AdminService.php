<?php

    class AdminService{
        public $con;

        public function __construct($con){
           $this->con=$con;
        }


    //get total users
    public function getActiveUsers(){
        $sql="SELECT count(mob_no) FROM allads.user_mst";
        $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
        $row=mysqli_fetch_assoc($result);
        $count = $row["count(mob_no)"];
        return (int)$count;
    }


        //get total ads 
        public function getActiveAds(){
            $sql="SELECT count(ad_id) FROM allads.ads_mst WHERE is_active=1";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            $row=mysqli_fetch_assoc($result);
            $count = $row["count(ad_id)"];
            return (int)$count;
        }


         //get total ads 
         public function getTotalAds(){
            $sql="SELECT count(ad_id) FROM allads.ads_mst";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            $row=mysqli_fetch_assoc($result);
            $count = $row["count(ad_id)"];
            return (int)$count;
        }

          //get total earning 
          public function getTotalEarning(){
            $sql="SELECT SUM(ad_price) FROM allads.ads_mst";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            $row=mysqli_fetch_assoc($result);
            $count = $row["SUM(ad_price)"];
            return (double)$count;
        }


         //get total by type
         public function getTotalEarningByType(){
            $response = array();
            $sql="SELECT cat.cat_name as cat_name, COALESCE(SUM(ads.ad_price),0) as amount FROM category cat LEFT JOIN ads_mst ads ON cat.cat_id=ads.cat_id GROUP BY cat.cat_id";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            while($row = $result->fetch_assoc()) {
                $response[$row['cat_name']] = (double)$row['amount'];
            }

            return $response;
        }


    }


?>