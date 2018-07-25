<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MovieResource
 *
 * @ORM\Table(name="movie_resource", options={"comment":"enregistre les differents formats d'images pour un movie"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieResourceRepository")
 */
class MovieResource
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
    * @ORM\Column(name="cover", type="string", length=255, nullable=true, options={"comment":"stock l'image de couverture du programme"})
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=1920,maxWidth=1920, minHeight=1080,maxHeight=1080)
    */
    private $cover;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="landscape", type="string", length=255, nullable=true, options={"comment":"stock la vignette en paysage du programme"})
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=640,maxWidth=640, minHeight=360,maxHeight=360)
    */
    private $landscape;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="portrait", type="string", length=255, nullable=true,options={"comment":"stock la vignette en portrait du programme"})
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=270,maxWidth=270, minHeight=360,maxHeight=360)
    */
    private $portrait;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
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
     * Set cover
     *
     * @param string $cover
     *
     * @return MovieResource
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set landscape
     *
     * @param string $landscape
     *
     * @return MovieResource
     */
    public function setLandscape($landscape)
    {
        $this->landscape = $landscape;

        return $this;
    }

    /**
     * Get landscape
     *
     * @return string
     */
    public function getLandscape()
    {
        return $this->landscape;
    }

    /**
     * Set portrait
     *
     * @param string $portrait
     *
     * @return MovieResource
     */
    public function setPortrait($portrait)
    {
        $this->portrait = $portrait;

        return $this;
    }

    /**
     * Get portrait
     *
     * @return string
     */
    public function getPortrait()
    {
        return $this->portrait;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return MovieResource
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
