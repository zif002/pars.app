<?
class Users  extends DB{
  public function __construct(){
    parent::__construct();
  }

  public function save($data){

   $query = $this->db->prepare("INSERT INTO users (first_name, last_name,id_vk, ip, access_token) VALUES (:first_name, :last_name, :id_vk, :ip, :access_token)");
   

        return $query->execute(array(':first_name'    => $data['first_name'], 
                                     ':last_name'     => $data['last_name'],
                                      ':id_vk'        => $data['vk_id'],
                                      ':ip'           => $data['ip'],
                                      ':access_token' => $data['access_token']

                              ));    
  }


}

