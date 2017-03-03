<?php
/**
 * �������� ������ ������� � ���� � �������������
 * ������ ���. ������� � ����������� � �����.
 * �������� ����� ���� ��� ������� ���������� �������������, 
 * ��� ������� ������� ���. ���� ������������� � ��� ������� ���� � ������� 2000-m-d
 * 
 * 5 ����, �� ������ ����� ��� �� ��������?
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
$capricorn->getList(intval($_GET['p']),1) // ������ 1 ��� ��������� 20 ���� ���� 20 �� ��������
          ->printList()
          ->printPager();
?>