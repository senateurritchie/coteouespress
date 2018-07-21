<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Actor
 *
 * @ORM\Table(name="actor", options={"comment":"enregistre tout les acteurs"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ActorRepository")
 */
class Actor
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
    * @var string
    *
    * @Groups({"group1","group2"})
    * @assert\Length(min=3, max=50)
    * @ORM\Column(name="name", type="string", length=50)
    */
    private $name;

     /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="description", type="string", length=254)
    */
    private $description;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=100, unique=true)
    */
    private $slug;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="image", type="string", length=255, nullable=true)
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=200,maxWidth=400, minHeight=180,maxHeight=400,allowPortrait=false)
    */
    private $image;

    /**
    * @var \DateTime
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="create_at", type="datetime")
    */
    private $createAt;

    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\ActorCountry", mappedBy="actor")
    */
    private $countries;
    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieActor", mappedBy="actor")
    */
    private $movies;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Actor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Actor
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Actor
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Actor
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Actor
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
     * Add country
     *
     * @param \AppBundle\Entity\ActorCountry $country
     *
     * @return Actor
     */
    public function addCountry(\AppBundle\Entity\ActorCountry $country)
    {
        $this->countries[] = $country;

        return $this;
    }

    /**
     * Remove country
     *
     * @param \AppBundle\Entity\ActorCountry $country
     */
    public function removeCountry(\AppBundle\Entity\ActorCountry $country)
    {
        $this->countries->removeElement($country);
    }

    /**
     * Get countries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * Add movie
     *
     * @param \AppBundle\Entity\MovieActor $movie
     *
     * @return Actor
     */
    public function addMovie(\AppBundle\Entity\MovieActor $movie)
    {
        $this->movies[] = $movie;

        return $this;
    }

    /**
     * Remove movie
     *
     * @param \AppBundle\Entity\MovieActor $movie
     */
    public function removeMovie(\AppBundle\Entity\MovieActor $movie)
    {
        $this->movies->removeElement($movie);
    }

    /**
     * Get movies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovies()
    {
        return $this->movies;
    }
}
