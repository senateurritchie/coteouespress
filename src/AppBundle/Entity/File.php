<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Table(name="file", options={"comment":"enregistre tout les fichiers deposés sur la plateforme"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileRepository")
 */
class File
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
     * @var string
     *
     * @ORM\Column(name="original_name", type="string", length=255, options={"comment":"le nom original du fichier sur la machine du uploader"})
     */
    private $originalName;

     /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, unique=true, options={"comment":"le nom du fichier généré automatiquement sur la plateforme"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5, options={"comment":"l'extension du fichier uploadé"})
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=15, options={"comment":"le type du fichier uploadé"}, columnDefinition="ENUM('image','office doc','video','other','music','pdf')", nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="mime", type="string", length=30, options={"comment":"le mime type du fichier"})
     */
    private $mime;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", options={"comment":"la taille du fichier en octet"})
     */
    private $size;

   

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime", options={"comment":"la date d'upload du fichier sur la plateforme"})
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
     * Set originalName
     *
     * @param string $originalName
     *
     * @return File
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return File
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return File
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
     * Set mime
     *
     * @param string $mime
     *
     * @return File
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return File
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return File
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
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return File
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
