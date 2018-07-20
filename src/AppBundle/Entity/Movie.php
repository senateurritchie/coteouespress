<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @var AppBundle\Entity\Category
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="movies")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $category;

    /**
    * @var AppBundle\Entity\Category
    *
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\MovieResource")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $resource;

    /**
    * @var AppBundle\Entity\MovieTrailer
    *
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\MovieTrailer")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $trailer;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

     /**
     * @var string
     *
     * @ORM\Column(name="original_name", type="string", length=50, nullable=true)
     */
    private $originalName;

    /**
     * @var string
     *
     * @ORM\Column(name="synopsis", type="text", nullable=true)
     */
    private $synopsis;

     /**
     * @var boolean
     *
     * @ORM\Column(name="in_theather", type="boolean", options={"comment":"valide si un programme est à l'affiche ou non"}, nullable=true)
     */
    private $in_theather = 0;

     /**
     * @var \Datetime
     *
     * @ORM\Column(name="year_start", type="datetime", options={"comment":"debut de l'année de production du film"}, nullable=true)
     */
    private $yearStart;

     /**
     * @var \Datetime
     *
     * @ORM\Column(name="year_end", type="datetime", options={"comment":"fin de l'année de production du film"}, nullable=true)
     */
    private $yearEnd;

     /**
     * @var integer
     *
     * @ORM\Column(name="episode_num", type="integer", options={"comment":"le nombre d'épisodes du movie"}, nullable=true)
     */
    private $episodeNum;

     /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", options={"comment":"enregistre la durée du film en minutes"}, nullable=true)
     */
    private $duration;

     /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", options={"comment":"enregistre le format du film"}, nullable=true)
     */
    private $format;

     /**
     * @var string
     *
     * @ORM\Column(name="mention", type="string", options={"comment":"determine si le movie est en HD, SD ou autre"}, columnDefinition="ENUM('HD','SD','4k','2k')", nullable=true)
     */
    private $mention = "HD";

    /**
     * @var string
     *
     * @ORM\Column(name="original_language", type="string", options={"comment":"la langue original du movie"},nullable=true)
     */
    private $originalLanguage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_exclusivity", type="boolean", options={"comment":"marque un movie comme etant à la une"},nullable=true)
     */
    private $hasExclusivity = 0;

    /**
    * @var string
    *
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=30, unique=true)
    */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;


    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieGenre", mappedBy="movie")
    */
    private $genres;
    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieCountry", mappedBy="movie")
    */
    private $countries;
    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieLanguage", mappedBy="movie")
    */
    private $languages;
    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieEpisode", mappedBy="movie")
    */
    private $episodes;
    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieActor", mappedBy="movie")
    */
    private $actors;
    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieSeason", mappedBy="movie")
    */
    private $seasons;
    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieReward", mappedBy="movie")
    */
    private $rewards;
    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieProducer", mappedBy="movie")
    */
    private $producers;
    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\MovieDirector", mappedBy="movie")
    */
    private $directors;
    /**
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
        $this->episodes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seasons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rewards = new \Doctrine\Common\Collections\ArrayCollection();
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
        $this->in_theather = $inTheather;

        return $this;
    }

    /**
     * Get inTheather
     *
     * @return boolean
     */
    public function getInTheather()
    {
        return $this->in_theather;
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
     * Set episodeNum
     *
     * @param integer $episodeNum
     *
     * @return Movie
     */
    public function setEpisodeNum($episodeNum)
    {
        $this->episodeNum = $episodeNum;

        return $this;
    }

    /**
     * Get episodeNum
     *
     * @return integer
     */
    public function getEpisodeNum()
    {
        return $this->episodeNum;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Movie
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
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
     * Set resource
     *
     * @param \AppBundle\Entity\MovieResource $resource
     *
     * @return Movie
     */
    public function setResource(\AppBundle\Entity\MovieResource $resource = null)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource
     *
     * @return \AppBundle\Entity\MovieResource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set trailer
     *
     * @param \AppBundle\Entity\MovieTrailer $trailer
     *
     * @return Movie
     */
    public function setTrailer(\AppBundle\Entity\MovieTrailer $trailer = null)
    {
        $this->trailer = $trailer;

        return $this;
    }

    /**
     * Get trailer
     *
     * @return \AppBundle\Entity\MovieTrailer
     */
    public function getTrailer()
    {
        return $this->trailer;
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
     * Add episode
     *
     * @param \AppBundle\Entity\MovieEpisode $episode
     *
     * @return Movie
     */
    public function addEpisode(\AppBundle\Entity\MovieEpisode $episode)
    {
        $this->episodes[] = $episode;

        return $this;
    }

    /**
     * Remove episode
     *
     * @param \AppBundle\Entity\MovieEpisode $episode
     */
    public function removeEpisode(\AppBundle\Entity\MovieEpisode $episode)
    {
        $this->episodes->removeElement($episode);
    }

    /**
     * Get episodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpisodes()
    {
        return $this->episodes;
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
     * Add season
     *
     * @param \AppBundle\Entity\MovieSeason $season
     *
     * @return Movie
     */
    public function addSeason(\AppBundle\Entity\MovieSeason $season)
    {
        $this->seasons[] = $season;

        return $this;
    }

    /**
     * Remove season
     *
     * @param \AppBundle\Entity\MovieSeason $season
     */
    public function removeSeason(\AppBundle\Entity\MovieSeason $season)
    {
        $this->seasons->removeElement($season);
    }

    /**
     * Get seasons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeasons()
    {
        return $this->seasons;
    }

    /**
     * Add reward
     *
     * @param \AppBundle\Entity\MovieReward $reward
     *
     * @return Movie
     */
    public function addReward(\AppBundle\Entity\MovieReward $reward)
    {
        $this->rewards[] = $reward;

        return $this;
    }

    /**
     * Remove reward
     *
     * @param \AppBundle\Entity\MovieReward $reward
     */
    public function removeReward(\AppBundle\Entity\MovieReward $reward)
    {
        $this->rewards->removeElement($reward);
    }

    /**
     * Get rewards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRewards()
    {
        return $this->rewards;
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
}
