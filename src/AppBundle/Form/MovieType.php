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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Movie;
use AppBundle\Entity\Category;
use AppBundle\Form\ResourceType;

class MovieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name',TextType::class,array(
            "attr"=>["placeholder"=>"Nom du programme","class"=>"input-sm"]
        ))
        ->add('originalName',TextType::class,array(
            "attr"=>["placeholder"=>"Nom original du programme","class"=>"input-sm"]
        ))
        ->add('synopsis',TextareaType::class,array(
            "attr"=>["placeholder"=>"Description du programme","class"=>"input-sm"]
        ))
        ->add('inTheather',CheckboxType::class,array(
            "label"=>"Programme à l'affiche"
        ))
        ->add('format',TextType::class,array(
            "attr"=>["placeholder"=>"Format","class"=>"input-sm"]
        ))
        ->add('trailer',TextType::class,array(
            "attr"=>["placeholder"=>"Trailer du programme","class"=>"input-sm"]
        ))
        ->add('year_start',DateType::class,array(
            "attr"=>["placeholder"=>"Debut d'année de production","class"=>"input-sm"]
        ))
        ->add('year_end',DateType::class,array(
            "attr"=>["placeholder"=>"Fin d'année de production","class"=>"input-sm"]
        ))
        ->add('mention',ChoiceType::class,array(
            "placeholder"=>"Définition",
            "choices"=>[
                "HD"=>"HD",
                "SD"=>"SD",
                "4k"=>"4k",
                "2k"=>"2k",
            ]
        ))
        ->add('originalLanguage',LanguageType::class,array(
            "placeholder"=>"version original",
        ))
        ->add('hasExclusivity',CheckboxType::class,array(
            "label"=>"Production à blébiciter",
        ))
        ->add('category',EntityType::class,array(
            "placeholder"=>"Catégorie",
            "class"=>Category::class,
            "choice_label"=>function($item,$key,$index){
                return $item->getName();
            }
        ))
        ->add('cover_img',FileType::class,array(
            "required"=>false,
             "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->add('landscape_img',FileType::class,array(
            "required"=>false,
             "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->add('portrait_img',FileType::class,array(
            "required"=>false,
             "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options){
            $movie = $event->getData();
            $form = $event->getForm();

            if (!$trailer) {
                return;
            }

            if(@$options["use_for"] == "upload"){
                $form
                ->remove("name")
                ->remove('originalName')
                ->remove('synopsis')
                ->remove('inTheather')
                ->remove('format')
                ->remove('trailer')
                ->remove('year_start')
                ->remove('year_end')
                ->remove('mention')
                ->remove('originalLanguage')
                ->remove('hasExclusivity')
                ->remove('category')
                ->remove('originalLanguage');
            }

            if($trailer->getImage()){
                $path = $options['upload_dir'].'/'.$movie->getCoverImg();
                $movie->setCoverImg(new File($path));

                $path = $options['upload_dir'].'/'.$movie->getLandscapeImg();
                $movie->setLandscapeImg(new File($path));

                $path = $options['upload_dir'].'/'.$movie->getPortraitImg();
                $movie->setPortraitImg(new File($path));
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
            'data_class' => Movie::class,
            'use_for' => "normal",
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
