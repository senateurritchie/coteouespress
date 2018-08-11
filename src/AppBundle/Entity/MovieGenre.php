<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MovieGenre
 *
 * @ORM\Table(name="movie_genre", options={"comment":"enregistre les genres d'un movie"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieGenreRepository")
 */
class MovieGenre
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="genres")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $movie;

    /**
    * @var AppBundle\Entity\Genre
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Genre", inversedBy="movies")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $genre;

    /**
    * @var \DateTime
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
     * @return MovieGenre
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
     * @return MovieGenre
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
     * Set genre
     *
     * @param \AppBundle\Entity\Genre $genre
     *
     * @return MovieGenre
     */
    public function setGenre(\AppBundle\Entity\Genre $genre = null)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return \AppBundle\Entity\Genre
     */
    public function getGenre()
    {
        return $this->genre;
    }
}
