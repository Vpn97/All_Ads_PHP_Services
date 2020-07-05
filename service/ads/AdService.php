<?php

    class AdService{
        public $con;

        public function __construct($con){
           $this->con=$con;
        }
    
        public function getAdFromAdId($adId){


            $sql="SELECT * FROM allads.ads_mst WHERE ad_id=$adId";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            $row=mysqli_fetch_assoc($result);
            

            $images=array();
            $sqlImages="SELECT * FROM allads.ad_image_dtl WHERE ad_id=$adId";
            $resultImages = mysqli_query($this->con,$sqlImages) or die(mysqli_error($this->con));
           
            while($rowImg = $resultImages->fetch_assoc()) {
                $images[] = $rowImg;
            }

            $sqlOrder="SELECT * FROM allads.order_mst WHERE ad_id=$adId";
            $resultOrder = mysqli_query($this->con,$sqlOrder) or die(mysqli_error($this->con));
            $rowOrder=mysqli_fetch_assoc($resultOrder);

            $row['user_dtl']=$this->getUserMstFromUID($row['uid']);
            $row['images'] =$images;
            $row['order_dtl'] = $rowOrder;
            $row['category_dtl']=$this->getCategoryDtlFromCatId($row['cat_id']);
            $row['sub_cat_dtl']=$this->getSubCategoryDtlFromSubCatId($row['sub_cat_id']);

            return $row;
            
        }



        public function getUserMstFromUID($uid){
            $sql="SELECT uid,mob_no,user_name,address_id,user_type_id,email FROM allads.user_mst WHERE uid=$uid";
            $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
            $row=mysqli_fetch_assoc($result);
            return $row;
        }

            //get total ads 
            public function getActiveAds(){
                $sql="SELECT count(ad_id) FROM allads.ads_mst WHERE is_active=1";
                $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
                $row=mysqli_fetch_assoc($result);
                $count = $row["count(ad_id)"];
                return (int)$count;
            }

        public function getCategoryDtlFromCatId($catId){

            if(isset($catId)){
                $sql="SELECT * FROM allads.category WHERE cat_id=$catId";
                $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
                $row=mysqli_fetch_assoc($result);
                return $row;
            }
            return null;
        }


        public function getSubCategoryDtlFromSubCatId($subCatId){

            if(isset($subCatId)){
                $sql="SELECT * FROM allads.sub_category WHERE sub_cat_id=$subCatId";
                $result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
                $row=mysqli_fetch_assoc($result);
                return $row;
            }
            return null;
        }


        public function getAdsPagination($pagination){
            # code...
            $this->con -> autocommit(FALSE);
           
            extract($pagination);

            $totalAds=array();
            $response=array();
            $adIds=array();
            $paginationData=array();

            $sqlData="SELECT ad_id FROM allads.ads_mst WHERE is_active=1 ORDER BY created_date DESC";

            if(!isset($page)){
                $page=1;   
            }

            if(!isset($ads_per_page)){
                $ads_per_page=10;   
            }

            $start_from = ($page-1) * $ads_per_page;

            $sqlData=$sqlData." LIMIT $start_from, $ads_per_page";
            $result = mysqli_query($this->con,$sqlData) or die(mysqli_error($this->con));
            while($row = $result->fetch_assoc()) {
                $adIds[]=$row['ad_id'];
                $adMst=$this->getAdFromAdId($row['ad_id']);
                $totalAds[] = $adMst;
            }



            $paginationData['page']=$page;
            $paginationData['next_page']=$page+1;
            $paginationData['ads_per_page']=$ads_per_page;
            if (!empty($adIds)) {
                // list is empty.
            $paginationData['start_ad_id']=reset($adIds);
            $paginationData['end_ad_id']=array_values(array_slice($adIds, -1))[0];
           }
          

            $response['pagination_data']=$paginationData;
            $response['ads']=$totalAds;
            $response['total_active_ads']=$this->getActiveAds();
            $response['total_ad_in_page']=count($adIds);
            return $response;

        }

    



}

?>