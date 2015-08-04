
<?

// подклчение класса users
$save_user = new Users();
//require_once ('../core_parser/parser_vse_pokormanu.php');
$vk = new Controller_VK();

//var_dump($save_user);
static $VK_APP_ID = "4976017";
static $VK_SECRET_CODE = "7SUe2GWNds2mXPRWAuRN";


if(!empty($_GET['code'])) {
  $code = $_GET['code'];
  //переменные в сессии
  if (!isset($_SESSION['access_token']))
  {
    $data = $vk->get_data_acsess_token($VK_APP_ID,$VK_SECRET_CODE,$code); 
  
  $access_token =  $data['access_token'] ;
  $vk_id = $data['user_id']  ; 
    $_SESSION['access_token'] = $access_token;
    $_SESSION['vk_id'] = $vk_id;
   // echo  $_SESSION['access_token'];
    $res = file_get_contents("https://api.vk.com/method/users.get?uids=".$vk_id."&access_token=".$access_token."&fields=uid,first_name,last_name,nickname,photo"); 
    $data = json_decode($res, true);
   // echo "<pre>";
  // print_r($data);
  // echo "</pre>";
 
  $user_info = $data['response'][0]; 
   echo "<pre>";
  //echo true;
  echo "</pre>";
  $first_name = $user_info['first_name'];
  $last_name  = $user_info['last_name'];
  $vk_id = $user_info['uid'];
 // echo $vk_uid;
  $ip=$_SERVER['REMOTE_ADDR'];
    $data  = array(
      'first_name'  => $first_name,
      'last_name'   => $last_name,
      'vk_id'       => $vk_id,
      'ip'          => $ip,
      'access_token'=> $access_token
    );
    //$save_user->save($data); 
  }else{
    echo$_SESSION['access_token']."<br>";
    $_SESSION['vk_id']."<br>";
    echo $vk_id;
    $access_token=$_SESSION['access_token'];
    

  }


// обращаемся к ВК Api, получаем имя, фамилию и ID пользователя вконтакте
// метод users.get
// 
// 
  
  // echo $user_info['first_name']." ".$user_info['last_name']."</br>";
    


  function get_server($access_token,$album_id=216306417,$group_id=84177783){
      $res1 = file_get_contents("https://api.vk.com/method/photos.getUploadServer?album_id=$album_id&group_id=$group_id&access_token={$access_token}"); 
      $server_upload_uri = json_decode($res1, true);

      $server = $server_upload_uri['response']['upload_url'];
      return $server;
  }
  $server = get_server($access_token);
  //echo $server."<br>";
  $post = array( 
    "file1"=>"@".dirname(__FILE__)."/image/1.jpg",
    "file2"=>"@".dirname(__FILE__)."/image/2.jpg",
    "file3"=>"@".dirname(__FILE__)."/image/3.jpg",
  );
  $captions = array( 
    "desc1"=>"test1",
    "desc2"=>"test2",
    "desc3"=>"test3",
  );
  //print_r($post);
  function save_photo($server,$post,$access_token,$album_id=216306417,$group_id=84177783){
  
    $ch = curl_init($server);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $post);
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
    echo "<pre>";
   // print_r($rezult);
    echo "</pre>";
    $success_save = $rezult['response'];
    $photo_id;
    if(is_array($success_save)){
      foreach ($success_save as $key => $value) {
        $photo_id[] = $value['pid']."<br>";
      }
    }
    return $photo_id;
    
  }
  function photo_caption_edit($arr,$captions,$access_token,$group_id=84177783){
    $count = count($arr);
    foreach ($arr as $key => $value) {
      foreach ($captions as $key1 => $value1) {
        if(strlen($count) == 1)
  
        $value = (int)$value;
        $data = file_get_contents("https://api.vk.com/method/photos.edit?photo_id={$value}&owner_id=-$group_id&caption={$captions[$value1]}&access_token={$access_token}");
        //print_r($data);
     
            }
        
    }

   
    $success = json_decode($data, true);
    return $success;

  } 
  $photo_id = save_photo($server,$post,$access_token);
  print_r($photo_id);

  photo_caption_edit($photo_id,$captions,$access_token);
   

  
               
  
//выборка альбомов группы
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
$groups_list = get_groups_list($vk_id, $access_token);

$albums_list = get_albums_list($vk_id);
//print_r($groups_list);
 echo "<select>";
        foreach ($albums_list as $key) {
          foreach ($key as $key1 => $value) {
            echo "<option id={$value['aid']}>{$value['title']}</option>";


            
          }
        }
  echo "</select>";
  // echo "<pre>";
  // print_r($groups_list['response']);
  // echo "</pre>";
   echo "<select>";
        foreach ($groups_list['response'] as $key) {
          if ($key['is_admin'] == 1 )
            echo "<option id={$key['gid']}>{$key['name']}</option>";       
        }
  echo "</select>";

}
?>





