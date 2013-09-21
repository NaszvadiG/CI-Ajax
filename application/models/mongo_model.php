<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tienn2t
 * Date: 4/8/13
 * Time: 5:42 PM
 * To change this template use File | Settings | File Templates.
 */
class Mongo_Model extends CI_Model{
    public  function __construct(){
        $this->load->database();
        $this->mongo = new MongoClient();
    }

    public function get($collection,$query=FALSE){
        if($query!=FALSE){
            $this->db->where($query);
        }
        $query = $this->db->get($collection);
        return $query->result();
    }

    /**
     * @param $collection
     * @param $data
     * @return array(n=>int(0))
     */
    public function insert($collection,$data){
        $rs = $this->db->insert($collection,$data);
        return $rs;
    }

    /**
     * update $data to $collection with condition $query
     * @param $collection
     * @param $data = array('$set'=>array());
     * @param $query
     * return array('n'=>int,...) meaning number of records affected
     */
    public function update($collection,$data,$query){
        /*try{
        $tb = $this->mongo->selectCollection($this->namedb,$collection);
        $result = $tb->update($query,$data,array('multiple'=>1));
        return $result;
        }catch (Exception $e){
            echo $e->getMessage();
        }*/
        try{
            $this->db->where($query);
            foreach ($data as $key => $value) {
               $this->db->set($key,$value);
            }
            $result = $this->db->update($collection);
            return $result;
        }catch (Exception $ex){
            echo $ex->getMessage();
        }
        //neu la mongo javascript thi update multi document la dung multi, phpmongo la multiple
    }

    function count_documents($collection,$query=FALSE){
        if($query!=FALSE){
            $this->db->where($query);
        }
        $data = $this->db->count_all_results($collection);
        return $data;
    }

    /**
     * @return MongoCursor
     */
    public function get_campaign($action,$collection,$query,$limit=FALSE,$start=FALSE){
        if($query!=''){
            $this->db->where($query);
        }

        if($action=='total'){
            $campaign = $this->db->count_all_results($collection);
        }else{
//            $campaign = $this->get('campaigns',array('advertiser_id'=>new MongoId($advertisers_id)));
            if($limit!=FALSE){
                $campaign = $this->db->get($collection,$limit,$start);
            }else{
                $campaign = $this->db->get($collection);
            }
            return $campaign->result();
        }

        return $campaign;
    }

    /**
     * remove document in collection
     * @param $collection
     * @param bool $query
     * @throws Exception
     * return array('n'=>int,...) meaning number of records affected
     */
    public function delete($collection,$query=FALSE){
        if($query!=FALSE&&!is_array($query)){
            throw new Exception('Must be array');
        }
        if($query!=FALSE){
            $this->db->where($query);
        }
        $result = $this->db->delete($collection);
        return $result;
    }

    /**
     * function lay luot click, view, install, down ung voi campaign
     * @param $profiles
     * @return mixed
     */
    public function get_report_campaign($profiles,$banner=FALSE){
        $this->db->select(array('actives'=>1,'clicks'=>1,'downloads'=>1,'impressions'=>1,'installs'=>1,'views'=>1));
        if($banner!=FALSE){
            $this->db->where($banner);
        }else{
            $this->db->where(array('profile_id'=>array('$in'=>$profiles)));
        }
        $data = $this->db->get('banners');
        return $data->result();
    }
    /**
     * function lay luot click, view, install, down ung voi campaign
     * @param $profiles
     * @return mixed
     */
    public function get_report_campaign_with_type($profiles,$ads_type,$banner=FALSE){
        //select field to get data with $ads_type
        switch($ads_type){
            case 'cpim':
                $select = array('requests'=>1,'impressions'=>1);
                break;
            case 'cpc':
                $select = array('requests'=>1,'impressions'=>1,'clicks'=>1);
                break;
            case 'cpv':
                $select = array('requests'=>1,'impressions'=>1,'clicks'=>1,'views'=>1);
                break;
            case 'cpd':
                $select = array('requests'=>1,'impressions'=>1,'clicks'=>1,'views'=>1,'downloads'=>1);
                break;
            case 'cpin':
                $select = array('requests'=>1,'impressions'=>1,'clicks'=>1,'views'=>1,'downloads'=>1,'installs'=>1);
                break;
            default:
                $select = array('requests'=>1,'actives'=>1,'clicks'=>1,'downloads'=>1,'impressions'=>1,'installs'=>1,'views'=>1);
                break;
        }
        $this->db->select($select);
        if($banner!=FALSE){
            $this->db->where($banner);
        }else{
            $this->db->where(array('profile_id'=>array('$in'=>$profiles)));
        }
        $data = $this->db->get('banners');
        return $data->result();
    }

