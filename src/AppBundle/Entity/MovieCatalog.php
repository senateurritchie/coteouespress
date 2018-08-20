<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * MovieCatalog
 *
 * @ORM\Table(name="movie_catalog")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MovieCatalogRepository")
 */
class MovieCatalog
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Movie", inversedBy="countries")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $movie;

    /**
    * @var AppBundle\Entity\CatalogType
    *
    * @Groups({"group2"})
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CatalogType", inversedBy="movies")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $catalog;

    /**
    * @var \DateTime
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime")
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
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return MovieCatalog
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
     * Set movie
     *
     * @param \AppBundle\Entity\Movie $movie
     *
     * @return MovieCatalog
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

    /**
     * Set catalog
     *
     * @param \AppBundle\Entity\CatalogType $catalog
     *
     * @return MovieCatalog
     */
    public function setCatalog(\AppBundle\Entity\CatalogType $catalog = null)
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catalog
     *
     * @return \AppBundle\Entity\CatalogType
     */
    public function getCatalog()
    {
        return $this->catalog;
    }
}
