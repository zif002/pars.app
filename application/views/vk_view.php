<?

  // подклчение класса users and vk

  $vk = new Controller_VK();
  //$user_obj = new Users();

  static $VK_APP_ID = "4976017";
  static $VK_SECRET_CODE = "7SUe2GWNds2mXPRWAuRN";
  if ($_GET['type']) {
    $type_parser = $_GET['type'];
    foreach ($list_parser as $parser) {
      if($_GET['type'] == $parser['case']){
        echo "<h1>{$parser['name']}</h1>";
        require_once($parser['path']);

      }

    }
   }


  //переменные в сессии
  if (!isset($_COOKIE['access_token'])){
    $code = $_GET['code'];
    $data = $vk->get_data_acsess_token($VK_APP_ID,$VK_SECRET_CODE,$code);  
    //print_r($data);
    setcookie("access_token", $data['access_token'],time()+86400);
    setcookie("vk_id",$data['user_id'],time()+86400); 
     $access_token = $_COOKIE['access_token'];
    $user =  $vk->get_user($data['user_id'] , $access_token);

    // echo "<pre>";
    // print_r($user);
    // echo "<pre>";

    $first_name = $user['first_name'];
    $last_name  = $user['last_name'];
    //print_r($user);
    $access_token_time = time();
    $ip=$_SERVER['REMOTE_ADDR'];
      $data_user  = array(
        'first_name'          => $first_name,
        'last_name'           => $last_name,
        'vk_id'               => $data['user_id'],
        'ip'                  => $ip,
        'access_token'        => $data['access_token']
      );
      print_r($data_user);
    //var_dump($user_obj);
    //$users = $user_obj->save($data_user);
    $_SESSION['user'] = $data_user;
    print_r($dara_user);
    //выборка альбомов группы
    // $res_users = $vk->model->save($data_user);
  }else{

  }
  $first_name = $_SESSION['user']['first_name'];
  $last_name = $_SESSION['user']['last_name'];
  //print_r($_SESSION);
  
  //echo $access_token;
  $vk_id =  $_SESSION['user']['vk_id'];
  //echo $vk_id;
  // echo  $_SESSION['access_token'];

  $dir = dirname(__FILE__);
  $files = $vk->get_photos($dir);

  // echo "<pre>";
  // print_r($files);
  // echo "</pre>";
  // echo "<pre>";
  // print_r($captions);
  // echo "</pre>";
  //var_dump($access_token);
  //print_r($group_id);
$album_id = $_POST['albums_id'];
$group_id = $_POST['groups_id'];
//print_r($captions);
 if(isset($captions)){
  //echo "!!OK!!!";
$vk->save_photo($files,$captions,$_COOKIE['access_token'],$album_id,$group_id);
   unset($captions);
 }

?>


<div class="row">
  <div class="col-md-12">
  <?if($_GET['type']){?>
  <h1>Привет <?=$first_name." ".$last_name?></h1>
  <?}else{?>
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
          
        </div>
        <a class="navbar-brand " href="vk">Назад</a>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
            <li><a href="#">Link</a></li>        
          </ul>        
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    <div class="row">
     <?  
        foreach ($data as $parser) {?>
              <div class="col-md-6"><a href="vk?type=<?=$parser['case']?>" class="btn btn-primary btn-block btn-lg"><?=$parser['case'].".&nbsp;".$parser['name']?></a>
                  <table class="table table-bordered table table-hover">
                    <tr >
                      <td class="col-md-6">Ссылка</td>
                      <td class="col-md-6"><a href="<?=$parser['link']?>" target="_blank"><?=$parser['link']?></a></td>
                    </tr>
                    <tr>
                      <td class="col-md-6">Минималка</td>
                       <td class="col-md-6"><?=$parser['min']?></td>
                    </tr>
                    <tr>
                    <td class="col-md-6">Описание</td>
                    <td class="col-md-6"><?=$parser['title']?></td>
                    </tr>
                  </table>
              </div>
          <?}
      ?>        
    </div>
    <?}?>
  </div>
</div>


  