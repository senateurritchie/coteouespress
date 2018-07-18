<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


use AppBundle\Entity\Role;

class RoleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name',TextType::class,array(
            "attr"=>["placeholder"=>"Court nom exemple: api","class"=>"input-sm"]
        ))
        ->add('label',TextType::class,array(
            "attr"=>["class"=>"input-sm","placeholder"=>"Reférence exemple: ROLE_API"]
        ))
        ->add('type',ChoiceType::class,array(
            "label"=>"Nature",
            "choices"=>array(
                "Rôle"=>"role",
                "Privilège"=>"privilege"
            ),
            "attr"=>["class"=>"input-sm"]
        ))
        ->add('description',TextareaType::class,array(
            "label"=>"Courte description",
            "attr"=>[
                "resize"=>"none",

            ]
        ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Role::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_role';
    }


}
