<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Department
 *
 * @ORM\Table(name="department")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepartmentRepository")
 * @UniqueEntity("name", message="le nom du departement doit être unique")
 */
class Department
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
    * @ORM\Column(name="name", type="string", length=30, unique=true)
    */
    private $name;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Slug(fields={"name"})
    * @ORM\Column(name="slug", type="string", length=60, unique=true, nullable=true)
    */
    private $slug;

    /**
    * @var string
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="email", type="string", length=254,nullable=true)
    */
    private $email;

    /**
    * @var integer
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="email_received", type="integer")
    */
    private $emailReceived = 0;

    /**
    * @var integer
    *
    * @Groups({"group1","group2"})
    * @ORM\Column(name="email_processed", type="integer")
    */
    private $emailProcessed = 0;

    /**
    * @var \DateTime
    *
    * @Groups({"group1","group2"})
    * @Gedmo\Timestampable(on="create")
    * @ORM\Column(name="create_at", type="datetime")
    */
    private $createAt;

    /**
    * @var AppBundle\Entity\WebsiteMail
    *
    * @Groups({"group2"})
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\WebsiteMail", mappedBy="department")
    */
    private $mails;


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
     * @return Department
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
     * @return Department
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
     * @return Department
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
     * Constructor
     */
    public function __construct()
    {
        $this->mails = new \Doctrine\Common\Collections\ArrayCollection();
        $this->emailProcessed = 0;
        $this->emailReceived = 0;
    }

    /**
     * Add mail
     *
     * @param \AppBundle\Entity\WebsiteMail $mail
     *
     * @return Department
     */
    public function addMail(\AppBundle\Entity\WebsiteMail $mail)
    {
        $this->mails[] = $mail;

        return $this;
    }

    /**
     * Remove mail
     *
     * @param \AppBundle\Entity\WebsiteMail $mail
     */
    public function removeMail(\AppBundle\Entity\WebsiteMail $mail)
    {
        $this->mails->removeElement($mail);
    }

    /**
     * Get mails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMails()
    {
        return $this->mails;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Department
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailReceived
     *
     * @param integer $emailReceived
     *
     * @return Department
     */
    public function setEmailReceived($emailReceived)
    {
        $this->emailReceived = $emailReceived;

        return $this;
    }

    /**
     * Get emailReceived
     *
     * @return integer
     */
    public function getEmailReceived()
    {
        return $this->emailReceived;
    }

    /**
     * Set emailProcessed
     *
     * @param integer $emailProcessed
     *
     * @return Department
     */
    public function setEmailProcessed($emailProcessed)
    {
        $this->emailProcessed = $emailProcessed;

        return $this;
    }

    /**
     * Get emailProcessed
     *
     * @return integer
     */
    public function getEmailProcessed()
    {
        return $this->emailProcessed;
    }
}
