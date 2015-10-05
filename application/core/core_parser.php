<?
//echo $_COOKIE['access_token'];
$groups_list = $vk->get_groups_list($vk_id, $_COOKIE['access_token']);

$albums_list = $vk->get_albums_list($vk_id);

?>

<div class="col-lg-12">

    <div class="input-group form">

    <form action="vk?type=<?=$type_parser?>" class='form-horizontal' method='post' >
             <div class="form-group">
             <label for="inputEmail3" class="col-md-12 control-label">
            <select class='selectpicker' name='groups_id'>
              <?foreach ($groups_list['response'] as $key) {
                  if ($key['is_admin'] == 1 )
                    echo "<option  selected='selected' value='{$key['gid']}'>{$key['name']}</option>";       
                }
              ?>
            </select>
            <select class='selectpicker' name="albums_id">
            <?foreach ($albums_list as $key) {
              foreach ($key as $key1 => $value) {
                echo "<option  value='{$value['aid']}' >{$value['title']}</option>";               
              }
            }
            ?>
            </select>
            </label>
            </div>
     <div class="form-group">
      <label for="inputEmail3" class="col-md-12 control-label">
      <input type="text" name="link" class="form-control" placeholder="Вставте ссылку"> <button class="btn btn-default pull-left" type="submit">Выбрать</button>
      </label>
      </div>


    </div><!-- /input-group -->
    </form>
         <div class="cleafix"></div>
         <a class="navbar-brand btn btn-default btn-info btn-lg pull-left" href="vk">Назад</a>
  </div><!-- /.col-lg-6 -->




 


