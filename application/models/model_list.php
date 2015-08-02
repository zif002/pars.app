<?

class Model_VK extends Model
{

    public function get_data()
    {	
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
    public function setUsers($data){
       
        $pdoStatement = $db->prepare('SELECT * FROM `users` WHERE `id_vk`=:id1');

        $pdoStatement->bindParam(':id1', $data['vk_id']);
        $pdoStatement->execute();
        print_r( $pdoStatement->fetchAll());

    }

}

