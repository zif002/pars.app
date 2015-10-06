<?


class Controller_Main extends Controller{
    private $db ;
	 function __construct(){
		$this->model = new Model_Main();
		$this->view = new View();
        $db = new DB();
        $this->db = $db->getDb();

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
        return md5('asdfsdfs' . $data . 'sdkflskjflksdjflskj');
    }

    /**
    * @param $login
    * @param $pass
    *
    * @return bool
    */
    public function auth($login, $pass){
        echo $login,$pass;
        if ( !empty( $_COOKIE['uid'] ) && !empty( $_COOKIE['pwd'] ) ) {
            $id = (int)$_COOKIE['uid'];
            $pwd = (string)$_COOKIE['pwd'];
            
            $query1 = $this->db->prepare("SELECT * FROM users WHERE id_vk=:id_vk");
            $data = $query1->execute([':id_vk' => $id_vk]);

            print_r($data);
            if ( $data && ( $this->generateHashCookie($pwd) == $data['access_token'] ) ) {
                $_SESSION['user_id'] = $data['id'];
                return true;
            }
        }
       //var_dump($this);
        $query1 = $this->db->prepare("SELECT * FROM users WHERE login=:login");        
        $query1->execute([':login' => $login]);
       
        $data=$query1->fetch();
        

        if (!$data) {
            return false;
        }
        //echo $this->generatePassword($pass);
        //print_r($data['pass']);
        if ($data['pass'] == $this->generatePassword($pass)) {
        //echo $this->generatePassword($pass);
        $_SESSION['user_id'] = $data['id_vk'];

        // query -> remember_token


        setcookie('uid', $data['id_vk'], 86400, '/');
        setcookie('pwd', $data['pass'], 86400, '/');
        print_r($_COOKIE);

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
