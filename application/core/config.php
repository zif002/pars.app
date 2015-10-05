<?

ini_set('max_execution_time', 3600);
class DB {
    private $host = "localhost";
    private $dbname = "pars";
    private $user = "admin";
    private $pass = "1111";
    
   
    public $db;

    public function __construct() {
      try {   
        $this->db = new PDO ("mysql:host=".$this->host.";dbname=".$this->dbname,$this->user,$this->pass);
        
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->exec("set names utf8");
        //echo "ok";
      } catch (PDOException $e) {
        echo "<span style='color:red;font-size:24px;padding10px;border:5px solid red;'>Failed to get DB handle: " . $e->getMessage() . "</span>\n";
       
      }
    }

}








