<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieEpisode
 *
 * @ORM\Table(name="movie_episode", options={"comment":"enregistre les episodes d'un movie"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieEpisodeRepository")
 */
class MovieEpisode
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="episodes")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $movie;

     /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

     /**
     * @var int
     *
     * @ORM\Column(name="pos", type="integer",options={"comment":"le numéro de l'épisode du movie"})
     */
    private $pos;

     /**
     * @var string
     *
     * @ORM\Column(name="full_url", type="string", length=255, options={"comment":"l'url d'accès à la video"},nullable=true)
     */
    private $fullUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="code_url", type="string", length=50, nullable=true)
     */
    private $codeUrl;

    /**
     * @var \DateTime
     *
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
     * @return MovieEpisode
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
     * Set pos
     *
     * @param integer $pos
     *
     * @return MovieEpisode
     */
    public function setPos($pos)
    {
        $this->pos = $pos;

        return $this;
    }

    /**
     * Get pos
     *
     * @return int
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return MovieEpisode
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set synopsis
     *
     * @param string $synopsis
     *
     * @return MovieEpisode
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * Get synopsis
     *
     * @return string
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * Set fullUrl
     *
     * @param string $fullUrl
     *
     * @return MovieEpisode
     */
    public function setFullUrl($fullUrl)
    {
        $this->fullUrl = $fullUrl;

        return $this;
    }

    /**
     * Get fullUrl
     *
     * @return string
     */
    public function getFullUrl()
    {
        return $this->fullUrl;
    }

    /**
     * Set codeUrl
     *
     * @param string $codeUrl
     *
     * @return MovieEpisode
     */
    public function setCodeUrl($codeUrl)
    {
        $this->codeUrl = $codeUrl;

        return $this;
    }

    /**
     * Get codeUrl
     *
     * @return string
     */
    public function getCodeUrl()
    {
        return $this->codeUrl;
    }

    /**
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return MovieEpisode
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
}
