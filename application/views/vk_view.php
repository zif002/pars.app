<?
  // подклчение класса users and vk
  $save_user = new Users();
  $vk = new Controller_VK();
  static $VK_APP_ID = "4976017";
  static $VK_SECRET_CODE = "7SUe2GWNds2mXPRWAuRN";

  print_r($_POST);
 if ($_GET['type']) {
    $i=1;
    $file_parser = $_GET['type'];
    switch ($file_parser) {      
      case 1:
        require_once('application/list_parser/parser_vse_pokormanu.php');        
      break;
      case 2:
        require_once('application/list_parser/parser_flisco.php');
      break;
    }
  }  
  //переменные в сессии
  if (!isset($_SESSION['access_token'])){
  $code = $_GET['code'];
  $data = $vk->get_data_acsess_token($VK_APP_ID,$VK_SECRET_CODE,$code);  
  $_SESSION['access_token'] =  $data['access_token'] ;
  $_SESSION['vk_id'] = $data['user_id']  ; 
  }
  $access_token =  $_SESSION['access_token'];
  $vk_id =  $_SESSION['vk_id'];
  //echo $vk_id;
   // echo  $_SESSION['access_token'];
  //$user =  $vk->get_user($vk_id,$access_token);  
   // echo "<pre>";
  // print_r($data);
  // echo "</pre>";
  $first_name = $user['first_name'];
  $last_name  = $user['last_name']; 
  $ip=$_SERVER['REMOTE_ADDR'];
    $data  = array(
      'first_name'  => $first_name,
      'last_name'   => $last_name,
      'vk_id'       => $vk_id,
      'ip'          => $ip,
      'access_token'=> $access_token
    );               
  
//выборка альбомов группы

$groups_list = $vk->get_groups_list($vk_id, $access_token);

$albums_list = $vk->get_albums_list($vk_id);
$files = $vk->get_photos();

// echo "<pre>";
// print_r($files);
// echo "</pre>";
// echo "<pre>";
// print_r($captions);
// echo "</pre>";

//print_r($captions);
// if(isset($captions)){
//   $vk->save_photo($files,$captions,$access_token);
//   unset($captions);
// }

?>


<div class="row">
  <div class="col-md-12">
  <h1>Привет <?=$first_name." ".$last_name?></h1>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Brand</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
            <li><a href="#">Link</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Выбор сайта <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="vk?type=1">Все по карману</a></li>
                <li><a href="vk?type=2">Детская одежда flisco</a></li>               
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-left" action="vse"   role="search">
            <select class='selectpicker'>
              <?foreach ($groups_list['response'] as $key) {
                  if ($key['is_admin'] == 1 )
                    echo "<option name='groups_id' value='{$key['gid']}'>{$key['name']}</option>";       
                }
              ?>
            </select>
            <select class='selectpicker'>
            <?foreach ($albums_list as $key) {
              foreach ($key as $key1 => $value) {
                echo "<option name='albums_id' value='{$value['aid']}' >{$value['title']}</option>";               
              }
            }
            ?>
            </select>
            <button type="submit" class="btn btn-default">Выбрать</button>
          </form>
         
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    
  </div>
</div>


  