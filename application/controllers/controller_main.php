<?


class Controller_Main extends Controller{
	 function __construct(){
		$this->model = new Model_Main();
		$this->view = new View();
	}

    function action_index(){

    	$data = $this->model->get_data();
        $this->view->generate('main_view.php', 'main_template.php',$data);        
    }
    /**
    * @param string $pass
    *
    * @return string
    */
    public function generatePassword($pass){
        return md5('asdfsdfs' . $pass . 'sdkflskjflksdjflskj');
    }
    

    /**
    * @param string $pass
    *
    * @return string
    */
    public function generateHashCookie($data){
        return md5('sdf' . $data . 'sdf');
    }

    /**
    * @param $login
    * @param $pass
    *
    * @return bool
    */
    public function auth($login, $pass){

        if ( !empty( $_COOKIE['uid'] ) && !empty( $_COOKIE['pwd'] ) ) {
            $id = (int)$_COOKIE['uid'];
            $pwd = (string)$_COOKIE['pwd'];
            
            $query1 = $this->db->prepare("SELECT * FROM users WHERE id=:id");
            $data = $query1->execute([':id' => $id]);

            if ( $data && ( $this->generateHashCookie($pwd) == $data['access_token'] ) ) {
                $_SESSION['user_id'] = $data['id'];
                return true;
            }
        }
       var_dump($this);
        $query1 = $this->db->prepare("SELECT * FROM users WHERE login=:login");

        $data = $query1->execute([':login' => $login]);
        print_r($data);

        if (!$data) {
            return false;
        }

        if ($data['password'] == $this->generatePassword($pass)) {

        $_SESSION['user_id'] = $data['id'];

        // query -> remember_token


        setcookie('uid', $data['id'], 86400, '/');
        setcookie('pwd', $data['remember_token'], 86400, '/');

        return true;
        }

        return false;
    }

    public function save($data){
    //print_r($data);
        $query1 = $this->db->prepare("SELECT * FROM users WHERE id_vk=:id_vk");
         $res1 = $query1->execute(array(':id_vk' => $data['vk_id']));

        while ( $res = $query1->fetch(PDO::FETCH_ASSOC) ) {
        // print_r($res);
            if ( $res['id_vk'] == $data['vk_id'] ) {
            return false;
            }
        //echo $res['id_vk'];
        }

        $query = $this->db->prepare("INSERT INTO users (first_name, last_name,id_vk, ip, access_token) VALUES (:first_name, :last_name, :id_vk, :ip, :access_token)");

        return $query->execute(array(
        ':first_name' => $data['first_name'],
        ':last_name' => $data['last_name'],
        ':id_vk' => $data['vk_id'],
        ':ip' => $data['ip'],
        ':access_token' => $data['access_token']

        ));

    }
}
