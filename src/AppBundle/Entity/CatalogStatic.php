<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * CatalogStatic
 *
 * @ORM\Table(name="catalog_static", options={"comment":"enregistre les catalogues générées manuellement à mettre en téléchargement dans espace client"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CatalogStaticRepository")
 */
class CatalogStatic
{
    /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @var AppBundle\Entity\CatalogType
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CatalogType", inversedBy="downloads")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $catalog;
    /**
    * @var string
    *
    * @ORM\Column(name="file", type="string", length=255, unique=true, options={"comment":"le nom du fichier uploadé"})
    * @assert\File(maxSize="100M", mimeTypes="application/pdf")
    */
    private $file;
    /**
    * @var \DateTime
    *
    * @ORM\Column(name="year", type="date")
    */
    private $year;

    /**
    * @var boolean
    *
    * @ORM\Column(name="published", type="boolean")
    */
    private $published = 0;

    /**
    * @var int
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="download_nbr", type="integer", nullable=true,options={"comment":"le nombre de fois le catalogue a été téléchargé espace client"})
    */
    private $downloadNbr = 0;
    /**
    * @var string
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="token", type="string", length=64, options={"comment":"code unique du catalogue"},unique=true, nullable=false)
    */
    protected $token;

    /**
    * @var string
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="description", type="text", options={"comment":"courte description du catalogue"},nullable=true)
    */
    protected $description;

    /**
    * @var \DateTime
    *
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime")
    */
    private $createAt;


    

    

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
     * Set file
     *
     * @param string $file
     *
     * @return CatalogStatic
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set year
     *
     * @param \DateTime $year
     *
     * @return CatalogStatic
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \DateTime
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CatalogStatic
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set downloadNbr
     *
     * @param integer $downloadNbr
     *
     * @return CatalogStatic
     */
    public function setDownloadNbr($downloadNbr)
    {
        $this->downloadNbr = $downloadNbr;

        return $this;
    }

    /**
     * Get downloadNbr
     *
     * @return integer
     */
    public function getDownloadNbr()
    {
        return $this->downloadNbr;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return CatalogStatic
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
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return CatalogStatic
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
     * Set catalog
     *
     * @param \AppBundle\Entity\CatalogType $catalog
     *
     * @return CatalogStatic
     */
    public function setCatalog(\AppBundle\Entity\CatalogType $catalog = null)
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catalog
     *
     * @return \AppBundle\Entity\CatalogType
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CatalogStatic
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
