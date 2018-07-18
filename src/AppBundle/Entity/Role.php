<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
* Role
*
* @ORM\Table(name="role", options={"comment":"Definition de tout les roles et privileges"})
* @ORM\Entity(repositoryClass="AppBundle\Repository\RoleRepository")
* @UniqueEntity("name", message="ce nom est déja enregistrée")
* @UniqueEntity("label", message="ce label est déja enregistrée")
*/
class Role
{
    /**
    * @var int
    *
    * @Groups({"group1"})
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
    * @Assert\NotBlank
     * @Assert\Length(min=3, max=30)
     * @ORM\Column(name="name", type="string", length=30, unique=true, options={"comment":"le nom court du role ex: Traducteur"})
    * @Groups({"group1"})
     */
    private $name;

     /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(min=3, max=30)
     * @ORM\Column(name="label", type="string", length=30, unique=true, options={"comment":"le label reference du role ex: ROLE_TRANSLATOR"})
    * @Groups({"group1"})
     */
    private $label;

    /**
    * @var string
    *
    * @Assert\Choice({"role","privilege"})
    * @ORM\Column(name="type", type="string", length=30, columnDefinition="ENUM('role','privilege')", nullable=true, options={"comment":"le type fait la difference entre un role et un privilège"})
    * @Groups({"group1"})
    */
    private $type;


    /**
    * @var string
    *
    * @Assert\NotBlank
    * @Assert\Length(min=3, max=250)
    * @ORM\Column(name="description", type="string", length=250, options={"comment":"courte description de ce a quoi sert ce role"})
    * @Groups({"group1"})
    */
    private $description;

   
    /**
     * @var \DateTime
     *
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
     * Set name
     *
     * @param string $name
     *
     * @return Role
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
     * Set description
     *
     * @param string $description
     *
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Role
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Role
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
     * Set type
     *
     * @param string $type
     *
     * @return Role
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
