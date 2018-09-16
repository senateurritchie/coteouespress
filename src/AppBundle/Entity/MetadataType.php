<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * MetadataType
 *
 * @ORM\Table(name="metadata_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MetadataTypeRepository")
 */
class MetadataType
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
    * @ORM\Column(name="name", type="string", length=100, unique=true)
    */
    private $name;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=100, unique=true)
    */
    private $slug;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="status", type="string", length=20, nullable=true)
    */
    private $status;


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
    * @return MetadataType
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
    * @return MetadataType
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
    * Set status
    *
    * @param string $status
    *
    * @return MetadataType
    */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
    * Get status
    *
    * @return string
    */
    public function getStatus()
    {
        return $this->status;
    }
}

