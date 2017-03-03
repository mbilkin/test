<?php
namespace Main;

/**
 * ����� �������
 * ������� �� ����� ������������� �� ����� ������� �������, 
 * � ������������ ��������� (�� 20 �������������);
 */
Class Capricorn {
    
    /**
     * ��������� �������� ����� �� ��������
     */
    const DEFAULT_ONPAGE = 20;
    
    /**
     * ��������� �������� �������� �������
     */
    const DEFAULT_PAGE = 1;
    
    /**
     * ������ ������ � ����� 
     * @var source 
     */
    protected $db;
    
    /**
     * ���������� ����� �����
     * @var type integer
     */
    protected $cnt;
    
    /**
     * ���������� ����� �� ��������
     * @var integer
     */
    protected $onPage;
    
    /**
     * ������� ��������
     * @var integer
     */
    protected $page;
    
    /**
     * ����� ������
     * @var array
     */
    protected $result;
    
    /**
     * �������� ������� �������
     * @param PDO $db ����� ������ � �����
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }
    
    /**
     * ��������� ������ ���������� �����
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
     * ��������� ���������� �� ����
     * @param integer $page
     * @param integer $onPage
     * @return \Capricorn
     */
    public function getList($page=Capricorn::DEFAULT_PAGE, $onPage=Capricorn::DEFAULT_ONPAGE)
    {
    	$this->onPage = (empty($onPage) || !is_numeric($onPage) || $onPage<0) ? Capricorn::DEFAULT_ONPAGE : $onPage;
    	$this->page=(empty($page) || !is_numeric($page) || $page<0) ? Capricorn::DEFAULT_PAGE : $page;
    	$start = ($this->page-1)*$onPage;
    	
        // ���� ���������� ������ ������ �� ������ ������ ���������
    	if ($this->getCount()==0 || $this->getCount()<=$start) { 
            $this->result = array(); 
        } else {
            $sql = "SELECT * FROM result LIMIT ".$start.", ".$this->onPage.";";
            $this->result = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $this;
    }
    
    /**
     * ��������� ���������� � ���� �������
     * @return array
     */
    public function getResult() {
        return $this->result;
    }
    
    /**
     * ����� ����������
     * @return \Capricorn 
     */
    public function printList() 
    {
        echo "��������: <br><table>";
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
     * ����� �������, ������� ��� ���������
     * @return \Capricorn
     */
    public function printPager() 
    {
        echo "<br>���: ";
        for ($i=1;$i<=ceil($this->getCount()/$this->onPage);$i++) {
            echo ($i==$this->page) ? ' 1 ' : ' <a href="?p='.$i.'">'.$i.'</a> ';
        }
        return $this;
    }
}
