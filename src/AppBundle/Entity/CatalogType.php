<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * CatalogType
 *
 * @ORM\Table(name="catalog_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CatalogTypeRepository")
 */
class CatalogType
{
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
    * @var string
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="name", type="string", length=50, unique=true)
    */
    private $name;

    /**
    * @var string
    * 
    * @Groups({"group1","group2"})
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=50, unique=true)
    */
    private $slug;

    /**
    * @var int
    * 
    * @Groups({"group1","group2"})
    * @ORM\Column(name="movieNbr", type="integer", nullable=true)
    */
    private $movieNbr = 0;

    /**
    * @var \DateTime
    * @Groups({"group1","group2"})
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime")
    */
    private $createAt;


    /**
    * Get id
    *
    * @return int
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Set name
    *
    * @param string $name
    *
    * @return CatalogType
    */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
    * Get name
    *
    * @return string
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    * Set slug
    *
    * @param string $slug
    *
    * @return CatalogType
    */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
    * Get slug
    *
    * @return string
    */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
    * Set movieNbr
    *
    * @param integer $movieNbr
    *
    * @return CatalogType
    */
    public function setMovieNbr($movieNbr)
    {
        $this->movieNbr = $movieNbr;

        return $this;
    }

    /**
    * Get movieNbr
    *
    * @return int
    */
    public function getMovieNbr()
    {
        return $this->movieNbr;
    }

    /**
    * Set createAt
    *
    * @param \DateTime $createAt
    *
    * @return CatalogType
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
}

