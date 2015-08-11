<?
class Controller_vse extends Controller
{

    function __construct()
    {
   
        
        $this->view = new View();
    }
    
    function action_index()
    {
      		
        $this->view->generate('vse_view.php', 'main_template.php');
    }
}