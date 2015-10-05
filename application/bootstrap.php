<?
require_once 'core/config.php';
session_start();
function _sleep(){
    global $timeSelect;
    global $select;
    if ( $select >= 2 ) {
        usleep(10000);
        if ( time() > $timeSelect ) {
            $timeSelect = time();
            $select = 0;
        } else {
            _sleep();
        }
    }
}

function _curl_exec($ch){
    global $select;
    _sleep();
    $return = curl_exec($ch);
    $select++;

    return $return;
}
require_once 'lib/simple_html_dom.php';
require_once 'lib/short_url.php';
require_once 'core/users.php';
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/route.php';






Route::start(); //запускаем маршрутизатор
$db = null;