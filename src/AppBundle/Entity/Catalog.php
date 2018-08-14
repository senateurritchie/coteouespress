<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
* Actor
*
* @ORM\Table(name="catalog", options={"comment":"permet de stocker les liens de visionnage de catalogue"})
* @ORM\Entity(repositoryClass="AppBundle\Repository\CatalogRepository")
*/
class Catalog{
    /**
    * @var int
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;
    /**
    * @var AppBundle\Entity\User
    *
    * @Groups({"group1","group2"})
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $creator;
    /**
    * @var string
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="type", type="string", length=15, options={"comment":"le type type de catalogue soit ESA ou FSA"}, columnDefinition="ENUM('FSA','ESA','WORLD')", nullable=true)
    */
    protected $type;
    /**
    * @var string
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="token", type="string", length=16, options={"comment":"code unique du catalogue"},unique=true, nullable=false)
    */
    protected $token;
    /**
    * @var array
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="criteria", type="array", options={"comment":"les parametres ou critères de recherche"}, nullable=true)
    */
    protected $criteria;
    /**
    * @var datetime
    * 
    * @Groups({"group1","group2"})
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="createAt", type="datetime", options={"comment":"la dete de creation du catalogue"}, nullable=true)
    */
    protected $createAt;

    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Catalog
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Catalog
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set criteria
     *
     * @param array $criteria
     *
     * @return Catalog
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * Get criteria
     *
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Catalog
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set creator
     *
     * @param \AppBundle\Entity\User $creator
     *
     * @return Catalog
     */
    public function setCreator(\AppBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreator()
    {
        return $this->creator;
    }
}
