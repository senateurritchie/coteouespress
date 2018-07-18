<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @var AppBundle\Entity\Image
    *
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $cover;

    /**
    * @var AppBundle\Entity\Image
    *
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $profil;

    /**
    * @var AppBundle\Entity\Image
    *
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $vignette;


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
     * Set cover
     *
     * @param \AppBundle\Entity\Image $cover
     *
     * @return MovieResource
     */
    public function setCover(\AppBundle\Entity\Image $cover = null)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return \AppBundle\Entity\Image
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set profil
     *
     * @param \AppBundle\Entity\Image $profil
     *
     * @return MovieResource
     */
    public function setProfil(\AppBundle\Entity\Image $profil = null)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return \AppBundle\Entity\Image
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set vignette
     *
     * @param \AppBundle\Entity\Image $vignette
     *
     * @return MovieResource
     */
    public function setVignette(\AppBundle\Entity\Image $vignette = null)
    {
        $this->vignette = $vignette;

        return $this;
    }

    /**
     * Get vignette
     *
     * @return \AppBundle\Entity\Image
     */
    public function getVignette()
    {
        return $this->vignette;
    }
}
