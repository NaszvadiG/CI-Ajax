<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tienn2t
 * Date: 5/13/13
 * Time: 3:50 PM
 * To change this template use File | Settings | File Templates.
 */
class Model_Backend extends CI_Model{
    /**
     * contructor
     */
    public  function __construct(){
        $this->load->database();
        $this->mongo = new MongoClient();
        $this->namedb = 'ads';
    }

    /**
     * @param bool $campaign_id
     * @return mixed
     */
    public function get_id_name_campaign($adv_id,$campaign_id=FALSE){
        $this->db->select(array('_id'=>1,'name'=>1,'profiles'=>1));
        if($campaign_id!=FALSE){
            $this->db->where(array('advertiser_id'=>$adv_id,'_id'=>$campaign_id));
        }else{
            $this->db->where(array('advertiser_id'=>$adv_id));
        }
        $query = $this->db->get('campaigns');
        return $query->result();
    }

    public function get_select($collection,$select,$query='',$limit=FALSE,$start=FALSE){
        $this->db->select($select);
        if($query!=''){
            $this->db->where($query);
        }
        if($limit!=FALSE){
            $query = $this->db->get($collection,$limit,$start);
        }else{
            $query = $this->db->get($collection);
        }
        return $query->result();
    }

    public function count_array_nested($action,$query,$limit=FALSE,$start_from = FALSE){
        if($action=='total'){
            $op = array(array('$unwind'=>'$permission.advertisers'),
                array('$project'=>array('permission.advertisers'=>1)),$query,array('$group'=>array('_id'=>null,'count'=>array('$sum'=>1))));
            $info = $this->db->aggregate('admin',$op);
            if(count($info)>0){
                return $info[0]['count'];
            }
            return 0;
        }else{
            //using $slice to get item in array with start and limit
            $this->db->where($query); //echo 'startfrom='.$start_from.'-limit='.$limit;
            $this->db->select(array('permission.advertisers'=>array('$slice'=>array((int)$start_from,(int)$limit))));
            $data = $this->db->get('admin');
            $data = $data->result_array();
            return $data;
        }
    }
}
?>
