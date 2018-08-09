<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Translatable\Translatable;

/**
* Genre
*
* @ORM\Table(name="genre",options={"comment":"enregistre les genres d'un movie"})
* @ORM\Entity(repositoryClass="AppBundle\Repository\GenreRepository")
* @UniqueEntity("name", message="le nom du genre doit être unique")
*/
class Genre
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
    * @Gedmo\Translatable
    * @ORM\Column(name="name", type="string", length=30, unique=true)
    */
    private $name;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Translatable
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=60, unique=true, nullable=true)
    */
    private $slug;

    /**
    * @var integer
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="movie_nbr", type="integer")
    */
    private $movieNbr = 0;

    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieGenre", mappedBy="genre")
    */
    private $movies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     *
     * @return Genre
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Genre
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
     * Add movie
     *
     * @param \AppBundle\Entity\MovieGenre $movie
     *
     * @return Genre
     */
    public function addMovie(\AppBundle\Entity\MovieGenre $movie)
    {
        $this->movies[] = $movie;

        return $this;
    }

    /**
     * Remove movie
     *
     * @param \AppBundle\Entity\MovieGenre $movie
     */
    public function removeMovie(\AppBundle\Entity\MovieGenre $movie)
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

    /**
     * Set movieNbr
     *
     * @param integer $movieNbr
     *
     * @return Genre
     */
    public function setMovieNbr($movieNbr)
    {
        $this->movieNbr = $movieNbr;

        return $this;
    }

    /**
     * Get movieNbr
     *
     * @return integer
     */
    public function getMovieNbr()
    {
        return $this->movieNbr;
    }
}
