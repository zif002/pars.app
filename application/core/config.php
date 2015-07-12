<?

//Подключение к базе данных

class Database{
    private $host      = 'localhost';
    private $user      = 'root';
    private $pass      = '';
    private $dbname    = 'pars';
 
    private $dbh;
    private $error;
 
    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instanace
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch(PDOException $e){
            $this->error = $e->getMessage();
        }
      
    }
       public function getDb() {
       		if ($this->db instanceof PDO) {
           	 return $this->dbh;
      	 }
 		}
}

