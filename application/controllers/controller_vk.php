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

    function __construct(){
        $this->model = new Model_VK();
        
        $this->view = new View();
    }




    function action_index(){
        $list_parser = $this->model->getList();	
      
        $this->view->generate('vk_view.php', 'main_template.php',$list_parser);
    }
    //полученние $accses_token
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
    //формирование массива фотографий
    function get_photos($dir){
    //echo "!!!1!!";
      $photo = scandir('application/views/image/');

      //print_r($photo);
 
      $photo = array_filter(scandir('application/views/image/'), function($photo) {
        return !is_dir('application/views/image/'.$photo);
        });
    if(!empty($photo)){
    
        foreach($photo as $photos){
              
              $photos_arr[$i++] = $photos; 
        }

        natsort($photos_arr);
         //print_r($photos_arr);
         $j=1;
        foreach ($photos_arr as $key => $value) {
           $photos_ids['file'.$j++] = "@".$dir."\image\\".$value;
        }
        return $photos_ids;
      }else{
        return ;
      }
      // print_r($photos_arr); 
    }
    //получение списка альбомов
    function get_albums_list($vk_id,$id_group ="NULL"){
        $albums_list = file_get_contents("https://api.vk.com/method/photos.getAlbums?uids=".$vk_id."&owner_id=-84177783,album_ids"); 
        $albums_list = json_decode($albums_list, true); 
        return $albums_list;
    }
    //выбор групп
    function get_groups_list($vk_id = 1, $access_token){

        $groups_list_users = file_get_contents("https://api.vk.com/method/groups.get?user_id=".$vk_id."&extended=1&count=100&access_token={$access_token}"); 
     
        $groups_list = json_decode($groups_list_users, true);

        return $groups_list;
    }
    function get_user($vk_id,$access_token){
        echo $access_token;
        //echo $vk_id."<br>";
        $res = file_get_contents("https://api.vk.com/method/users.get?uids=".$vk_id."&access_token=".$access_token."&fields=uid,first_name,last_name,nickname,photo"); 

        $data = json_decode($res, true); 

        $user = $data['response'][0];
        //print_r($user);
        return $user;
    }
    //Сохранение фото в альбом
    function save_photo($files,$captions,$access_token,$album_id,$group_id){
        //print_r($album_id);
        //print_r($group_id);
        //echo "!!true!!";
        // echo "<pre>";
        // echo print_r($captions);
        // echo "</pre>";
        //   echo "<pre>";
        // echo print_r($files);
        // echo "</pre>";
        //Получение url сервера
        //print_r($captions);
        //ключи у $captions and $files должны совпадаит иначе 
        //описаие не загрузится
        $COUNT = 0;
        $res1 = file_get_contents("https://api.vk.com/method/photos.getUploadServer?album_id=$album_id&group_id=$group_id&access_token={$access_token}");         
        $server_upload_uri = json_decode($res1, true);
        $server = $server_upload_uri['response']['upload_url'];

            foreach ($files as  $key => $value) { 
             
                    $post['file1'] = $value;
                    // echo "<pre>";
                    // echo print_r($post);
                    // echo "</pre>";
                    //print_r($captions[$key]);
                    //echo $captions[$value]."<br>";                
                    // echo "<pre>";
                    // echo print_r($res1);
                    // echo "</pre>";
                    
                    // echo "<pre>";
                    // echo print_r($server);
                    // echo "</pre>";
                    //отравка post на сервер c файлами
                    $ch = curl_init($server);       
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
                   // print_r($files);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,  $post);
                    //print_r($files);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data; charset=UTF-8'));
                    //unset($post);
                    $json = json_decode(curl_exec($ch));     
                    //   echo "<pre>";
                    // echo print_r($json);
                    // echo "</pre>";
                    curl_close($ch);
                    $hash = $json->hash;
                    //print_r($hash);
                    $photos_list = $json ->photos_list;
                    //print_r($photos_list);
                    $server_to_save = $json->server;
                    
                    // echo $captions[$key]."<br>"; 
                    //echo $server_to_save."<br>" ;   
                    $cg = curl_init("https://api.vk.com/method/photos.save");       
                    curl_setopt($cg, CURLOPT_POST, true);
                    curl_setopt($cg, CURLOPT_RETURNTRANSFER, 1);
                    //echo $captions[$key]."<br>";
                    curl_setopt($cg, CURLOPT_POSTFIELDS, "album_id=$album_id&group_id=$group_id&server={$server_to_save}&photos_list={$photos_list}&hash={$hash}&caption=$captions[$key]&access_token={$access_token}");  
                    $res_save_photo = _curl_exec($cg);
                    curl_close($cg);     
                    $rezult = json_decode($res_save_photo, true);
                    //  echo "<pre>";
                    // print_r($rezult);
                    //  echo "</pre>";
                    $success_save = $rezult['response'];
                    //print_r($success_save);
                    if(is_array($success_save)){         
                        $photos_id = $success_save['pid'];
                    }
          
        }
      
        //return "!!!!!!!suscess!!!!!!";
        unset($captions);
        unset($files);
        //на всякий случай загрузка до 5 фото
        // $photos_id;
        //print_r($photos_id);
        //print_r($photos_id);
        //Сливка двух массивоов с id фото и описания
        //Добавлеоние а альбом
        // if(isset($captions) && isset($photos_id)){
        //     $photo_array = array_combine($photos_id, $captions);  
        //        unset($photos_id);
        //     //print_r($photo_array);
        //     foreach ($photo_array as $photo_id => $value) {

        //         $value = (string)$value;   
        //         $photo_id = (int)$photo_id;
                
        //         //$data = file_get_contents("https://api.vk.com/method/photos.edit?photo_id={$photo_id}&owner_id=-$group_id&caption={$value}&access_token={$access_token}");
        //         $ch = curl_init("https://api.vk.com/method/photos.edit");       
        //         curl_setopt($ch, CURLOPT_POST, true);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        //        // print_r($files);
        //         curl_setopt($ch, CURLOPT_POSTFIELDS, "photo_id={$photo_id}&owner_id=-$group_id&caption={$value}&access_token={$access_token}");
        //         //print_r($files);
        //         $out = curl_exec($ch);
        //         print_r($out);
        //      //print_r($data);
        //     }
        // }else{
        //     echo "не приходят $captions and $photos_id";
        // }
        
        
    }
}
