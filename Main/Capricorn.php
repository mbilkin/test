<?php
namespace Main;

/**
 * Класс Козерог
 * Выводит на экран пользователей по знаку зодиака Козерог, 
 * с постраничной разбивкой (по 20 пользователей);
 */
Class Capricorn {
    
    /**
     * Дефолтное значение строк на страницу
     */
    const DEFAULT_ONPAGE = 20;
    
    /**
     * Дефолтное значение страницы текущей
     */
    const DEFAULT_PAGE = 1;
    
    /**
     * Объект работы с базой 
     * @var source 
     */
    protected $db;
    
    /**
     * Количество строк всего
     * @var type integer
     */
    protected $cnt;
    
    /**
     * Количество строк на страницу
     * @var integer
     */
    protected $onPage;
    
    /**
     * Текущая страница
     * @var integer
     */
    protected $page;
    
    /**
     * Набор данных
     * @var array
     */
    protected $result;
    
    /**
     * Создание объекта Козерог
     * @param PDO $db обект работы с базой
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }
    
    /**
     * Получение общего количества строк
     * @return integer
     */
    public function getCount()
    {
    	if (!isset($this->cnt)) {
    		$sql = "SELECT COUNT(1) AS cnt FROM result";
    		$res = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    		$this->cnt = intval($res[0]["cnt"]);
    	}
    	
    	return $this->cnt;
    }
    
    /**
     * Получение результата из базы
     * @param integer $page
     * @param integer $onPage
     * @return \Capricorn
     */
    public function getList($page=Capricorn::DEFAULT_PAGE, $onPage=Capricorn::DEFAULT_ONPAGE)
    {
    	$this->onPage = (empty($onPage) || !is_numeric($onPage) || $onPage<0) ? Capricorn::DEFAULT_ONPAGE : $onPage;
    	$this->page=(empty($page) || !is_numeric($page) || $page<0) ? Capricorn::DEFAULT_PAGE : $page;
    	$start = ($this->page-1)*$onPage;
    	
        // Если количество меньше старта то отдаем пустой результат
    	if ($this->getCount()==0 || $this->getCount()<=$start) { 
            $this->result = array(); 
        } else {
            $sql = "SELECT * FROM result LIMIT ".$start.", ".$this->onPage.";";
            $this->result = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $this;
    }
    
    /**
     * Получение результата в виде массива
     * @return array
     */
    public function getResult() {
        return $this->result;
    }
    
    /**
     * Вывод результата
     * @return \Capricorn 
     */
    public function printList() 
    {
        echo "Козероги: <br><table>";
        $head = true;
        foreach ($this->result as $row)  {
            $tmp = array();
            if ($head) {
                echo "<thead><tr><th>".implode("</th><th>",array_keys($row))."</th></tr></thead><tbody>";
            }
            echo "<tr><td>".implode("</td><td>",array_values($row))."</td></tr>";
            $head = false;
        }
        echo "</tbody></table>";
        return $this;
    }
    
    /**
     * Вывод пейжера, простой без наворотов
     * @return \Capricorn
     */
    public function printPager() 
    {
        echo "<br>стр: ";
        for ($i=1;$i<=ceil($this->getCount()/$this->onPage);$i++) {
            echo ($i==$this->page) ? ' 1 ' : ' <a href="?p='.$i.'">'.$i.'</a> ';
        }
        return $this;
    }
}
