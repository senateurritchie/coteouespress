<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MovieCountry
 *
 * @ORM\Table(name="movie_country", options={"comment":"on enregistre les pays de production du movie"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieCountryRepository")
 */
class MovieCountry
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
    * @var AppBundle\Entity\Movie
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="countries")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $movie;

    /**
    * @var AppBundle\Entity\Country
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country", inversedBy="movies")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $country;

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return MovieCountry
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
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return MovieCountry
     */
    public function setMovie(\AppBundle\Entity\Movie $movie = null)
    {
        $this->movie = $movie;

        return $this;
    }

    /**
     * Get movie
     *
     * @return \AppBundle\Entity\Movie
     */
    public function getMovie()
    {
        return $this->movie;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return MovieCountry
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \AppBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}
