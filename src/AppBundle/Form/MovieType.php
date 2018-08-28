<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Movie;
use AppBundle\Entity\Actor;
use AppBundle\Entity\Director;
use AppBundle\Entity\Producer;
use AppBundle\Entity\Category;
use AppBundle\Entity\Language;
use AppBundle\Entity\OriginalLanguage;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Country;

use AppBundle\Form\ResourceType;
use AppBundle\Form\MovieEpisodeType;

class MovieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $languages = \Symfony\Component\Intl\Intl::getLanguageBundle()->getLanguageNames();

        $builder
        ->add('name',TextType::class,array(
            "attr"=>["placeholder"=>"Nom du programme","class"=>"input-sm"]
        ))
        ->add('category',EntityType::class,array(
            "placeholder"=>"Catégorie...",
            "label"=>"Catégorie",
            "class"=>Category::class,
            "choice_label"=>function($item,$key,$index){
                return $item->getName();
            }
        ))
        ->add('originalName',TextType::class,array(
            "attr"=>["placeholder"=>"Nom original du programme","class"=>"input-sm"],
            "required"=>false,
        ))
        ->add('synopsis',TextareaType::class,array(
            "attr"=>["placeholder"=>"A propos du programme","class"=>"input-sm","rows"=>10],
            "required"=>false,
        ))
        ->add('tagline',TextareaType::class,array(
            "attr"=>["placeholder"=>"Tagline du programme","class"=>"input-sm","rows"=>3],
            "required"=>false,
        ))
        ->add('logline',TextareaType::class,array(
            "attr"=>["placeholder"=>"Logline du programme","class"=>"input-sm","rows"=>3],
            "required"=>false,
        ))
        ->add('reward',TextareaType::class,array(
            "attr"=>["placeholder"=>"A propos ds recompences...","class"=>"input-sm","rows"=>5],
            "label"=>"Les recompences du programme",
            "required"=>false,
        ))
        ->add('award',TextareaType::class,array(
            "attr"=>["placeholder"=>"A propos des prix et nominations...","class"=>"input-sm","rows"=>5],
            "label"=>"Les Prix et le Nominations",
            "required"=>false,
        ))
        ->add('audience',TextareaType::class,array(
            "attr"=>["placeholder"=>"A propos des audiences...","class"=>"input-sm","rows"=>5],
            "label"=>"Les Audiences du programme",
            "required"=>false,
        ))
        ->add('inTheather',CheckboxType::class,array(
            "label"=>"Programme à l'affiche",
            "required"=>false,
        ))
        ->add('hasExclusivity',CheckboxType::class,array(
            "label"=>"Production à plébiciter",
            "required"=>false,
        ))
        ->add('isPublished',CheckboxType::class,array(
            "label"=>"Publier directement",
            "required"=>false,
        ))
        ->add('format',TextType::class,array(
            "attr"=>["placeholder"=>"Format","class"=>"input-sm"],
            "required"=>false,
        ))
        ->add('year_start',DateType::class,array(
            "attr"=>["class"=>"input-sm"],
            "widget"=>"single_text",
            "widget"=>"single_text",
            "label"=>"Debut de production",
            "required"=>false,
        ))
        ->add('year_end',DateType::class,array(
            "attr"=>["class"=>"input-sm"],
            "widget"=>"single_text",
            "label"=>"Fin de production",
            "required"=>false,
        ))
        ->add('mention',ChoiceType::class,array(
            "attr"=>["class"=>"input-sm"],
            "placeholder"=>"Resolution...",
            "choices"=>[
                "HD"=>"HD",
                "SD"=>"SD",
                "4k"=>"4k",
                "2k"=>"2k",
            ],
            "required"=>false,
        ))
        ->add('state',ChoiceType::class,array(
            "required"=>false,
            "expanded"=>true,
            "placeholder"=>"aucun status",
            "label"=>"Status du programme",
            "choices"=>[
                "En attente"=>"pre-moderate",
                "Rejeté"=>"rejected",
                "Approuvé"=>"approved",
                "En modération"=>"workflow",
                "Désactivé"=>"deactivated",
            ],
        ))
        ->add('language',EntityType::class,array(
            "class"=>OriginalLanguage::class,
            "placeholder"=>"version original...",
            "required"=>false,
            "choice_label"=>function($item,$key,$index){
                return $item->getName();
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },
        ))
        ->add('coverImg',FileType::class,array(
            "required"=>false,
            "label"=>"Image de couverture",
            "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->add('landscapeImg',FileType::class,array(
            "required"=>false,
            "label"=>"Vignette paysage",
            "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->add('portraitImg',FileType::class,array(
            "required"=>false,
            "label"=>"Vignette portrait",
            "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->add('trailer',TextType::class,array(
            "attr"=>["placeholder"=>"Lien de visionnage du trailer","class"=>"input-sm"],
            "required"=>false,
        ))
        ->add('episode1',TextType::class,array(
            "attr"=>["placeholder"=>"Lien de visionnage episode 1","class"=>"input-sm"],
            "label"=>"Episode 1",
            "required"=>false,
        ))
        ->add('episode2',TextType::class,array(
            "attr"=>["placeholder"=>"Lien de visionnage episode 2","class"=>"input-sm"],
            "label"=>"Episode 2",
            "required"=>false,
        ))
        ->add('episode3',TextType::class,array(
            "attr"=>["placeholder"=>"Lien de visionnage episode 3","class"=>"input-sm"],
            "label"=>"Episode 3",
            "required"=>false,
        ))
        ->add('producers',CollectionType::class,array(
            'entry_type' => EntityType::class,
            'entry_options' => array(
                "class"=>Producer::class,
                "placeholder"=>"Producteur...",
                "attr"=>array("class"=>"input-sm"),
                "choice_label"=>"name",
                'group_by' => function($value, $key, $index) {
                    return strtoupper($value->getSlug()[0]);
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
            ),
            "allow_add"=>true,
            "allow_delete"=>true,
            "label"=>"Les Producteurs",
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('actors',CollectionType::class,array(
            'entry_type' => EntityType::class,
            'entry_options' => array(
                "class"=>Actor::class,
                "placeholder"=>"Acteur...",
                "attr"=>array("class"=>"input-sm"),
                "choice_label"=>"name",
                "choice_value"=>"slug",
                'group_by' => function($value, $key, $index) {
                    return strtoupper($value->getSlug()[0]);
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
            ),
             "allow_add"=>true,
            "allow_delete"=>true,
            "label"=>"Les Acteurs",
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('directors',CollectionType::class,array(
            'entry_type' => EntityType::class,
            'entry_options' => array(
                "class"=>Director::class,
                "placeholder"=>"Réalisateur...",
                "attr"=>array("class"=>"input-sm"),
                "choice_label"=>"name",
                "choice_value"=>"slug",
                'group_by' => function($value, $key, $index) {
                    return strtoupper($value->getSlug()[0]);
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
            ),
             "allow_add"=>true,
            "allow_delete"=>true,
            "label"=>"Les Réalisateurs",
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('languages',CollectionType::class,array(
            'entry_type' => EntityType::class,
            'entry_options' => array(
                "class"=>Language::class,
                "placeholder"=>"Langue...",
                "attr"=>array("class"=>"input-sm"),
                "choice_label"=>"name",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
            ),
            "allow_add"=>true,
            "allow_delete"=>true,
            "label"=>"Versions disponibles",
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('countries',CollectionType::class,array(
            'entry_type' => EntityType::class,
            'entry_options' => array(
                "class"=>Country::class,
                "attr"=>array("class"=>"input-sm"),
                "placeholder"=>"Pays...",
                "choice_label"=>"name",
                "choice_value"=>"slug",
                'group_by' => function($value, $key, $index) {
                    return strtoupper($value->getSlug()[0]);
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
            ),
            "allow_add"=>true,
            "allow_delete"=>true,
            "label"=>"Origine de production",
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('genres',CollectionType::class,array(
            'entry_type' => EntityType::class,
            'entry_options' => array(
                "class"=>Genre::class,
                "placeholder"=>"Genre...",
                "attr"=>array("class"=>"input-sm"),
                "choice_label"=>"name",
                "attr"=>array("class"=>"input-sm"),
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
            ),
            "allow_add"=>true,
            "allow_delete"=>true,
            "label"=>"Les Genres",
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('catalogs',CollectionType::class,array(
            'entry_type' => EntityType::class,
            'entry_options' => array(
                "class"=>\AppBundle\Entity\CatalogType::class,
                "placeholder"=>"Catalogue...",
                "attr"=>array("class"=>"input-sm"),
                "choice_label"=>"name",
                "attr"=>array("class"=>"input-sm"),
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },
            ),
            "allow_add"=>true,
            "allow_delete"=>true,
            "label"=>"Les Catalogues",
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('episodes',CollectionType::class,array(
            'entry_type' => MovieEpisodeType::class,
            'entry_options' => array(
               "upload_dir"=>$options["upload_dir"],
            ),
            'by_reference' => false,
            "allow_add"=>true,
            "allow_delete"=>true,
            "label"=>"Les Episodes",
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('gallery',CollectionType::class,array(
            'entry_type' => FileType::class,
            'entry_options' => array(
                "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"],
            ),
            "allow_add"=>true,
            "allow_delete"=>true,
            "label"=>"Galerie photo",
            "required"=>false,
            "mapped"=>false,
        ))
        
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options,&$languages){
            $movie = $event->getData();
            $form = $event->getForm();

            if (!$movie) {
                return;
            }   //

            if(in_array(@$options["use_for"], ["upload","upload_gallery"])) {
                $form
                ->remove('name')
                ->remove('category')
                ->remove('originalName')
                ->remove('synopsis')
                ->remove('tagline')
                ->remove('logline')
                ->remove('reward')
                ->remove('award')
                ->remove('audience')
                ->remove('inTheather')
                ->remove('hasExclusivity')
                ->remove('isPublished')
                ->remove('format')
                ->remove('year_start')
                ->remove('year_end')
                ->remove('mention')
                ->remove('state')
                ->remove('language')
                ->remove('trailer')
                ->remove('episode1')
                ->remove('episode2')
                ->remove('episode3')
                ->remove('producers')
                ->remove('actors')
                ->remove('directors')
                ->remove('languages')
                ->remove('countries')
                ->remove('genres')
                ->remove('catalogs');
            }

            if($options["use_for"] == "upload_gallery") {
                $form
                ->remove('coverImg')
                ->remove('landscapeImg')
                ->remove('portraitImg');
            }
            else if($options["use_for"] == "upload") {
                $form
                ->remove('gallery');
            }


            if($movie->getCoverImg()){
                $path = $options['upload_dir'].'/'.basename($movie->getCoverImg());
                $movie->setCoverImg(new File($path));
            }

            if($movie->getLandscapeImg()){
                $path = $options['upload_dir'].'/'.basename($movie->getLandscapeImg());
                $movie->setLandscapeImg(new File($path));
            }

            if($movie->getPortraitImg()){
                $path = $options['upload_dir'].'/'.basename($movie->getPortraitImg());
                $movie->setPortraitImg(new File($path));
            }

            $movie->setInTheather($movie->getInTheather()?true:false);
            $movie->setHasExclusivity($movie->getHasExclusivity()?true:false);
            $movie->setIsPublished($movie->getIsPublished()?true:false);

        })
        ->addEventListener(FormEvents::SUBMIT,function(FormEvent $event)use(&$options,$languages){
            $data = $event->getData();
            $form = $event->getForm();

            $event->setData($data);
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
