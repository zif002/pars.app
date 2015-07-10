<?
class Controller_VK extends Controller
{

    function __construct()
    {
        $this->model = new Model_VK();
        $this->view = new View();
    }
    
    function action_index()
    {
        $data = $this->model->get_data();		
        $this->view->generate('vk_view.php', 'main_template.php',$data);
    }
}