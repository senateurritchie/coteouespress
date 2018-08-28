<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use AppBundle\Entity\MovieEpisode;

class MovieEpisodeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',TextType::class,array(
            "label"=>false,
            "attr"=>["placeholder"=>"Titre","class"=>"input-sm"]
        ))
        ->add('synopsis',TextareaType::class,array(
            "label"=>false,
            "attr"=>["placeholder"=>"Synopsis","class"=>"input-sm"],
            "required"=>false,
        ))
        ->add('fullUrl',UrlType::class,array(
            "attr"=>["placeholder"=>"Lien de visionnage vimeo","class"=>"input-sm"],
            "label"=>false,
            "required"=>false,
        ))
        ->add('image',FileType::class,array(
            "required"=>false,
            "label"=>false,
            "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->add('duration',TextType::class,array(
            "label"=>false,
            "attr"=>["placeholder"=>"Durée HH:MM:SS","class"=>"input-sm text-center"],
        ))
        ->add('button',ButtonType::class,array(
            "attr"=>["class"=>"btn-sm delete pull-left btn btn-danger"],
            "label"=>"supprimer"
        ))
        
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options,&$builder){
            $model = $event->getData();
            $form = $event->getForm();

            if (!$model) {
                return;
            }   //

            if(@$options["use_for"] == "update"){

                
            }


            if($model->getImage()){
                $path = $options['upload_dir'].'/'.basename($model->getImage());
                $model->setImage(new File($path));
            }
        });
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setRequired('upload_dir');

        $resolver->setDefaults(array(
            'data_class' => MovieEpisode::class,
            'use_for' => "normal",
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(){
        return null;
    }
}
