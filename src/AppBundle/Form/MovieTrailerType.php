<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\MovieTrailer;

class MovieTrailerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',TextType::class,array(
            "attr"=>["placeholder"=>"Titre du trailer","class"=>"input-sm"]
        ))
        ->add('fullUrl',TextType::class,array(
            "attr"=>["placeholder"=>"Lien de la video","class"=>"input-sm"]
        ))
         ->add('image',FileType::class,array(
            "required"=>false,
             "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options){
            $trailer = $event->getData();
            $form = $event->getForm();

            if (!$trailer) {
                return;
            }

            if($trailer->getImage()){
                $path = $options['upload_dir'].'/'.$trailer->getImage();
                $trailer->setImage(new File($path));
            }
        });
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('upload_dir');

        $resolver->setDefaults(array(
            'data_class' => MovieTrailer::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }


}
