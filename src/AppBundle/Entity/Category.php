<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
* Category
*
* @ORM\Table(name="category", options={"comment":"enregistre les catégories des movies"})
* @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
* @UniqueEntity("name", message="le nom de catégorie doit être unique")
*/
class Category
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
    * @ORM\Column(name="slug", type="string", length=60, unique=true,nullable=true)
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
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Movie", mappedBy="category")
    * @ORM\OrderBy({"createdAt" = "DESC"})
    */
    private $movies;

    /**
    * @Gedmo\Locale
    * Used locale to override Translation listener`s locale
    * this is not a mapped field of entity metadata, just a simple property
    */
    private $locale;

     /**
     * Constructor
     */
    public function __construct()
    {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setTranslatableLocale($locale){
        $this->locale = $locale;
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
     * @return Category
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
     * @return Category
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
     * Set movies
     *
     * @param \AppBundle\Entity\Movie $movies
     *
     * @return Category
     */
    public function setMovies(\AppBundle\Entity\Movie $movies = null)
    {
        $this->movies = $movies;

        return $this;
    }

    /**
     * Get movies
     *
     * @return \AppBundle\Entity\Movie
     */
    public function getMovies()
    {
        return $this->movies;
    }
   

    /**
     * Add movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return Category
     */
    public function addMovie(\AppBundle\Entity\Movie $movie)
    {
        $this->movies[] = $movie;

        return $this;
    }

    /**
     * Remove movie
     *
     * @param \AppBundle\Entity\Movie $movie
     */
    public function removeMovie(\AppBundle\Entity\Movie $movie)
    {
        $this->movies->removeElement($movie);
    }

    /**
     * Set movieNbr
     *
     * @param integer $movieNbr
     *
     * @return Category
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
