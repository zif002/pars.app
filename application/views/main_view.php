<?
	 foreach($data as $link)
    {
       $app_id = $link['index_url'];
    }
?>
<div class="col-md-6 col-md-offset-3"><h1>Добро пожаловать в Парсер для жены betta 0.0.0.1!</h1></div>
<div class="row">
	<div class="col-md-6"><a href="<? echo $app_id;?>"class="btn btn-success">Войти через VK</a></div>
</div>
