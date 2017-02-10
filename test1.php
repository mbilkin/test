<?php

$x = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
                     
// Первый ключ берем и сохраняем значение        
$tmpArr=[array_shift($x)=>null];
// Бежим по оставшимся       
foreach ($x as $el) {
                              
    // Вложенность, обращаемся рекурсивно к последнему и вписывем туда массив               
    array_walk_recursive($tmpArr, function(&$item) use ($el) {
        $item = [$el=>null];      
    });
                              
}; 
$x = $tmpArr;
echo "<pre>";
print_r($x);
