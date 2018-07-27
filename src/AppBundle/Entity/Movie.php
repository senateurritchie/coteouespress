<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Movie
 *
 * @ORM\Table(name="movie", options={"comment":"enregistre les programmes ou movies"}, indexes={@ORM\Index(columns={"slug","synopsis"},flags={"fulltext"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieRepository")
 */
class Movie
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
    * @var AppBundle\Entity\Category
    *
    * @Groups({"group1","group2"})
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="movies")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $category;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Assert\NotBlank
    * @Assert\Length(min=3, max=50)
    * @ORM\Column(name="name", type="string", length=50, unique=true)
    */
    private $name;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=30, unique=true)
    */
    private $slug;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="original_name", type="string", length=50, nullable=true)
    */
    private $originalName;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="synopsis", type="text", nullable=true)
    */
    private $synopsis;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="reward", type="text", nullable=true,options={"comment":"enregistre les recompenses obtenues du movie"})
    */
    private $reward;
    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="award", type="text", nullable=true,options={"comment":"enregistre les prix et les nominations du movie"})
    */
    private $award;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="audience", type="text", nullable=true,options={"comment":"enregistre les audiences du movie"})
    */
    private $audience;

    /**
    * @var boolean
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="in_theather", type="boolean", options={"comment":"valide si un programme est à l'affiche ou non"}, nullable=true)
    */
    private $inTheather = 0;

    /**
    * @var boolean
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="has_exclusivity", type="boolean", options={"comment":"marque un movie comme etant à la une"},nullable=true)
    */
    private $hasExclusivity = 0;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="format", type="string", options={"comment":"enregistre le format du film"}, nullable=true)
    */
    private $format;

    /**
    * @var \Datetime
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="year_start", type="datetime", options={"comment":"debut de l'année de production du film"}, nullable=true)
    */
    private $yearStart;

    /**
    * @var \Datetime
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="year_end", type="datetime", options={"comment":"fin de l'année de production du film"}, nullable=true)
    */
    private $yearEnd;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="mention", type="string", options={"comment":"determine si le movie est en HD, SD ou autre"}, columnDefinition="ENUM('HD','SD','4k','2k')", nullable=true)
    */
    private $mention = "HD";

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="original_language", type="string", options={"comment":"la langue original du movie"},nullable=true)
    */
    private $originalLanguage;

   /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="cover_img", type="string", length=255, nullable=true, options={"comment":"stock l'image de couverture du programme"})
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=1920,maxWidth=1920, minHeight=1080,maxHeight=1080)
    */
    private $coverImg;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="landscape_img", type="string", length=255, nullable=true, options={"comment":"stock la vignette en paysage du programme"})
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=640,maxWidth=640, minHeight=360,maxHeight=360)
    */
    private $landscapeImg;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="portrait_img", type="string", length=255, nullable=true,options={"comment":"stock la vignette en portrait du programme"})
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=270,maxWidth=270, minHeight=360,maxHeight=360)
    */
    private $portraitImg;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="trailer", type="string", length=255, nullable=true, options={"comment":"le lien du trailer du programme"})
    * @assert\Url
    */
    private $trailer;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="episode_1", type="string", length=255, nullable=true, options={"comment":"le lien de l'episode 1 du programme"})
    * @assert\Url
    */
    private $episode1;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="episode_2", type="string", length=255, nullable=true, options={"comment":"le lien de l'episode 2 du programme"})
    * @assert\Url
    */
    private $episode2;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="episode_3", type="string", length=255, nullable=true, options={"comment":"le lien de l'episode 3 du programme"})
    * @assert\Url
    */
    private $episode3;


    /**
    * @var boolean
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="is_published", type="boolean", options={"comment":"l'etat de publication du programme"})
    */
    private $isPublished = 0;


    /**
    * @var \DateTime
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="create_at", type="datetime", nullable=true)
    */
    private $createAt;

    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieGenre", mappedBy="movie")
    */
    private $genres;
    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieCountry", mappedBy="movie")
    */
    private $countries;
    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieLanguage", mappedBy="movie")
    */
    private $languages;
    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieActor", mappedBy="movie")
    */
    private $actors;
    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieProducer", mappedBy="movie")
    */
    private $producers;
    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieDirector", mappedBy="movie")
    */
    private $directors;
    /**
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieScene", mappedBy="movie")
    */
    private $scenes;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->languages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->producers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->directors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->scenes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Movie
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
     * Set originalName
     *
     * @param string $originalName
     *
     * @return Movie
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set synopsis
     *
     * @param string $synopsis
     *
     * @return Movie
     */
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    /**
     * Get synopsis
     *
     * @return string
     */
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * Set inTheather
     *
     * @param boolean $inTheather
     *
     * @return Movie
     */
    public function setInTheather($inTheather)
    {
        $this->inTheather = $inTheather;

        return $this;
    }

    /**
     * Get inTheather
     *
     * @return boolean
     */
    public function getInTheather()
    {
        return $this->inTheather;
    }

    /**
     * Set yearStart
     *
     * @param \DateTime $yearStart
     *
     * @return Movie
     */
    public function setYearStart($yearStart)
    {
        $this->yearStart = $yearStart;

        return $this;
    }

    /**
     * Get yearStart
     *
     * @return \DateTime
     */
    public function getYearStart()
    {
        return $this->yearStart;
    }

    /**
     * Set yearEnd
     *
     * @param \DateTime $yearEnd
     *
     * @return Movie
     */
    public function setYearEnd($yearEnd)
    {
        $this->yearEnd = $yearEnd;

        return $this;
    }

    /**
     * Get yearEnd
     *
     * @return \DateTime
     */
    public function getYearEnd()
    {
        return $this->yearEnd;
    }

    /**
     * Set format
     *
     * @param string $format
     *
     * @return Movie
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set mention
     *
     * @param string $mention
     *
     * @return Movie
     */
    public function setMention($mention)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * Get mention
     *
     * @return string
     */
    public function getMention()
    {
        return $this->mention;
    }

    /**
     * Set originalLanguage
     *
     * @param string $originalLanguage
     *
     * @return Movie
     */
    public function setOriginalLanguage($originalLanguage)
    {
        $this->originalLanguage = $originalLanguage;

        return $this;
    }

    /**
     * Get originalLanguage
     *
     * @return string
     */
    public function getOriginalLanguage()
    {
        return $this->originalLanguage;
    }

    /**
     * Set hasExclusivity
     *
     * @param boolean $hasExclusivity
     *
     * @return Movie
     */
    public function setHasExclusivity($hasExclusivity)
    {
        $this->hasExclusivity = $hasExclusivity;

        return $this;
    }

    /**
     * Get hasExclusivity
     *
     * @return boolean
     */
    public function getHasExclusivity()
    {
        return $this->hasExclusivity;
    }

    /**
     * Set coverImg
     *
     * @param string $coverImg
     *
     * @return Movie
     */
    public function setCoverImg($coverImg)
    {
        $this->coverImg = $coverImg;

        return $this;
    }

    /**
     * Get coverImg
     *
     * @return string
     */
    public function getCoverImg()
    {
        return $this->coverImg;
    }

    /**
     * Set landscapeImg
     *
     * @param string $landscapeImg
     *
     * @return Movie
     */
    public function setLandscapeImg($landscapeImg)
    {
        $this->landscapeImg = $landscapeImg;

        return $this;
    }

    /**
     * Get landscapeImg
     *
     * @return string
     */
    public function getLandscapeImg()
    {
        return $this->landscapeImg;
    }

    /**
     * Set portraitImg
     *
     * @param string $portraitImg
     *
     * @return Movie
     */
    public function setPortraitImg($portraitImg)
    {
        $this->portraitImg = $portraitImg;

        return $this;
    }

    /**
     * Get portraitImg
     *
     * @return string
     */
    public function getPortraitImg()
    {
        return $this->portraitImg;
    }

    /**
     * Set trailer
     *
     * @param string $trailer
     *
     * @return Movie
     */
    public function setTrailer($trailer)
    {
        $this->trailer = $trailer;

        return $this;
    }

    /**
     * Get trailer
     *
     * @return string
     */
    public function getTrailer()
    {
        return $this->trailer;
    }

    /**
     * Set episode1
     *
     * @param string $episode1
     *
     * @return Movie
     */
    public function setEpisode1($episode1)
    {
        $this->episode1 = $episode1;

        return $this;
    }

    /**
     * Get episode1
     *
     * @return string
     */
    public function getEpisode1()
    {
        return $this->episode1;
    }

    /**
     * Set episode2
     *
     * @param string $episode2
     *
     * @return Movie
     */
    public function setEpisode2($episode2)
    {
        $this->episode2 = $episode2;

        return $this;
    }

    /**
     * Get episode2
     *
     * @return string
     */
    public function getEpisode2()
    {
        return $this->episode2;
    }

    /**
     * Set episode3
     *
     * @param string $episode3
     *
     * @return Movie
     */
    public function setEpisode3($episode3)
    {
        $this->episode3 = $episode3;

        return $this;
    }

    /**
     * Get episode3
     *
     * @return string
     */
    public function getEpisode3()
    {
        return $this->episode3;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Movie
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
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Movie
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
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Movie
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add genre
     *
     * @param \AppBundle\Entity\MovieGenre $genre
     *
     * @return Movie
     */
    public function addGenre(\AppBundle\Entity\MovieGenre $genre)
    {
        $this->genres[] = $genre;

        return $this;
    }

    /**
     * Remove genre
     *
     * @param \AppBundle\Entity\MovieGenre $genre
     */
    public function removeGenre(\AppBundle\Entity\MovieGenre $genre)
    {
        $this->genres->removeElement($genre);
    }

    /**
     * Get genres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGenres()
    {
        return $this->genres;
    }

    /**
     * Add country
     *
     * @param \AppBundle\Entity\MovieCountry $country
     *
     * @return Movie
     */
    public function addCountry(\AppBundle\Entity\MovieCountry $country)
    {
        $this->countries[] = $country;

        return $this;
    }

    /**
     * Remove country
     *
     * @param \AppBundle\Entity\MovieCountry $country
     */
    public function removeCountry(\AppBundle\Entity\MovieCountry $country)
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
     * Add language
     *
     * @param \AppBundle\Entity\MovieLanguage $language
     *
     * @return Movie
     */
    public function addLanguage(\AppBundle\Entity\MovieLanguage $language)
    {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * Remove language
     *
     * @param \AppBundle\Entity\MovieLanguage $language
     */
    public function removeLanguage(\AppBundle\Entity\MovieLanguage $language)
    {
        $this->languages->removeElement($language);
    }

    /**
     * Get languages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Add actor
     *
     * @param \AppBundle\Entity\MovieActor $actor
     *
     * @return Movie
     */
    public function addActor(\AppBundle\Entity\MovieActor $actor)
    {
        $this->actors[] = $actor;

        return $this;
    }

    /**
     * Remove actor
     *
     * @param \AppBundle\Entity\MovieActor $actor
     */
    public function removeActor(\AppBundle\Entity\MovieActor $actor)
    {
        $this->actors->removeElement($actor);
    }

    /**
     * Get actors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActors()
    {
        return $this->actors;
    }

    


    /**
     * Add producer
     *
     * @param \AppBundle\Entity\MovieProducer $producer
     *
     * @return Movie
     */
    public function addProducer(\AppBundle\Entity\MovieProducer $producer)
    {
        $this->producers[] = $producer;

        return $this;
    }

    /**
     * Remove producer
     *
     * @param \AppBundle\Entity\MovieProducer $producer
     */
    public function removeProducer(\AppBundle\Entity\MovieProducer $producer)
    {
        $this->producers->removeElement($producer);
    }

    /**
     * Get producers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducers()
    {
        return $this->producers;
    }

    /**
     * Add director
     *
     * @param \AppBundle\Entity\MovieDirector $director
     *
     * @return Movie
     */
    public function addDirector(\AppBundle\Entity\MovieDirector $director)
    {
        $this->directors[] = $director;

        return $this;
    }

    /**
     * Remove director
     *
     * @param \AppBundle\Entity\MovieDirector $director
     */
    public function removeDirector(\AppBundle\Entity\MovieDirector $director)
    {
        $this->directors->removeElement($director);
    }

    /**
     * Get directors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDirectors()
    {
        return $this->directors;
    }

    /**
     * Add scene
     *
     * @param \AppBundle\Entity\MovieScene $scene
     *
     * @return Movie
     */
    public function addScene(\AppBundle\Entity\MovieScene $scene)
    {
        $this->scenes[] = $scene;

        return $this;
    }

    /**
     * Remove scene
     *
     * @param \AppBundle\Entity\MovieScene $scene
     */
    public function removeScene(\AppBundle\Entity\MovieScene $scene)
    {
        $this->scenes->removeElement($scene);
    }

    /**
     * Get scenes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScenes()
    {
        return $this->scenes;
    }

    /**
     * Set isPublished
     *
     * @param boolean $isPublished
     *
     * @return Movie
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
     * Set reward
     *
     * @param string $reward
     *
     * @return Movie
     */
    public function setReward($reward)
    {
        $this->reward = $reward;

        return $this;
    }

    /**
     * Get reward
     *
     * @return string
     */
    public function getReward()
    {
        return $this->reward;
    }

    /**
     * Set award
     *
     * @param string $award
     *
     * @return Movie
     */
    public function setAward($award)
    {
        $this->award = $award;

        return $this;
    }

    /**
     * Get award
     *
     * @return string
     */
    public function getAward()
    {
        return $this->award;
    }

    /**
     * Set audience
     *
     * @param string $audience
     *
     * @return Movie
     */
    public function setAudience($audience)
    {
        $this->audience = $audience;

        return $this;
    }

    /**
     * Get audience
     *
     * @return string
     */
    public function getAudience()
    {
        return $this->audience;
    }
}
