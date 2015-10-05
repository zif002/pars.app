



<?
$vk = new Controller_VK();
$access_token = $_SESSION['access_token'];
require_once('application/core/core_parser.php');


function getBigImage($url,$i=1){
	//echo $url."<br>";
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
	$url = $url->id;
	echo $url."<br>";
	// находим фото


	if( count($data->find('#zoom1')) ){
		//$img = $data->find('#wrap img');
		//echo $img->src.'<br>';
		 foreach($data->find('#zoom1') as $img){		
			//echo $img->href;
		 	if( !preg_match('#^http://#',$img->href) )$img->href = 'http://sankt_peterburg.vsepokarmanu.ru'.$img->href;
			file_put_contents( 'application/views/image/'.$i++.'.jpg', file_get_contents($img->href));
		 	 // сохраняем в файл			
		 }
		// if( !preg_match('#^http://#',$img->src) )$img->src = 'http://sankt_peterburg.vsepokarmanu.ru'.$img->src;
		
	}
	//echo $url."<br>";
	//Вывод названия товара
	if( count($data->find('h1')) ){
		$product_title = $data->find('h1',0);
		$product_title = $product_title->plaintext;
		echo  trim($product_title)."<br>";	
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
	 	$aritcle;
	 	foreach ($data->find('#artnumber') as $article){
	 		$article = $article->plaintext;
	 		echo  "Артикул :".trim($article)."<br>";
	 	}
	 }else{
	
	}


	 if(  count($data->find('#priceItem'))){	 	
	 	$price2 = $data->find('#priceItem',0);
	 	// foreach($data->find('span#our_price_display_red') as $price2) {	
	 		
	 	// }
	 	$price2 = $price2->plaintext;
	 	echo  'Цена '.trim($price2)."<br>";	 
	 }else{			
	 
	 }

	  //вывод состав
	 
	 if( count($data->find('#ls0')) ){
	 	$size1=array();
	 	foreach($data->find('#ls0 ul li') as $size) {
	 		$size1[] = $size->plaintext;
	  		
	  	}
	  	$size2 = implode(" ",$size1);		
		//echo $size2."<br>";
		
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
	
	$temp_captions = "$product_title\n\r$url\n\rАртикул: $article\n\rЦена: $price2\n\rРазмер: $size2\n\r$str1\n\r$str2";
	//print_r($temp_captions);
	
	
	
	$data->clear();// подчищаем за собой
	unset($data);
	return $temp_captions;

}

function getYandexImages($url,$findpages = true,$i=1,$n=15){

	
	
	// загружаем данный URL
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36");
	$data = curl_exec($ch); 
	curl_close($ch);  
	

	$data = str_get_html($data);
	$f=1;
	// очищаем страницу от лишних данных, это не обязательно, но когда HTML сильно захламлен бывает удобно почистить его, для дальнейшего анализа
		foreach($data->find('script,link,comment') as $tmp)$tmp->outertext = '';

	 // echo "<pre>";
	 // print_r($price2);
	 // echo "</pre>";
	//echo count($data->find('.box a.item'))."<br>";
	//находим URL страниц только для первого вызова функции

	if( $findpages and count($data->find('.box a.item'))){
	
		$captions;
		foreach($data->find('.box a.item') as $a){	
			//echo $n."<br>";
			 //echo count($a).'<br>';
			// довольно распространенный случай - локальный URL. Поэтому иногда url надо дополнять до полного
			if( !preg_match('#^http://#',$a->href) )$a->href = 'http://sankt_peterburg.vsepokarmanu.ru'.$a->href;
			// и еще дна тонкость, &amp; надо заменять на &
			$a->href = str_replace('&amp;','&',$a->href);
			// echo $a->href.'<br>';
			// вызываем функцию для каждой страницы
		
			getYandexImages($a->href,false);
			
			$temp_captions = getBigImage($a->href,$i);
			//echo $temp_captions."<br>";
			$captions['file'.$f++] = $temp_captions;
			


			

			if($i++>=$n) return $captions; // завершаем работу если скачали достаточно фотографий
			// этакий progressbar, будет показывать сколько фотографий уже загружено
			//echo '<script>document.getElementById("counter").innerHTML = "Загружено: '.$i.' из '.$n.' фото";</script>';
		
		}

		

	}


	
	

	$data->clear();// подчищаем за собой
	unset($data);
	//print_r($captions);
	return $captions;
	
}
// очищение папки 
// echo "<pre>";
// print_r($captions);
// echo "<pre>";
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

// $files = get_photos();
// //print_r($files);
// //print_r($captions);


// поисковый URL


if (isset($_POST['link'])) {

	$i=1;
	$n=200;
	$url =	$_POST['link'] ;
	clear_dir('application/views/image/');
	$captions = getYandexImages($url);

}









