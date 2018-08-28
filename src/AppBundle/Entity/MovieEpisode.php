<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * MovieEpisode
 *
 * @ORM\Table(name="movie_episode", options={"comment":"enregistre les episodes d'un movie"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieEpisodeRepository")
 */
class MovieEpisode
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
    * @var AppBundle\Entity\Movie
    *
    * @Groups({"group2"})
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="episodes")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $movie;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Translatable
    * @ORM\Column(name="title", type="string", length=100,nullable=true)
    */
    private $title;
    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Translatable
    * @ORM\Column(name="synopsis", type="text", nullable=true)
    */
    private $synopsis;
    /**
    * @var int
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="pos", type="integer",options={"comment":"le numéro de l'épisode du movie"}, nullable=true)
    */
    private $pos = 0;
    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="duration", type="string", length=10, options={"comment":"la durée de l'épisode du movie"}, nullable=true)
    */
    private $duration;
    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="full_url", type="string", length=255, options={"comment":"l'url d'accès à la video"},nullable=true)
    */
    private $fullUrl;
    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="image", type="string", length=255, nullable=true, options={"comment":"stock l'image de couverture de l'épisode"})
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=270,maxWidth=270, minHeight=360,maxHeight=360)
    */
    private $image;
    /**
    * @var boolean
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="is_published", type="boolean", options={"comment":"l'etat de publication de l'épisode"})
    */
    private $isPublished = 0;
    /**
    * @var \DateTime
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime", nullable=true)
    */
    private $createAt;
    /**
    * @var \DateTime
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Timestampable(on="update")
    * @ORM\Column(name="update_at", type="datetime", nullable=true)
    */
    private $updateAt;
    /**
    * @Gedmo\Locale
    * Used locale to override Translation listener`s locale
    * this is not a mapped field of entity metadata, just a simple property
    */
    private $locale;

    public function setTranslatableLocale($locale){
        $this->locale = $locale;
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
     * Set title
     *
     * @param string $title
     *
     * @return MovieEpisode
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set synopsis
     *
     * @param string $synopsis
     *
     * @return MovieEpisode
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
     * Set pos
     *
     * @param integer $pos
     *
     * @return MovieEpisode
     */
    public function setPos($pos)
    {
        $this->pos = $pos;

        return $this;
    }

    /**
     * Get pos
     *
     * @return integer
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return MovieEpisode
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
     * Set fullUrl
     *
     * @param string $fullUrl
     *
     * @return MovieEpisode
     */
    public function setFullUrl($fullUrl)
    {
        $this->fullUrl = $fullUrl;

        return $this;
    }

    /**
     * Get fullUrl
     *
     * @return string
     */
    public function getFullUrl()
    {
        return $this->fullUrl;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return MovieEpisode
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
     * Set isPublished
     *
     * @param boolean $isPublished
     *
     * @return MovieEpisode
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
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return MovieEpisode
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
     * Set updateAt
     *
     * @param \DateTime $updateAt
     *
     * @return MovieEpisode
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * Get updateAt
     *
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return MovieEpisode
     */
    public function setMovie(\AppBundle\Entity\Movie $movie = null)
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
