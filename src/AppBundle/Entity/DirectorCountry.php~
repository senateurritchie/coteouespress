<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DirectorCountry
 *
 * @ORM\Table(name="director_country", options={"comment":"liaison entre les realisateurs et leur pays d'origine"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DirectorCountryRepository")
 */
class DirectorCountry
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
    * @var AppBundle\Entity\Director
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Director", inversedBy="countries")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $director;

    /**
    * @var AppBundle\Entity\Country
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country", inversedBy="directors")
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
     * @return DirectorCountry
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
     * Set director
     *
     * @param \AppBundle\Entity\Director $director
     *
     * @return DirectorCountry
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

    /**
     * Set country
     *
     * @param \AppBundle\Entity\Country $country
     *
     * @return DirectorCountry
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
