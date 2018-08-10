<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Metadata
 *
 * @ORM\Table(name="metadata")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MetadataRepository")
 */
class Metadata
{
    const STATUS_PREMODERATE = "pre-moderate";
    const STATUS_BAN = "ban";
    const STATUS_VALIDATED = "validated";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @var string
    *
    * @ORM\Column(name="file", type="string", length=255, unique=true, options={"comment":"le nom du fichier uploadé"})
    * @assert\File(maxSize="2Mi", mimeTypes="application/zip")
    */
    private $file;

    /**
    * @var integer
    *
    * @ORM\Column(name="size", type="integer",options={"comment":"la taille du fichier uploadé"}, nullable=true)
    */
    private $size;

    /**
    * @var string
    *
    * @ORM\Column(name="status", type="string", length=15, options={"comment":"le status de la moderation"}, columnDefinition="ENUM('ban','pre-moderate','approved')", nullable=true)
    */
    private $status;

    /**
    * @var \DateTime
    * @Gedmo\Timestampable(on="change", field="status", value="approved")    
    * @ORM\Column(name="moderate_at", type="datetime", options={"comment":"la date de moderation"}, nullable=true)
    */
    private $moderateAt;

    /**
    * @var \DateTime
    *
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime", options={"comment":"la date de l'upload"})
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
     * Set file
     *
     * @param string $file
     *
     * @return Metadata
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Metadata
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
     * Set size
     *
     * @param integer $size
     *
     * @return Metadata
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Metadata
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Metadata
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set moderateAt
     *
     * @param \DateTime $moderateAt
     *
     * @return Metadata
     */
    public function setModerateAt($moderateAt)
    {
        $this->moderateAt = $moderateAt;

        return $this;
    }

    /**
     * Get moderateAt
     *
     * @return \DateTime
     */
    public function getModerateAt()
    {
        return $this->moderateAt;
    }
}
