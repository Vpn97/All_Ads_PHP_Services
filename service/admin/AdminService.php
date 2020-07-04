<?php

    class AdminService{
        public $con;

        public function __construct($con){
           $this->con=$con;
        }


 //get total users
        public function validateAdmin($admin_id,$mob_no){
            $sql="SELECT count(admin_id) FROM allads.admin_mst WHERE mob_no='$mob_no' AND admin_id=$admin_id";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            $row=mysqli_fetch_assoc($result);
            $count = $row["count(admin_id)"];
            return (int)$count;
        }


        public function validateAdminById($admin_id){
            $sql="SELECT count(admin_id) FROM allads.admin_mst WHERE admin_id=$admin_id";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            $row=mysqli_fetch_assoc($result);
            $count = $row["count(admin_id)"];
            return (int)$count;
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
                $response[$row['cat_name'].' Ads Earning'] = (double)$row['amount'];
            }

            return $response;
        }

          //get total by type
          public function getAdPriceMstDtls(){
            $response = array();
            $sql="SELECT * FROM ads_price_mst t1 
            WHERE t1.created_date = (SELECT MAX(t2.created_date) 
            FROM ads_price_mst t2 WHERE t2.user_type_id=t1.user_type_id)
            ORDER BY t1.user_type_id";

            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            while($row = $result->fetch_assoc()) {

                $sqlType="SELECT * FROM allads.user_type WHERE user_type_id='".$row['user_type_id']."' limit 1";
                $resultType = mysqli_query($this->con,$sqlType) or die(mysqli_error($this->con));
                $row['user_type']=$resultType->fetch_assoc();
                $response[] = $row;
                
            }

            return $response;
        }



        public function  getActiveAdsPriceMstFromUserType($userType){
            $response = array();
            $sql="SELECT * FROM allads.ads_price_mst WHERE is_active=1 AND user_type_id=$userType ORDER BY created_date DESC LIMIT 1";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            while($row = $result->fetch_assoc()) {

                $sqlType="SELECT * FROM allads.user_type WHERE user_type_id='".$row['user_type_id']."' limit 1";
                $resultType = mysqli_query($this->con,$sqlType) or die(mysqli_error($this->con));
                $row['user_type']=$resultType->fetch_assoc();
                $response[] = $row;
                
            }

            return $response;
        }



        public function updateAdPricesDtl($dtl){
            extract($dtl);
             // MySQL Start
            $this->con -> autocommit(FALSE);


            if(isset($ads_price_mst_id) && isset($user_type_id) && isset($amount_per_word) 
            && isset($amount_per_img) && isset($word_limit) && isset($lumpsum_amount) && isset($lumpsum_word_limit)
             && isset($admin_id) && isset($ads_time_limit_days)){

                $ads_price_mst_id=(int)$ads_price_mst_id;

                $admin_id=(int)$admin_id;
                $user_type_id=(int)$user_type_id;
                $amount_per_word=(double)$amount_per_word;
                $amount_per_img=(double)$amount_per_img;
                $lumpsum_amount=(double)$lumpsum_amount;
                $lumpsum_word_limit=(int)$lumpsum_word_limit;
                $word_limit=(int)$word_limit;
                $ads_time_limit_days=(int)$ads_time_limit_days;


                //find racoed 
                $sql="SELECT COUNT(ads_price_mst_id) FROM allads.ads_price_mst WHERE is_active=1 AND ads_price_mst_id=$ads_price_mst_id AND user_type_id=$user_type_id";
                $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
                $row=mysqli_fetch_assoc($result);
                $count = (int)$row["COUNT(ads_price_mst_id)"];
                
                if($count>=1){

                }else{
                    return 0;
                }


                $sqlInsert= "INSERT INTO `ads_price_mst` 
                (`ads_price_mst_id`, `user_type_id`, `amount_per_word`, `amount_per_img`, `word_limit`, 
                `lumpsum_amount`, `lumpsum_word_limit`, `admin_id`, `is_active`, `created_date`, `updated_date`,
                 `ads_time_limit_days`) 
                 VALUES (NULL, $user_type_id, $amount_per_word, $amount_per_img, $word_limit, $lumpsum_amount, $lumpsum_word_limit, 
                 $admin_id, 1, 
                 current_timestamp(), NULL, $ads_time_limit_days);";

               

                if(mysqli_query($this->con,$sqlInsert)){
                    // insert data in all table
                        if ($this->con -> commit()) {


                            $sqlUpdate="UPDATE `ads_price_mst` SET `is_active` = 0,
                                `updated_date` = current_timestamp() WHERE `ads_price_mst`.`ads_price_mst_id` = $ads_price_mst_id";
                                $result = $this->con->query($sqlUpdate) or die(mysqli_error($this->con));
                                if(mysqli_affected_rows($this->con)>=1){
                                    if ($this->con -> commit()) {
                                        return 1;
                                    } else {
                                        return 0;
                                    }
                                } else {
                                    return 1;
                                }

                        }else{

                            $this-> con -> rollback();
                            return 0;

                        }
                    }

                
             }else{
                 return 0;
                 $this->con -> rollback();
             }

        }


    }


?>