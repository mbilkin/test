<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Silicone\Application;
/**
 * @ORM\Entity
 * @ORM\Table(name="campaign")
 */
class Campaign extends EntityAbstract
{
    /**
     * @var integer $CampaignID
     * 
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @Assert\NotBlank()
     *  
     */
    protected $CampaignID;
    
    /**
     * @ORM\ManyToOne(targetEntity="Client") 
     * @ORM\JoinColumn(name="Login", referencedColumnName="Login")
     */
    protected $Login;

    /**
     * @ORM\Column
     * @Assert\NotBlank()
     * @Assert\Length(min = "3", max = "255")
     */
    protected $Name;
    
    /**
     * @ORM\Column
     */
    protected $IsActive;
    
    /**
     * @ORM\Column (type="datetime")
     */
    protected $cdate;
    
    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="CampaignID", cascade={"all"})
     * @ORM\JoinTable(
     *     name="tags_campaign",
     *     joinColumns={@ORM\JoinColumn(name="campaign_id", referencedColumnName="CampaignID")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="TagID")}
     * )
     **/
    protected $Tags;
    
    public function __construct($objects=null, Application $app=null, $FKkey = array(), $clear = 0)
    {
        $this->Tags = new ArrayCollection();
        parent::__construct($objects, $app, $FKkey, $clear);
    }

    public function setTags($Tag)
    {
        if (!$this->existsTags($tag)) 
            $this->Tags[] = $Tag;
    }
    
    public function existsTags($Tag)
    {
        foreach ($this->Tags as $k=>$_tag)
            if ($_tag->getTagID() == $Tag->getTagID())    
                return $k;
        
            return false;
    }
    
    public function setTagsALL($Tags) {
        foreach ($Tags as $Tag) {
            $Tag->setCampaign($this);
        }
        $this->Tags = $Tags;
    }
    
    public function getCampaignID()
    {
        return $this->CampaignID;
    }
    
    public function setCampaignID($CampaignID)
    {
        $this->CampaignID = $CampaignID;
    }
    
    public function getLogin()
    {
        return $this->Login;
    }
    
    public function setLogin($Login)
    {
        $this->Login = $Login;
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setName($Name)
    {
        $this->Name = $Name;
    }
    
    public function getIsActive()
    {
        return $this->IsActive;
    }

    public function setIsActive($IsActive)
    {
        $this->IsActive = $IsActive;
    }
}