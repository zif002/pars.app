<?
class Controller_VK extends Controller
{
    public $VK_APP_ID;
    public $VK_SECRET_CODE;
    public $code;
    public $data;
    public $access_token;
    public $album_id;
    public $group_id;

    function __construct()
    {
        $this->model = new Model_VK();
        
        $this->view = new View();
    }

    function action_index()
    {
        $data = $this->model->get_data();		
        $this->view->generate('vk_view.php', 'main_template.php',$data);
    }
    function get_data_acsess_token($VK_APP_ID = "4976017",$VK_SECRET_CODE = "7SUe2GWNds2mXPRWAuRN",$code){
        $this->VK_APP_ID = $VK_APP_ID;
        $this->VK_SECRET_CODE = $VK_SECRET_CODE;
        $this->code = $code;
     //отправка запроса на получение accses token  
    $vk_grand_url = "https://api.vk.com/oauth/access_token?client_id=".$this->VK_APP_ID."&client_secret=".$this->VK_SECRET_CODE."&code=".$this->code."&redirect_uri=http://pars.app/vk";
        
    // отправляем запрос на получения access token
      $resp = file_get_contents($vk_grand_url);
      $this->data = json_decode($resp, true); 
      return $this->data;    
      
    }
    function load_photos_vk($access_token,$album_id='84177783_209446394',$group_id = '84177783'){
        $this->access_token = $access_token;
        $this->album_id = $album_id;
        $this->$group_id = $group_id;
        $res = file_get_contents("https://api.vk.com/method/photos.getUploadServer?album_id=".$this->album_id."&group_id = ".$this->group_id."&access_token=".$this->access_token); 
        $server_upload_uri = json_decode($res, true);
        return $this->server_upload_uri;

    }
   

}