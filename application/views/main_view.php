
<?
	 foreach($data as $link)
    {
       $app_id = $link['index_url'];
    }
    $enter = new Controller_Main();
 	if (!empty($_POST['user_login']) && !empty($_POST['user_pass'])){
		$login = $_POST['user_login'];
		$pass  = $_POST['user_pass'];
		//echo $login." ".$pass;
		$get_user = $enter->auth($login,$pass);
	}
?>

<div class="row">
	<?if ($get_user){?>
	<div class="col-md-6"><a href="<?=$app_id;?>"class="btn btn-success">ОТКРЫТЬ ДОСТУП</a></div>
	<?}else{?>
	<div class="col-md-12"><h1>Добро пожаловать в Парсер для жены betta 0.0.0.1!</h1></div>
	<div class="col-md-12">
		<div class="row">
			<form class="form-horizontal" id="formEnter" action="main" method="post">
				<fieldset>
					<!-- Form Name -->
					<legend class="">Добро пожаловать</legend>

					<!-- Text input-->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="textinput">Введите Логин</label>  
					  <div class="col-md-4">
					  <input id="textinput" name="user_login" type="text" placeholder="Логин" class="form-control input-md">
					  </div>
					</div>

					<!-- Password input-->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="passwordinput">Введите пароль</label>
					  <div class="col-md-4">
					    <input id="passwordinput" name="user_pass" type="password" placeholder="Пароль" class="form-control input-md">
					  </div>
					</div>

					<!-- Button (Double) -->
					<div class="form-group">
					  <div class="col-md-4"></div>
					  <div class="col-md-8">
					    <button id="admin_enter" name="button1id" class="btn btn-success ">ВХОД</button>
					     <button id="admin_enter" name="button1id" class="btn btn-success ">ЗАРЕГЕСТРИРОВАТЬСЯ</button>		   
					  </div>
					</div>
				</fieldset>
			</form>

		</div>
		</div>
	<?}?>

</div>
