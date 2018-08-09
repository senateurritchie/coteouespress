<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * OriginalLanguage
 *
 * @ORM\Table(name="original_language")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OriginalLanguageRepository")
 */
class OriginalLanguage
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
    * @ORM\Column(name="name", type="string", length=100, unique=true)
    */
    private $name;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Translatable
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=100, unique=true)
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
    * @var \DateTime
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime")
    */
    private $createAt;

    /**
    * @Gedmo\Locale
    * Used locale to override Translation listener`s locale
    * this is not a mapped field of entity metadata, just a simple property
    */
    private $locale;

    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Movie", mappedBy="language")
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
     * @return OriginalLanguage
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
     * @return OriginalLanguage
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
     * Set movieNbr
     *
     * @param integer $movieNbr
     *
     * @return OriginalLanguage
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

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return OriginalLanguage
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
     * Add movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return OriginalLanguage
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
     * Get movies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovies()
    {
        return $this->movies;
    }

    public function setTranslatableLocale($locale){
        $this->locale = $locale;
    }
}