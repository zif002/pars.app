
<?
$dbh = new Database();
$base = $dbh->getDb();
$ip=$_SERVER['REMOTE_ADDR'];
echo $ip;
$VK_APP_ID = "4976017";
$VK_SECRET_CODE = "7SUe2GWNds2mXPRWAuRN";

if(!empty($_GET['code'])) {
 
  $vk_grand_url = "https://api.vk.com/oauth/access_token?client_id=".$VK_APP_ID."&client_secret=".$VK_SECRET_CODE."&code=".$_GET['code']."&redirect_uri=http://pars.app/vk";
 
 // отправляем запрос на получения access token
  $resp = file_get_contents($vk_grand_url);
  $data = json_decode($resp, true);
  $vk_access_token = $data['access_token'];
  $vk_uid =  $data['user_id'];
  echo "$vk_uid<br>,$vk_access_token<br>,$vk_access_token<br>";
  echo "<pre>";
  print_r($data);
  echo "</pre>";

// обращаемся к ВК Api, получаем имя, фамилию и ID пользователя вконтакте
// метод users.get
  $res = file_get_contents("https://api.vk.com/method/users.get?uids=".$vk_uid."&access_token=".$vk_access_token."&fields=uid,first_name,last_name,nickname,photo"); 
  $data = json_decode($res, true);
  $user_info = $data['response'][0];
  $name = $user_info['first_name'];
  $last_name = $user_info['last_name'];
  print_r($user_info);
  echo $user_info['first_name']." ".$user_info['last_name']."</br>";
 
  $sql="INSERT INTO users(first_name, last_name, id_vk) VALUES (
		:f_name,
		:l_name,
		:id_vk
  	)";
	$stmt = $base->prepare($sql);
	$stmt = bindParam(':f_name',$user_info['first_name'] , PDO::PARAM_STR);
  	$stmt = bindParam(':l_name',$user_info['last_name'] , PDO::PARAM_STR);
  	$stmt = bindParam(':vk_id',$user_info['uid'] , PDO::PARAM_STR);
  	$stmt->execute();


//   echo "‹img src='".$user_info['photo']."' border='0' /›";
  	//$photos = file_get_contents("https://api.vk.com/method/photos.getAlbums?uids=".$vk_uid."&access_token=".$vk_access_token."&owner_id=-84177783,album_ids");
  	$photos = file_get_contents("https://api.vk.com/method/photos.getAlbums?uids=".$vk_uid."&owner_id=-84177783,album_ids"); 
	$data = json_decode($photos, true); 
	//$data = json_decode($photos, true);
	echo "<select>";
	foreach ($data as $key) {
		foreach ($key as $key1 => $value) {
			echo "<option id={$value['aid']}>{$value['title']}</option>";


			
		}
	}
	echo "</select>";	
	// echo print_r($data);
 }
 echo "<h1>djfskljsa</h1>";
