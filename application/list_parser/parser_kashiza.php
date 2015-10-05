<?
session_start();
$vk = new Controller_VK();
$access_token = $_SESSION['access_token'];
require_once('application/core/core_parser.php');
function getBigImage($url,$i=1){
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36");
	$data = curl_exec($ch); 
	curl_close($ch);  
	if(trim($data)=='')return false; // бывает что сайт недоступен, его фото мы не грузим
	$data = str_get_html($data);
	// echo "<pre>";
	// print_r($data);
	// echo "</pre>";


	if( count($data->find('.thumbnails li')) ){
		$img =  $data->find('.thumbnails li.span2 a',0);

			 	
		// }
		//echo $img;
		if( !preg_match('#^http://#',$img->href) )$img->href = 'http://kashiza.ru/'.$img->href;	
	  	//echo $img->href;
	  	$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $img->href); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36");
		$imgExt = curl_exec($ch); 
		curl_close($ch);  
		$flag = file_put_contents( 'application/views/image/'.$i++.'.jpg', $imgExt);
	}
	if($flag){
		echo "Товар № ".$i."<br>";
		$url = shortUrl($url);
		$url = $url->id;
		echo $url."<br>";
		//Вывод названия товара
		 if( count($data->find('h1')) ){
		 	$product_title = $data->find('h1',0);	
			$product_title = $product_title->plaintext;
			echo  $product_title."<br>";			
			}else{	 
		}
		 //Вывод цены
		 if(  count($data->find('.shk-price'))){	 		
		 	$price = $data->find('.shk-price',0);		 
		 	$round = ($price->plaintext/2);
		 	$price = ceil($round/10)*10;
		 	echo  "Цена: ".$price."<br>";
		 }else{			
		
		 }
		 if(count($data->find('.productSize'))){
		 	$size1 = array();
			foreach($data->find('.productSize') as $size) {	  	
					$size = $size->plaintext;
					$size1[] = $size;
					echo $size."<br>";
		
			}
			$size2 = implode(" ",$size1);
		}else{			
		 	
		}
		//Вывод ,наличие шт
		 if( count($data->find('.productCenter p')) ){
		 	$brand = $data->find('.productCenter p',0);
		 	$brand = $brand->plaintext;
		 	echo $brand."<br>";
		 }else{
		
		}
		 
		 //   Вывод описания
	  	if(count($data->find('.collorName'))){
	  		$color = $data->find('.collorName',0);
	  		$color = $color->plaintext;
	  		echo $color;
		}else{			
		 	
		}
	}
	 //Конец 
	echo "<br><br>";

	$data->clear();// подчищаем за собой
	unset($data);
	$temp_captions = "$product_title\n\r$url\n\rЦена: $price\n\rРазмер: $size2\n\r$brand\n\r$color";
	//print_r($temp_captions);
	return $temp_captions;

}

function getYandexImages($url,$findpages = true,$i=1,$n=200){

	$f=1;  
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
	if( $findpages and count($data->find('article .img a'))){
		foreach($data->find('article .img a') as $a){	
			//$a->href.'<br>';
			// довольно распространенный случай - локальный URL. Поэтому иногда url надо дополнять до полного
			if( !preg_match('#^http://#',$a->href) )$a->href = 'http://kashiza.ru/'.$a->href;
			// и еще дна тонкость, &amp; надо заменять на &
			$a->href = str_replace('&amp;','&',$a->href);
			//echo $a->href.'<br>';
			// вызываем функцию для каждой страницы
			//echo $i."!!!!!!!!!!!!!!<br>";
			getYandexImages($a->href,false);
			$temp_captions = getBigImage($a->href,$i);
			$captions['file'.$f++] = $temp_captions;
			if($i++>=$n) return $captions; // завершаем работу если скачали достаточно фотографий
			// этакий progressbar, будет показывать сколько фотографий уже загружено
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
if (isset($_POST['link'])) {


	$url =	$_POST['link'] ;
	clear_dir('application/views/image/');
	$captions = getYandexImages($url);
	//print_r($captions);


}
//var_dump($_POST);
if(isset($POST['albums_id'])){
	//echo true;
    $album_id = $POST['albums_id'];
    $group_id = $_POST['groups_id'];
}
?>
