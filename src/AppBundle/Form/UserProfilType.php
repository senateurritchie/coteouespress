<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\HttpFoundation\File\File;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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
        ->add('image',FileType::class,array(
            "required"=>false,
            "label"=>"Photo",
            "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->add('aboutme', TextareaType::class,array(
            "label"=>"A propos",
        ))
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options){
            $model = $event->getData();
            $form = $event->getForm();

            if (!$model) {
                return;
            } 

            if($model->getImage()){
                $path = $options['upload_dir'].'/'.$model->getImage();
                $model->setImage(new File($path));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return UserType::class;
    }

}
