<?php
/**
 * Пример позднего статического связывания
 */

interface  wrapperTestAbstract {
    public static function fill($from, &$to);
}

// Реализация объект в объект
class wrapperObjectsToObjects implements  wrapperTestAbstract
{
    public static $map;

    public static function fill($from, &$to)
    {
        $reflectFrom = new ReflectionClass($from);
        $reflectTo = new ReflectionClass($to);
        foreach (static::$map as $key=>$val) {
            if ($reflectTo->hasProperty($key)) {
                if (strpos($val, '()')!==false) { 
                    $val = str_replace('()','',$val);     
                    if ($reflectFrom->hasMethod($val)) {
                        $to->$key = $from->$val();      
                    }
                } elseif ($reflectFrom->hasProperty($val))
                    $to->$key = $from->$val;
            }
        }
    }
}
// Реализация объект в массив
class wrapperObjectsToArray implements  wrapperTestAbstract
{
    public static $map;

    public static function fill($from, &$to)
    {
        $reflectFrom = new ReflectionClass($from);
        foreach (static::$map as $key=>$val) {
            if (isset($to[$key])) {
                if (strpos($val, '()')!==false) { 
                    $val = str_replace('()','',$val);     
                    if ($reflectFrom->hasMethod($val)) {
                        $to[$key] = $from->$val();      
                    }
                } elseif ($reflectFrom->hasProperty($val))
                    $to[$key] = $from->$val;
            }
        }       
    }
}

// Конкретная реализация - Апи в Модель
class wrapperApiTOData extends wrapperObjectsToObjects
{
    public static $map = [
        'firstName'=>'firstName()',
        'lastName'=>'lastName()',
        'avatar'=>'avatar()'
    ];
    
}

// Конкретная реализация2 - Апи в Модель 
class wrapperApiToData2 implements wrapperTestAbstract
{
    public static $map = [
        'firstName'=>'firstName()',
        'lastName'=>'lastName()',
        'avatar'=>'avatar()'
    ];
    
    public static function fill($api, &$model)
    {
        $reflect = new ReflectionClass($model);
        $props = $reflect->getProperties();
        foreach ($props as $prop) {
            $propName = $prop->getName();
            $model->$propName = $api->$propName();
        }
    }
}



Class F {
    public $test;
    function firstName() {return 'sdas';}
    function lastName() {return 'sdaasdass';}
    function avatar() {return 'asda';}
}

Class T {
    public $firstName;
    public $lastName;
    public $avatar;
}

$t = new T();
$f = new F();
wrapperApiToData2::fill($f,$t);
var_dump($t);
