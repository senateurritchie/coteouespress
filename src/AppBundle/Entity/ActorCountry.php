<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActorCountry
 *
 * @ORM\Table(name="actor_country", options={"comment":"liaison entre les acteurs et leur pays d'origine"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActorCountryRepository")
 */
class ActorCountry
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
    * @var AppBundle\Entity\Actor
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Actor", inversedBy="countries")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $actor;

    /**
    * @var AppBundle\Entity\Country
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country", inversedBy="actors")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $country;

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
     * @return ActorCountry
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
     * Set actor
     *
     * @param \AppBundle\Entity\Actor $actor
     *
     * @return ActorCountry
     */
    public function setActor(\AppBundle\Entity\Actor $actor = null)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get actor
     *
     * @return \AppBundle\Entity\Actor
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return ActorCountry
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
