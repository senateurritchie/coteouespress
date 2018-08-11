<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProducerCountry
 *
 * @ORM\Table(name="producer_country", options={"comment":"liaison entre les producteurs et leur pays d'origine"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProducerCountryRepository")
 */
class ProducerCountry
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
    * @var AppBundle\Entity\Producer
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Producer", inversedBy="countries")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $producer;

    /**
    * @var AppBundle\Entity\Country
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country", inversedBy="producers")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $country;

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
     * @return ProducerCountry
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
     * Set producer
     *
     * @param \AppBundle\Entity\Producer $producer
     *
     * @return ProducerCountry
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

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return ProducerCountry
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
