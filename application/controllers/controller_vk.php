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
    public $captions;

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
    //Сохранение фото в альбом
    function save_photo($captions,$files,$access_token,$album_id=216306417,$group_id=84177783){
        //Получение url сервера
        $res1 = file_get_contents("https://api.vk.com/method/photos.getUploadServer?album_id=$album_id&group_id=$group_id&access_token={$access_token}"); 
        $server_upload_uri = json_decode($res1, true);

        $server = $server_upload_uri['response']['upload_url'];

        //отравка post на сервер c файлами
        $ch = curl_init($server);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($ch, CURLOPT_POSTFIELDS,  $files);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data; charset=UTF-8'));
        $json = json_decode(curl_exec($ch));
        //print_r($json);
        curl_close($ch);
        $hash = $json->hash;
        $photos_list = $json ->photos_list;
        //print_r($photos_list);
        $server_to_save = $json->server;
        //echo $server_to_save."<br>" ;   
        $res2 = file_get_contents("https://api.vk.com/method/photos.save?album_id=$album_id&group_id=$group_id&server={$server_to_save}&photos_list={$photos_list}&hash={$hash}&access_token={$access_token}"); 
        $rezult = json_decode($res2, true);
       //  echo "<pre>";
       // // print_r($rezult);
       //  echo "</pre>";
        $success_save = $rezult['response'];
        $photos_id;
        if(is_array($success_save)){
          foreach ($success_save as $key => $value) {
            $photos_id[] = $value['pid']."<br>";
          }
        }
        
        //Сливка двух массивоов с id фото и описания
        //Добавлеоние а альбом
        $photo_array = array_combine($photos_id, $captions);  
        foreach ($photo_array as $photo_id => $value) {       
            $photo_id = (int)$photo_id;
            $data = file_get_contents("https://api.vk.com/method/photos.edit?photo_id={$photo_id}&owner_id=-$group_id&caption={$value}&access_token={$access_token}");
            print_r($data);
         //print_r($data);
        }
        
    }
    function get_list_pareser(){
        $dir = scandir("application/list_parser");
        //print_r($entries); 
        $filelist = [];
        foreach($dir as $files) {  
            if ($files != '.' && $files != '..'){      
                $filelist[] = $files;
            }
         
        }
        return $filelist;
    }

    
   

}