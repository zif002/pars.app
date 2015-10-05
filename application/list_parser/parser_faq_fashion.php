<?
session_start();
//echo "Файл подключен<br>";
$vk = new Controller_VK();
$access_token = $_SESSION['access_token'];
require_once('application/core/core_parser.php');
//print_r($access_token);
function getBigImage($url1,$i=1){
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL, $url1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36");
	$data = curl_exec($ch); 
	curl_close($ch);  
	if(trim($data)=='')return false; // бывает что сайт недоступен, его фото мы не грузим
	$data = str_get_html($data);
	// echo "<pre>";
	// print_r($data);
	// echo "</pre>";
	

	if( count($data->find('a.MagicZoomPlus')) ){
		//$img = $data->find('a.fancy-img');
		//echo $img->href;
		$img = $data->find('a.MagicZoomPlus',0);
		$img = $img->href;
		//echo $img;
		//echo "<img src='$img' width='130' height='130'>";
		
		//echo $img;
		//print_r($img);		
		//echo $img->href;	
		 //if( !preg_match('#^http://#',$img->src) )$img->src = 'http://xn--h1aebonp.xn--p1ai/'.$img->src;	
	 //  	echo $img;	
	  	$ch = curl_init();
	  	curl_setopt($ch, CURLOPT_HEADER, false);  
		curl_setopt($ch, CURLOPT_URL, $img); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36");
		$imgExt = curl_exec($ch);
		//print_r($imgExt);
		curl_close($ch);  
		$r_photo = file_put_contents( 'application/views/image/'.$i++.'.jpg', $imgExt);
		//echo $r_photo."<br>";
	}
	//Если фото нет не грузим переменные
	if($r_photo){
		//echo "Товар № ".$i."<br>";
		$url = shortUrl($url1);
		$url = $url->id;
		//echo $url."<br>";
		//Вывод названия товара
		 if( count($data->find('h1')) ){
			$product_title1 = $data->find('h1',0);
				$product_title1 =  $product_title1->plaintext;
				$product_title = trim($product_title1);
		

			echo  $product_title."<br>";	
			
			}else{	 
		}
		//Вывод ,Артикул
		 if( count($data->find('.option')) ){
		 	$size_temp =  $data->find('.option',0);
		 	echo  "Размер: ";
		 	foreach ($size_temp->find('.checkbox label') as $size) {
		 		$size1[] = $size->plaintext;
		 		echo $size->plaintext;
		 	}
		 	$size2 = implode(" ",$size1);
		 	echo "<br>";
		 	
		 	
		 }else{
		
		}
		 
		//Вывод цены
		 if(  count($data->find('.price'))){	 		
		 	$price = $data->find('.price',0);
			$price = $price->plaintext;
			
		 	echo  htmlspecialchars($price)."<br>";
		 }else{			
		
		 }		
		 //   Вывод короткого описания
	  	if(count($data->find('.tablecolor'))){
	  		$table = $data->find('.tablecolor',0);
	  		foreach ($table->find('tr') as $description) {
	  			$descr[] = str_replace('&quot;','',$description->plaintext);
	  			echo $description->plaintext."<br>";
	  		}
	  		$description = implode("\n\r",$descr);
	  		//$description = trim($description);
	  		//echo $description."<br>";
		}else{			
		 	
		}
	}
	 //Конец 
	echo "<br><br>";
	$temp_captions = "$url\n\r$product_title1\n\rЦена: $price\n\rРазмер: $size2\n\r$description\n\r";

	
	print_r($temp_captions);
	//var_dump($temp_captions);
	$data->clear();// подчищаем за собой
	unset($data);
	return $temp_captions;
}

function getYandexImages($url,$findpages = true,$i=1,$n=3){

	$f=1;
	$captions = array();
	//echo $url;
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
	//echo $data;
	 // echo "<pre>";
	 // print_r($price2);
	 // echo "</pre>";
	//находим URL страниц только для первого вызова функции
	
	if( $findpages and count($data->find('h3.name a'))){
		
		foreach($data->find('h3.name a') as $a){	
		//$a->href.'<br>';
			// довольно распространенный случай - локальный URL. Поэтому иногда url надо дополнять до полного
			if( !preg_match('#^http://#',$a->href) )$a->href = 'http://faq-fashion.ru/'.$a->href;
			// и еще дна тонкость, &amp; надо заменять на &
			$a->href = str_replace('&amp;','&',$a->href);
			//echo $a->href.'<br>';
			// вызываем функцию для каждой страницы
		
			getYandexImages($a->href,false);
			$temp_captions = getBigImage($a->href,$i);
			$captions['file'.$f++] = $temp_captions;
			
			
			if($i++>=$n) return $captions; // завершаем работу если скачали достаточно фотографий
			// этакий progressbar, будет показывать сколько фотографий уже загружено
			echo '<script>document.getElementById("counter").innerHTML = "Загружено: '.$i.' из '.$n.' фото";</script>';
			flush();

		}
	

	}
	
	$data->clear();// подчищаем за собой
	unset($data);
	return $captions;
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

if (isset($_POST['link'])) {


	$url =	$_POST['link'] ;
	clear_dir('application/views/image/');
	$captions = getYandexImages($url);


}
//var_dump($_POST);
if(isset($POST['albums_id'])){
	echo true;
    echo $album_id = $POST['albums_id'];
    echo $group_id = $_POST['groups_id'];
}
//print_r($_POST);

	


?>

