<?php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Silicone\Application;
/**
 * @ORM\Entity
 * @ORM\Table(name="banner")
 */
class Banner extends EntityAbstract
{
    /**
     * @ORM\Id
     * @Assert\NotBlank()
     * @ORM\Column(type="bigint") 
     */
    protected $BannerID;
    
    /**
     * @ORM\ManyToOne(targetEntity="Campaign") 
     * @ORM\JoinColumn(name="CampaignID", referencedColumnName="CampaignID")
     */
    protected $CampaignID;

    /**
     * @ORM\Column
     * @Assert\NotBlank()
     * @Assert\Length(min = "3", max = "255")
     */
    protected $Title;
    
    /**
     * @ORM\Column
     */
    protected $Href;
    
    /**
     * @ORM\Column (type="string")
     * @Assert\Length(min = "2", max = "3")
     */
    protected $StatusArchive;
    
    /**
     * @ORM\Column (type="datetime")
     */
    protected $cdate;
    
    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="BannerID", cascade={"all"})
     * @ORM\JoinTable(
     *     name="tags_banner",
     *     joinColumns={@ORM\JoinColumn(name="banner_id", referencedColumnName="BannerID")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="TagID")}
     * )
     **/
    protected $Tags;
    
    public function __construct($objects=null, Application $app=null, $FKkey = array(), $clear = 0)
    {
        $this->Tags = new ArrayCollection();
        parent::__construct($objects, $app, $FKkey, $clear);
    }

    /**
     * Add Tag
     */
    public function setTags($tag) {
        if (!$this->existsTags($tag)) 
            $this->Tags[] = $tag;
    }
    
    public function existsTags($Tag)
    {
        foreach ($this->Tags as $k=>$_tag)
            if ($_tag->getTagID() == $Tag->getTagID())    
                return $k;
        
            return false;
    }
    
    /**
     * Get categories
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTags() {
        return $this->Tags;
    }
    
    public function setTagsALL($Tags) {
        foreach ($Tags as $Tag) {
            $Tag->setBanner($this);
        }
        $this->Tags = $Tags;
    }
    
    public function getBannerID()
    {
        return $this->BannerID;
    }
    
    public function setBannerID($BannerID)
    {
        $this->BannerID = $BannerID;
        return $this;
    }
    
    public function getCampaignID()
    {
        return $this->CampaignID;
    }
    
    public function setCampaignID(Campaign $CampaignID = null)
    {
        $this->CampaignID = $CampaignID;
    }

    public function getTitle()
    {
        return $this->Title;
    }

    public function setTitle($Title)
    {
        $this->Title = $Title;
    }
    
    public function getHref()
    {
        return $this->Href;
    }

    public function setHref($Href)
    {
        $this->Href = $Href;
    }
    
    public function getStatusArchive()
    {
        return $this->StatusArchive;
    }

    public function setStatusArchive($StatusArchive)
    {
        $this->StatusArchive = $StatusArchive;
    }
    
}