<?
session_start();
//echo "Файл подключен<br>";
$vk = new Controller_VK();
$access_token = $_SESSION['access_token'];
require_once('application/core/core_parser.php');


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


	// находим фото


	if( count($data->find('#thumbs_list_frame li a.thickbox')) ){
		$img = $data->find('#thumbs_list_frame li a.thickbox',0);
		$img = $img->href;

		//echo $img;
	if( !preg_match('#^http://#',$img) )$img = 'http://sellleggings.ru'.$img;	
	  	//echo $img;	
	  	$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $img); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36");
		$imgExt = curl_exec($ch); 
		//print_r($imgExt);
		curl_close($ch);  
		$r_photo = file_put_contents( 'application/views/image/'.$i++.'.jpg', $imgExt);
		
		
	}
	if($r_photo){
		$url = shortUrl($url);
		$url = $url->id;
		//var_dump($url);
		//echo $url."<br>";
		//Вывод названия товара
		if( count($data->find('#pb-left-column h1')) ){
			$product_title = $data->find('#pb-left-column h1',0);
			$product_title = trim(html_entity_decode($product_title->plaintext));

			//echo $product_title."<br>".strlen($product_title);
			//$product_title = htmlspecialchars($product_title);
					
		 }else{
		 	echo false;
		 }
		//Вывод ,бренда
		 if( count($data->find('p.ot-brand')) ){
		 	$brand = $data->find('p.ot-brand',0);	
		 	$brand = $brand->plaintext;
		 	//echo $brand."<br>";
		
		 
		 }else{
			echo false;
		}
		 // if( count($data->find('#product_reference')) ){
		 // 	$article = $data->find('#product_reference',0);
		 // 	$article = $article->plaintext;
		 // 	$article = trim(html_entity_decode($article));
		 // 	//echo $article."<br>".strlen($article);
		 // }else{
		 // 	echo false;
		 // }
		 if( count($data->find('ul#attribute_to_pick_list')) ){
		 	//echo "Размер ";
		 	$size1 = array();
			foreach($data->find('ul#attribute_to_pick_list li a') as $size) {
				//echo  $size->plaintext." ";	
				$size1[] = $size->plaintext;
			}
			$size_str = implode(" ",$size1);
		 }else{
		 	
		 }
		 //Вывод цены
		 if(count($data->find('span#opt_price_display'))){
		 		//echo "<span>Цена в магазине </span>";
		 	$price = $data->find('span#opt_price_display',0);
			$price = $price->plaintext;
		 	//echo  $price."<br>";
		
		 }else{			
		
		 }
		 if(  count($data->find('span#our_price_display_red'))){
		 	//echo "<span style='text-transform:uppercase;'>Ваша цена </span>";
		 	$price2 = $data->find('span#our_price_display_red',0);
		 	$price2 = $price2->plaintext;
		 //	echo  $price2."<br>";
		
		 }else{			
		 	echo "Такой тег не найден<br>";
		 }
		 //   Вывод описания
		  if(count($data->find('#idTab1'))){
		  	$descript = $data->find('#idTab1 p',0);
		  	$descript = $descript->plaintext;
		  //	echo $descript."<br>";
		  	$descript_arr = array();
		 	foreach($data->find('#idTab1 ul li') as $descript1) {	
		 		//echo $descript1->plaintext."<br>";
		 		$descript_arr[] = $descript1->plaintext;
		 	}
		 	$descript_str =  implode("\n\r",$descript_arr);
		 }else{			
		 	
		 }
		 //Конец 
		// echo "<br><br>";

	}
	$temp_captions = "$url\n\r$product_title\n\r$brand\n\rРазмер: $size_str\n\rЦена: $price руб\n\rВаша цена: $price2\n\rОписание: $descript\n\r$descript_str\n\r";

	$data->clear();// подчищаем за собой
	unset($data);
	//print_r(htmlspecialchars($temp_captions));
	return $temp_captions;
}

function getYandexImages($url,$findpages = true,$i=1,$n=200){
	$f=1;
	$captions = array();
	
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
	if( $findpages and count($data->find('a.product_img_link'))){
		foreach($data->find('a.product_img_link') as $a){	
			 //echo $a->href.'<br>';
			// довольно распространенный случай - локальный URL. Поэтому иногда url надо дополнять до полного
			if( !preg_match('#^http://#',$a->href) )$a->href = 'http://shoestown.ru'.$a->href;
			// и еще дна тонкость, &amp; надо заменять на &
			$a->href = str_replace('&amp;','&',$a->href);
			 //echo $a->href.'<br>';
			// вызываем функцию для каждой страницы
		
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
	//print_r($captions)
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
	//print_r($captions);


}
//var_dump($_POST);
if(isset($POST['albums_id'])){
	//echo true;
    $album_id = $POST['albums_id'];
    $group_id = $_POST['groups_id'];
}
?>