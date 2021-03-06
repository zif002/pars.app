<?

class Model_VK extends Model{
    
    public function getList(){
        return array(
              [
                "case"  => 1,
                "name"  => "Все по корману",
                "path"  =>  "application/list_parser/parser_vse_pokormanu.php"
              ],
              [
                "case"  => 2,
                "name"  => "Флиско",
                "path"  =>  "application/list_parser/parser_flisco.php"
              ],
              [
                "case"  => 3,
                "name"  => "Бренды",
                "path"  =>  "application/list_parser/parser_shoestown.php",
                "title" => "Детская обувь",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 4,
                "name"  => "Торговый Дом Виктория",
                "path"  =>  "application/list_parser/parser_tdv.php",
                "title" => "Детская обувь",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 5,
                "name"  => "Кашиза",
                "path"  =>  "application/list_parser/parser_kashiza.php",
                "title" => "Детская обувь",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 6,
                "name"  => "Все по корману",
                "path"  =>  "application/list_parser/parser_vse_pokormanu.php",
                "title" => "Детская обувь",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 7,
                "name"  => "Сливки",
                "path"  =>  "application/list_parser/parser_slivki.php",
                "title" => "Детская обувь",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 8,
                "name"  => "Стиляж",
                "path"  =>  "application/list_parser/parser_stilyag.php",
                "title" => "Детская обувь",
                "min"   =>  "7000",
                "link"  =>  ""
         
              ],
              [
                "case"  => 9,
                "name"  => "Волга шуз",
                "path"  =>  "application/list_parser/parser_volgashouse.php",
                "title" => "Детская обувь",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 10,
                "name"  => "Трикотята",
                "path"  =>  "application/list_parser/parser_trikotata.php",
                "title" => "Детская одежда",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 11,
                "name"  => "Авангарде",
                "path"  =>  "application/list_parser/parser_avangarde.php",
                "title" => "Одежда",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 12,
                "name"  => "Кифа Обувь",
                "path"  =>  "application/list_parser/parser_qifa.php",
                "title" => "Обувь",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 13,
                "name"  => "Faq Fasion",
                "path"  =>  "application/list_parser/parser_faq_fashion.php",
                "title" => "Одежда",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 14,
                "name"  => "Золотой пони",
                "path"  =>  "application/list_parser/parser_gold_pony.php",
                "title" => "Одежда детская",
                "min"   =>  "7000",
                "link"  =>  ""
              ],
              [
                "case"  => 15,
                "name"  => "Ваши сласти",
                "path"  =>  "application/list_parser/parser_vashi_slasti.php",
                "title" => "Конфеты сладости",
                "min"   =>  "6000",
                "link"  =>  "http://vashislasti-opt.ru/"
              ],
              [
                "case"  => 16,
                "name"  => "Транго",
                "path"  =>  "application/list_parser/parser_trango.php",
                "title" => "Детская одежда",
                "min"   =>  "6500",
                "link"  =>  "http://www.trangowear.ru/"
              ],
              [
                "case"  => 17,
                "name"  => "Tоп натур",
                "path"  =>  "application/list_parser/parser_top_nature.php",
                "title" => "Кожаные тапки",
                "min"   =>  "10 000",
                "link"  =>  "http://top-natur.ru//",
                "category" => "obuv",
                "category_name" => "Обувь"
              ],
              [
                "case"  => 18,
                "name"  => "SUOMA.RU",
                "path"  =>  "application/list_parser/parser_fin_goods.php",
                "title" => "Товары из финляндии",
                "min"   =>  "1000",
                "link"  =>  "http://suoma.ru/",
                "category" => "goods",
                "category_name" => "Товары из финляндии"
              ],
              [
                "case"  => 19,
                "name"  => "Концелярский базар",
                "path"  =>  "application/list_parser/parser_conc_goods_1.php",
                "title" => "Концилярские товары",
                "min"   =>  "5000",
                "link"  =>  "http://www.stationerymarket.ru/",
                "category" => "conc_goods",
                "category_name" => "Концелярские товары"
              ],
              [
                "case"  => 20,
                "name"  => "Селл легинс",
                "path"  =>  "application/list_parser/parser_sellegins.php",
                "title" => "Одежда",
                "min"   =>  "10000",
                "link"  =>  "http://sellleggings.ru/",
                "category" => "goods",
                "category_name" => "Легинсы"
              ],
              [
                "case"  => 21,
                "name"  => "Сатурн",
                "path"  =>  "application/list_parser/parser_sellegins.php",
                "title" => "Бытовая химия",
                "min"   =>  "5000",
                "link"  =>  "http://saturnhim.ru/",
                "category" => "chimia",
                "category_name" => "Химя бытовая"
              ],
              [
                "case"  => 22,
                "name"  => "Радуга самоцветов",
                "path"  =>  "application/list_parser/parser_sellegins.php",
                "title" => "Бижютерия",
                "min"   =>  "3000",
                "link"  =>  "http://samotsvet.ru/",
                "category" => "bijuteria",
                "category_name" => "Бижютерия"
              ],
              [
                "case"  => 23,
                "name"  => "Rus podarki",
                "path"  =>  "application/list_parser/parser_sellegins.php",
                "title" => "Новогоднее",
                "min"   =>  "800",
                "link"  =>  "http://rus-podarki.ru/",
                "category" => "new-year",
                "category_name" => "New Year"
              ]
              
          );
    }
    public function get_data(){	
        return array(
            
            array(
                'Year' => '2012',
                'Site' => 'http://DunkelBeer.ru',
                'Description' => 'Промо-сайт темного пива Dunkel от немецкого производителя Löwenbraü выпускаемого в России пивоваренной компанией "CАН ИнБев".'
            ),
            array(
                'Year' => '2012',
                'Site' => 'http://ZopoMobile.ru',
                'Description' => 'Русскоязычный каталог китайских телефонов компании Zopo на базе Android OS и аксессуаров к ним.'
            ),
            // todo
        );
    }



  
}

