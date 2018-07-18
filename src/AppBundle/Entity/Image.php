<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image", options={"comment":"enregistre toutes les images uploadées sur la plateforme"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
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
    * @var int
    *
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\File")
    * @ORM\JoinColumn(onDelete="CASCADE")
    */
    private $file;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer", options={"comment":"la largeur de l'image"})
     */
    private $width;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer", options={"comment":"la hauteur de l'image"})
     */
    private $height;

    /**
     * @var int
     *
     * @ORM\Column(name="ratio", type="integer", options={"comment":"le ratio des dimensions"})
     */
    private $ratio;


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
     * Set width
     *
     * @param integer $width
     *
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set ratio
     *
     * @param integer $ratio
     *
     * @return Image
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * Get ratio
     *
     * @return int
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * Set file
     *
     * @param \AppBundle\Entity\File $file
     *
     * @return Image
     */
    public function setFile(\AppBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \AppBundle\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }
}
