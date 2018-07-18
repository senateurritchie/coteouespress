<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WebsiteMail
 *
 * @ORM\Table(name="website_mail")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WebsiteMailRepository")
 */
class WebsiteMail
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
    * @var AppBundle\Entity\Department
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Department", inversedBy="mails")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $department;

    /**
    * @var AppBundle\Entity\WebsiteReferer
    *
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WebsiteReferer")
    * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
    */
    private $referer;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="veuillez renseigner ce champs")
     * @Assert\Length(min=3, max=30)
     * @ORM\Column(name="firstname", type="string", length=30)
     */
    private $firstname;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="veuillez renseigner ce champs")
     * @Assert\Length(min=3, max=30)
     * @ORM\Column(name="lastname", type="string", length=30)
     */
    private $lastname;

    /**
    * @var string
    *
    * @Assert\Email
    * @ORM\Column(name="email", type="string", length=254)
    */
    private $email;

    /**
    * @var string
    *
    * @Assert\NotBlank(message="veuillez renseigner ce champs")
    * @Assert\Length(min=3, max=50)
    * @ORM\Column(name="subject", type="string", length=50)
    */
    private $subject;

    /**
    * @var string
    *
    * @Assert\Length(max=50)
    * @ORM\Column(name="referer_message", type="string", length=50, nullable=true)
    */
    private $refererMessage;

    /**
    * @var string
    *
    * @Assert\NotBlank(message="veuillez renseigner ce champs")
    * @Assert\Length(min=3, max=500)
    * @ORM\Column(name="message", type="string", length=500)
    */
    private $message;

     /**
    * @var boolean
    *
    * @ORM\Column(name="is_processed", type="boolean")
    */
    private $isProcessed;

    /**
     * @var \DateTime
     *
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return WebsiteMail
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return WebsiteMail
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return WebsiteMail
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
     * Set subject
     *
     * @param string $subject
     *
     * @return WebsiteMail
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set refererMessage
     *
     * @param string $refererMessage
     *
     * @return WebsiteMail
     */
    public function setRefererMessage($refererMessage)
    {
        $this->refererMessage = $refererMessage;

        return $this;
    }

    /**
     * Get refererMessage
     *
     * @return string
     */
    public function getRefererMessage()
    {
        return $this->refererMessage;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return WebsiteMail
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return WebsiteMail
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
     * Set department
     *
     * @param \AppBundle\Entity\Department $department
     *
     * @return WebsiteMail
     */
    public function setDepartment(\AppBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \AppBundle\Entity\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set referer
     *
     * @param \AppBundle\Entity\WebsiteReferer $referer
     *
     * @return WebsiteMail
     */
    public function setReferer(\AppBundle\Entity\WebsiteReferer $referer = null)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * Get referer
     *
     * @return \AppBundle\Entity\WebsiteReferer
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Set isProcessed
     *
     * @param boolean $isProcessed
     *
     * @return WebsiteMail
     */
    public function setIsProcessed($isProcessed)
    {
        $this->isProcessed = $isProcessed;

        return $this;
    }

    /**
     * Get isProcessed
     *
     * @return boolean
     */
    public function getIsProcessed()
    {
        return $this->isProcessed;
    }
}
