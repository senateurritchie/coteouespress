<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * MovieProducer
 *
 * @ORM\Table(name="movie_producer", options={"comment":"enregistre les producteurs d'un movie"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieProducerRepository")
 */
class MovieProducer
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="producers")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $movie;

    /**
    * @var AppBundle\Entity\Producer
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Producer", inversedBy="movies")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $producer;

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
     * @return MovieProducer
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
     * @return MovieProducer
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
     * Set producer
     *
     * @param \AppBundle\Entity\Producer $producer
     *
     * @return MovieProducer
     */
    public function setProducer(\AppBundle\Entity\Producer $producer = null)
    {
        $this->producer = $producer;

        return $this;
    }

    /**
     * Get producer
     *
     * @return \AppBundle\Entity\Producer
     */
    public function getProducer()
    {
        return $this->producer;
    }
}
