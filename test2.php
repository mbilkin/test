<?php
$data1 = [
    'parent.child.field' => 1,
    'parent.child.field2' => 2,
    'parent2.child.name' => 'test',
    'parent2.child2.name' => 'test',
    'parent2.child2.position' => 10,
    'parent3.child3.position' => 10,
];

/**
 * @param  array $data исходный массив 
 * @param  array $type Тип формирования (0 - новый вид, 1 - обратно в исходный) 
 * @return array сформированный массив
 * 
 */
function my($data, $type = 0) {
  
  $tmpKey = []; $result = [];
  
  // Рекурсивно собирает в массив одномерный
  $generate_back = function ($arr) use (&$generate_back, &$tmpKey, &$result) {
        
        // Если массив перебираем элеиенты
        if (is_array($arr))
            foreach ($arr as $key=>$value) {
                  
                // накапливаем ключи  
                $tmpKey[] = $key; 
                // рекурсивно вызываем саму себя 
                $generate_back($value); 
            }
        else {
            // Если не массив сохраняем с ключом собранным из ключей     
            $result[join('.',$tmpKey)] =  $arr;   
        }
      
        // извлекаем последний
        array_pop($tmpKey);
  };
  
  // разбирает на многомерный массив
  $generate = function ($arr) use (&$result) {
    // Бежим по массиву
    foreach ($arr as $key=>$value) {
          
          // Ключ содержит точку
          if (strpos($key,'.')!==false) {
                      
              // Превращаем ключ в массив ключей        
              $keys = explode('.',$key); 
                      
              // Первый ключ берем и сохраняем значение        
              $tmpArr=[array_shift($keys)=>$value];
                      
              // Бежим по оставшимся       
              foreach ($keys as $el) {
                              
                // Вложенность, обращаемся рекурсивно к последнему и вписывем туда массив               
                array_walk_recursive($tmpArr, function(&$item) use ($el) {
                  $item = [$el=>$item];      
                });
                              
              };        
          } else
              $tmpArr=[$key=>$value]; // Кладем в промежуточную переменную ключ значение
          
          // Сливаем массив с Итоговым 
          $result = array_merge_recursive($result,$tmpArr);
    }
  };

  
  if ($type) 
      $generate_back($data);
  else 
      $generate($data);
  
  return $result;
      
}

echo "<pre>";

print_r(my($data1));
print_r(my(my($data1),1));

