<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MovieTrailer
 *
 * @ORM\Table(name="movie_trailer", options={"comment":"stocke le trailer d'un movie"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieTrailerRepository")
 */
class MovieTrailer
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
    * @ORM\Column(name="image", type="string", length=255, nullable=true)
    * @assert\Image(mimeTypes={"image/jpg","image/jpeg","image/png"},minWidth=270,maxWidth=270, minHeight=360,maxHeight=360)
    */
    private $image;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="title", type="string", length=254, nullable=true, options={"comment":"enregistre le titre ou du trailer"})
    */
    private $title;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Slug(fields={"title"})
    * @ORM\Column(name="slug", type="string", length=30, unique=true, nullable=true)
    */
    private $slug;


    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @assert\Url()
    * @ORM\Column(name="full_url", type="string", length=254, options={"comment":"url absolue du trailer"},nullable=true)
    */
    private $fullUrl;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="code_url", type="string", length=50, options={"comment":"code (id) de la video sur vimeo ou youtube"}, nullable=true)
    */
    private $codeUrl;
    /**
    * @var \DateTime
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="create_at", type="datetime", options={"comment":"date d'ajout du trailer sur la plateforme"})
    */
    private $createAt;

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
     * Set image
     *
     * @param string $image
     *
     * @return MovieTrailer
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
     * Set title
     *
     * @param string $title
     *
     * @return MovieTrailer
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
     * Set slug
     *
     * @param string $slug
     *
     * @return MovieTrailer
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
     * Set fullUrl
     *
     * @param string $fullUrl
     *
     * @return MovieTrailer
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
     * Set codeUrl
     *
     * @param string $codeUrl
     *
     * @return MovieTrailer
     */
    public function setCodeUrl($codeUrl)
    {
        $this->codeUrl = $codeUrl;

        return $this;
    }

    /**
     * Get codeUrl
     *
     * @return string
     */
    public function getCodeUrl()
    {
        return $this->codeUrl;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return MovieTrailer
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
}
