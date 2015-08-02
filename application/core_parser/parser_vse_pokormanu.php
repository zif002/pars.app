<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
	p{
		margin:0;
	}
	</style>
</head>
<body>



<div id="counter">0</div><!--progressbar-->
<?
set_time_limit(0); // это для того чтобы скрипт не отвалился через 30 секунд, если вддруг попадется медленный сайт донор
require_once '../lib/simple_html_dom.php';
require_once '../lib/short_url.php';



function getBigImage($url,$i){
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
	$data = curl_exec($ch); 
	curl_close($ch);
	if(trim($data)=='')return false; // бывает что сайт недоступен, его фото мы не грузим
	$data = str_get_html($data);
	// echo "<pre>";
	// print_r($data);
	// echo "</pre>";
	echo "Товар № ".$i."<br>";
	$url = shortUrl($url);
	echo $url->id."<br>";
	// находим фото


	if( count($data->find('#zoom1')) ){
		//$img = $data->find('#wrap img');
		//echo $img->src.'<br>';
		 foreach($data->find('#zoom1') as $img){		
			//echo $img->href;
		 	if( !preg_match('#^http://#',$img->href) )$img->href = 'http://sankt_peterburg.vsepokarmanu.ru'.$img->href;
			file_put_contents( 'data2/'.$i++.'.jpg', file_get_contents($img->href));
		 	 // сохраняем в файл			
		 }
		// if( !preg_match('#^http://#',$img->src) )$img->src = 'http://sankt_peterburg.vsepokarmanu.ru'.$img->src;
		
	}
	//echo $url."<br>";
	//Вывод названия товара
	if( count($data->find('h1')) ){
		$product_title = $data->find('h1',0);
		echo  $product_title->plaintext."<br>";	
		// foreach ($data->find('.product-page-content h1') as $product_title) {
		// 	// echo "<pre>";
		// 	// print_r($product_title);
		// 	// echo "</pre>";
		// 	echo  $product_title->plaintext."<br>";	
		// }
		
			
	 }else{
	 
	 }
	//Вывод ,бренда
	 if( count($data->find('#artnumber')) ){
	 	foreach ($data->find('#artnumber') as $article){	 	
	 		echo  "Артикул :".$article->plaintext."<br>";
	 	}
	 }else{
	
	}


	 if(  count($data->find('#priceItem'))){	 	
	 	$price2 = $data->find('#priceItem',0);
	 	// foreach($data->find('span#our_price_display_red') as $price2) {	
	 		
	 	// }
	 	echo  'Цена'.$price2->plaintext."<br>";	 
	 }else{			
	 
	 }

	  //вывод состав
	 
	 if( count($data->find('#ls0')) ){
	 	foreach($data->find('#ls0 ul li') as $size) {	  	
	  		echo $size->plaintext." ";
	  	}	
		echo '<br>';
		
	}else{
	
	}
	


	 //   Вывод описания
	  if(count($data->find('.product-description'))){
	  	$str="";
	  	foreach($data->find('.product-description') as $descript) {	  	
	  		$str1 = $descript->plaintext;
	  		
	  		//echo $descript; 
	  	}	  	
	  	$str1;
	  	$pos = strpos($str1,"Состав");
	  	$str2 = substr ( $str1, $pos ,-1 );
	  	echo $str2;
	  	echo $str1;
	  	//print_r($str);
	 }else{			
	 	
	 }
	 //Конец 
	 echo "<br><br>";

	
	$data->clear();// подчищаем за собой
	unset($data);
}

function getYandexImages($url,$findpages = true){
	global $i,$n;
	
	// загружаем данный URL
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36");
	$data = curl_exec($ch); 
	curl_close($ch);  
	

	$data = str_get_html($data);

	// очищаем страницу от лишних данных, это не обязательно, но когда HTML сильно захламлен бывает удобно почистить его, для дальнейшего анализа
foreach($data->find('script,link,comment') as $tmp)$tmp->outertext = '';

	 // echo "<pre>";
	 // print_r($price2);
	 // echo "</pre>";
	//находим URL страниц только для первого вызова функции
	if( $findpages and count($data->find('.box a.item'))){
		foreach($data->find('.box a.item') as $a){	
			 // echo $a->href.'<br>';
			// довольно распространенный случай - локальный URL. Поэтому иногда url надо дополнять до полного
			if( !preg_match('#^http://#',$a->href) )$a->href = 'http://sankt_peterburg.vsepokarmanu.ru'.$a->href;
			// и еще дна тонкость, &amp; надо заменять на &
			$a->href = str_replace('&amp;','&',$a->href);
			 //echo $a->href.'<br>';
			// вызываем функцию для каждой страницы
		
			getYandexImages($a->href,false);
			getBigImage($a->href,$i);
			if($i++>=$n)exit; // завершаем работу если скачали достаточно фотографий
			// этакий progressbar, будет показывать сколько фотографий уже загружено
			echo '<script>document.getElementById("counter").innerHTML = "Загружено: '.$i.' из '.$n.' фото";</script>';
			flush();
		}
	}	
	
	// находим все изображения на странице, а точнее ссылки на них
	// if(count($data->find('a.product-title'))){
	// 	foreach($data->find('a.product-title') as $a){
	// 		//echo $a->href."<br>";
	// 		if( !preg_match('#^http://#',$a->href) )$a->href = 'http://www.kupivsem.ru'.$a->href;
	// 		$a->href = str_replace('&amp;','&',$a->href);
	// 		//echo $a->href."<br>";		
	// 		getYandexImages($a->href,false);
			
	// 		// getPrice($a->href);
			
		
	// 	}
	// }
	$data->clear();// подчищаем за собой
	unset($data);
}
// очищение папки 

function myscandir($dir)
{
    $list = scandir($dir);
    unset($list[0],$list[1]);
    return array_values($list);
}

// функция очищения папки
function clear_dir($dir)
{
    $list = myscandir($dir);
    
    foreach ($list as $file)
    {
        if (is_dir($dir.$file))
        {
            clear_dir($dir.$file.'/');
            rmdir($dir.$file);
        }
        else
        {
            unlink($dir.$file);
        }
    }
}


// поисковый URL

$i = 1;
$n = 200;
if (isset($_POST['link'])) {

	$url =	$_POST['link'] ;
	clear_dir('data2/');
	getYandexImages($url);
}



?>
</body>
</html>
