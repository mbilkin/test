<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Silicone\Application;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client extends EntityAbstract
{
    /**
     * @ORM\Id
     * @Assert\NotBlank()
     * @ORM\Column(type="string") 
     */
    protected $Login;

    /**
     * @ORM\Column
     * @Assert\NotBlank()
     * @Assert\Length(min = "3", max = "255")
     */
    protected $FIO;
    
    /**
     * @ORM\Column
     */
    protected $Email;
    
    /**
     * @ORM\Column (type="datetime")
     */
    protected $cdate;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="Login", cascade={"all"})
     * @ORM\JoinTable(
     *     name="users_clients",
     *     joinColumns={@ORM\JoinColumn(name="login", referencedColumnName="Login")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     **/
    protected $Users;
    
    public function __construct($objects=null, Application $app=null, $FKkey = array(), $clear = 0)
    {
        $this->Users = new ArrayCollection();
        parent::__construct($objects, $app, $FKkey, $clear);
    }
    
    public function setUsers($User)
    {
        if ($pos=$this->existsUsers($User)===false) 
            $this->Users[] = $User;
        else
            $this->Users[$pos] = $User;
    }
    
    public function removeUser($User)
    {
        $pos=$this->existsUsers($User);
        if ($pos!==false) 
            unset($this->Users[$pos]);
    }
    
    public function existsUsers($User)
    {
        foreach ($this->Users as $k=>$_User)
            if ($_User->getId() == $User->getId())    
                return $k;
        
            return false;
    }
    
    public function setUsersALL($Users) {
        foreach ($Users as $User) {
            $User->setLogin($this);
        }
        $this->Users = $Users;
    }

    public function getLogin()
    {
        return $this->Login;
    }
    
    public function setLogin($Login)
    {
        $this->Login = $Login;
    }

    public function getFIO()
    {
        return $this->FIO;
    }

    public function setFIO($FIO)
    {
        $this->FIO = $FIO;
    }
    
    public function getEmail()
    {
        return $this->Email;
    }

    public function setEmail($Email)
    {
        $this->Email = $Email;
    }
}