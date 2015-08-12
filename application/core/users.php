<?
class Users  extends DB{
  public function __construct(){
    parent::__construct();
  }

  public function save($data){
    //print_r($data);
    $query1 = $this->db->prepare("SELECT * FROM users WHERE id_vk=:id_vk");
    $res1 = $query1->execute(array(':id_vk' => $data['vk_id']));
    
    
    while($res = $query1->fetch(PDO::FETCH_ASSOC)){
     // print_r($res);
        if($res['id_vk'] == $data['vk_id']){
           return false;
        }
        //echo $res['id_vk'];
    }

    
    $query = $this->db->prepare("INSERT INTO users (first_name, last_name,id_vk, ip, access_token) VALUES (:first_name, :last_name, :id_vk, :ip, :access_token)");
   

        return $query->execute(array(':first_name'    => $data['first_name'], 
                                     ':last_name'     => $data['last_name'],
                                      ':id_vk'        => $data['vk_id'],
                                      ':ip'           => $data['ip'],
                                      ':access_token' => $data['access_token']

                              ));    

  }

}

