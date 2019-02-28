<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * MovieEvent
 *
 * @ORM\Table(name="movie_event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieEventRepository")
 */
class MovieEvent
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
    * @var AppBundle\Entity\Event
    *
    * @Groups({"group1","group2"})
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Event", inversedBy="events")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $event;

    /**
    * @var AppBundle\Entity\Movie
    *
    * @Groups({"group1","group2"})
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="events")
    * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
    */
    private $movie;

    /**
     * @var \DateTime
     *
     * @Groups({"group1","group2"})
     * @ORM\Column(name="start", type="date", nullable=true)
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @Groups({"group1","group2"})
     * @ORM\Column(name="end", type="date")
     */
    private $end;

    /**
    * @var boolean
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="is_published", type="boolean", options={"comment":"l'etat de publication de l'evenement"})
    */
    private $isPublished = 0;


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
     * Set state
     *
     * @param string $state
     *
     * @return MovieEvent
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set start
     *
     * @param \DateTime $start
     *
     * @return MovieEvent
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return MovieEvent
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     *
     * @return MovieEvent
     */
    public function setIsPublished($isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * Get isPublished
     *
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->isPublished;
    }

    /**
     * Set event
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return MovieEvent
     */
    public function setEvent(\AppBundle\Entity\Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return MovieEvent
     */
    public function setMovie(\AppBundle\Entity\Movie $movie)
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
