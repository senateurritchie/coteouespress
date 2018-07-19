<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use AppBundle\Form\UserType;
use AppBundle\Entity\Role;

class UserProfilType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('salt')->remove('createAt')->remove('password')->remove('email')->remove('roles');

        $builder
        ->add('username', TextType::class,array(
            "label"=>"Nom & Prénoms",
        ))
        
        ->add('aboutme', TextareaType::class,array(
            "label"=>"A propos",
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return UserType::class;
    }


}
