<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * CatalogDownload
 *
 * @ORM\Table(name="catalog_download", options={"comment":"enregistre les téléchargements des catalogues qui sont fait dans l'espace client"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CatalogDownloadRepository")
 */
class CatalogDownload
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
    * @var AppBundle\Entity\CatalogStatic
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CatalogStatic", inversedBy="downloads")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $catalog;

    /**
    * @var AppBundle\Entity\Login
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Login", inversedBy="downloads")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $userSession;

    /**
    * @var \DateTime
    *
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime")
    */
    private $createAt;


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
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return CatalogDownload
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
     * Set catalog
     *
     * @param \AppBundle\Entity\CatalogStatic $catalog
     *
     * @return CatalogDownload
     */
    public function setCatalog(\AppBundle\Entity\CatalogStatic $catalog = null)
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catalog
     *
     * @return \AppBundle\Entity\CatalogStatic
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * Set userSession
     *
     * @param \AppBundle\Entity\Login $userSession
     *
     * @return CatalogDownload
     */
    public function setUserSession(\AppBundle\Entity\Login $userSession = null)
    {
        $this->userSession = $userSession;

        return $this;
    }

    /**
     * Get userSession
     *
     * @return \AppBundle\Entity\Login
     */
    public function getUserSession()
    {
        return $this->userSession;
    }
}