    /**
     * function select _id, $name,from campaign collections
     * @param bool $campaign_id
     * @return mixed
     */
    public function get_id_name_campaign($campaign_id=FALSE){
        $this->db->select(array('_id'=>1,'name'=>1,'profiles'=>1));
        if($campaign_id!=FALSE){
            $this->db->where(array('advertiser_id'=>$this->session->userdata('user_id'),'_id'=>$campaign_id));
        }else{
            $this->db->where(array('advertiser_id'=>$this->session->userdata('user_id')));
        }
        $query = $this->db->get('campaigns');
        return $query->result();
    }

    /**
     * select common
     * @param $collection
     * @param $select
     * @param bool $query
     * @return mixed
     */
    public function get_select($collection,$select,$query=FALSE){
        $this->db->select($select);
        if($query!=FALSE){
            $this->db->where($query);
        }
        $query = $this->db->get($collection);
        return $query->result();
    }
    public function getByDataLimit($collection,$query,$limit=FALSE,$start=FALSE){
        if($query!=''){
            $this->db->where($query);
        }
        if($limit!=FALSE){
            $data = $this->db->get($collection,$limit,$start);
        }else{
            $data = $this->db->get($collection);
        }
        return $data;
    }

    //count array nested in a document
    public function count_array_nested($action,$query,$limit=FALSE,$start_from = FALSE){
        if($action=='total'){
            if($this->session->userdata('date_search')!=''){
                $op = array(array('$unwind'=>'$funds'),
                    array('$project'=>array('funds'=>1)),$query,array('$group'=>array('_id'=>null,'count'=>array('$sum'=>1))));
                $info = $this->db->aggregate('advertisers',$op);
                if(count($info)>0){
                return $info[0]['count'];
                }
                return 0;
            }
            $op = array(array('$unwind'=>'$funds'),
                array('$project'=>array('funds'=>1)),$query,array('$group'=>array('_id'=>null,'count'=>array('$sum'=>1))));
            $info = $this->db->aggregate('advertisers',$op);
            if(count($info)>0){
                return $info[0]['count'];
            }
            return 0;
            /*$this->db->select('funds');
            $this->db->where($query);
            $info = $this->db->get('advertisers');
            foreach ($info->result_array() as $rs) {
                return count($rs['funds']);
            }
            return 0;*/
        }else{
            try{
            if($this->session->userdata('date_search')!=''){
                $op = array(array('$unwind'=>'$funds'),
                    array('$project'=>array('funds'=>1)),$query,array('$skip'=>(int)$start_from),array('$limit'=>(int)$limit)
                );
                $info = $this->db->aggregate('advertisers',$op);
                $result = array();
                foreach ($info as $rs) {
                    $result[]=$rs['funds'];
                }
                return $result;
            }
            //using $slice to get item in array with start and limit
            $this->db->where($query); //echo 'startfrom='.$start_from.'-limit='.$limit;
            $this->db->select(array('amount'=>1,'info.name'=>1,'funds'=>array('$slice'=>array((int)$start_from,(int)$limit))));
            $data = $this->db->get('advertisers');
            $data = $data->result_array();
            }catch (Exception $ex){
                echo $ex->getMessage();
            }
            foreach ($data as $rs) {
                if(count($rs)>0){
                    return $rs['funds'];
                }
            }
            return array();

        }
    }

    public function countData($collection, $query=FALSE){
        if($query!=FALSE){
            $this->db->where($query);
        }
        if($collection!=''){
            $data =  $this->db->count_all_results($collection);
        }
        return $data;
    }

    public function get_from_reports($profiles,$ads_type=FALSE){
        //select field to get data with $ads_type
        if($ads_type==FALSE){
            $ads_type = 'all';
        }
        switch($ads_type){
            case 'cpim':
                $select = array('requests'=>1,'impressions'=>1);
                break;
            case 'cpc':
                $select = array('requests'=>1,'impressions'=>1,'clicks'=>1);
                break;
            case 'cpv':
                $select = array('requests'=>1,'impressions'=>1,'clicks'=>1,'views'=>1);
                break;
            case 'cpd':
                $select = array('requests'=>1,'impressions'=>1,'clicks'=>1,'views'=>1,'downloads'=>1);
                break;
            case 'cpin':
                $select = array('requests'=>1,'impressions'=>1,'clicks'=>1,'views'=>1,'downloads'=>1,'installs'=>1);
                break;
            default:
                $select = array('requests'=>1,'actives'=>1,'clicks'=>1,'downloads'=>1,'impressions'=>1,'installs'=>1,'views'=>1);
                break;
        }

        $this->db->select($select);
        $this->db->where(array('profile_id'=>array('$in'=>$profiles)));
        $data = $this->db->get('reports');
        return $data->result();
    }

}
?>