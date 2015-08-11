
<?

// подклчение класса users
$save_user = new Users();
//require_once ('../core_parser/parser_vse_pokormanu.php');
$vk = new Controller_VK();

//var_dump($save_user);
static $VK_APP_ID = "4976017";
static $VK_SECRET_CODE = "7SUe2GWNds2mXPRWAuRN";



  $code = $_GET['code'];
  //переменные в сессии
  if (!isset($_SESSION['access_token'])){
 
   $data = $vk->get_data_acsess_token($VK_APP_ID,$VK_SECRET_CODE,$code);  
  $_SESSION['access_token'] =  $data['access_token'] ;
  $_SESSION['vk_id'] = $data['user_id']  ; 
  }
  $access_token =  $_SESSION['access_token'];
  $vk_id =  $_SESSION['vk_id'];
  
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

  //}else{
    //echo$_SESSION['access_token']."<br>";
    $_SESSION['vk_id']."<br>";
    //echo $vk_id;
    $access_token=$_SESSION['access_token'];
    $res = $save_user->save($data);
    print_r($res); 
    

  //}


// обращаемся к ВК Api, получаем имя, фамилию и ID пользователя вконтакте
// метод users.get
// 
// 
  
  // echo $user_info['first_name']." ".$user_info['last_name']."</br>";
    


 
 
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
  //$vk->save_photo($captions,$post,$access_token);
  $file_list = $vk->get_list_pareser();
  foreach ($file_list as $key => $value) {
    echo "<a href='{$value}'>$value</a>";
  }
 
  
   

  
               
  
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


?>





