<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Translatable;
/**
 * CatalogSectionCategory
 *
 * @ORM\Table(name="catalog_section_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CatalogSectionCategoryRepository")
 */
class CatalogSectionCategory implements Translatable
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
    * @var int
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="movie_nbr", type="integer")
    */
    private $movieNbr = 0;
    /**
    * @Gedmo\Locale
    * Used locale to override Translation listener`s locale
    * this is not a mapped field of entity metadata, just a simple property
    */
    private $locale;


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
    * @return CatalogSectionCategory
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
     * @return CatalogSectionCategory
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
     * @return CatalogSectionCategory
     */
    public function setMovieNbr($movieNbr)
    {
        $this->movieNbr = $movieNbr;

        return $this;
    }

    /**
     * Get movieNbr
     *
     * @return int
     */
    public function getMovieNbr()
    {
        return $this->movieNbr;
    }

    public function setTranslatableLocale($locale){
        $this->locale = $locale;
    }
}

