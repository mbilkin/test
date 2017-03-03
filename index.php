<?php
/**
 * Основную логику заложил в базе в представлении
 * Создал доп. таблицу с интервалами в датах.
 * Проблема может быть при большом количестве пользователей, 
 * как вариант сделать доп. поле предрасчетным в ней хранить дату в формате 2000-m-d
 * 
 * 5 пукт, не совсем понял что за прогнозы?
 * 
 */
DEFINE("DBNAME",'test');
DEFINE("DBLOGIN",'div');
DEFINE("DBPASS",'div');

spl_autoload_register(function ($className) {
    $filename = __DIR__."/".str_replace("\\","/",$className) . ".php";
    if (is_readable($filename)) {
        require_once $filename;
    }
});

use Main\Capricorn;

$db  = new \PDO('mysql:host=127.0.0.1;dbname='.DBNAME, DBLOGIN, DBPASS);
$capricorn = new Capricorn($db);
$capricorn->getList(intval($_GET['p']),1) // Убрать 1 или выставить 20 чтоб было 20 на странице
          ->printList()
          ->printPager();
?>