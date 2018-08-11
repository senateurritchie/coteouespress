<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MovieDirector
 *
 * @ORM\Table(name="movie_director", options={"comment":"enregistre les realisateurs d'un movie"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieDirectorRepository")
 */
class MovieDirector
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="directors")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $movie;

    /**
    * @var AppBundle\Entity\Director
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Director", inversedBy="movies")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $director;

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
     * @return MovieDirector
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
     * @return MovieDirector
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
     * Set director
     *
     * @param \AppBundle\Entity\Director $director
     *
     * @return MovieDirector
     */
    public function setDirector(\AppBundle\Entity\Director $director = null)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director
     *
     * @return \AppBundle\Entity\Director
     */
    public function getDirector()
    {
        return $this->director;
    }
}
