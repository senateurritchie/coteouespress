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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


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
        ->add('category',EntityType::class,array(
            "placeholder"=>"Catégorie",
            "label"=>"Catégorie du programme",
            "class"=>Category::class,
            "choice_label"=>function($item,$key,$index){
                return $item->getName();
            }
        ))
        ->add('originalName',TextType::class,array(
            "attr"=>["placeholder"=>"Nom original du programme","class"=>"input-sm"]
        ))
        ->add('synopsis',TextareaType::class,array(
            "attr"=>["placeholder"=>"A propos du programme","class"=>"input-sm"]
        ))
        ->add('inTheather',CheckboxType::class,array(
            "label"=>"Programme à l'affiche",
            "mapped"=>false,
        ))
        ->add('hasExclusivity',CheckboxType::class,array(
            "label"=>"Production à blébiciter",
            "mapped"=>false,
        ))
        ->add('format',TextType::class,array(
            "attr"=>["placeholder"=>"Format","class"=>"input-sm"]
        ))
        ->add('year_start',DateType::class,array(
            "attr"=>["class"=>"input-sm"],
            "widget"=>"single_text",
            "widget"=>"single_text",
            "label"=>"Debut de production",
        ))
        ->add('year_end',DateType::class,array(
            "attr"=>["class"=>"input-sm"],
            "widget"=>"single_text",
            "label"=>"Fin de production",
        ))
        ->add('mention',ChoiceType::class,array(
            "attr"=>["class"=>"input-sm"],
            "choices"=>[
                "HD"=>"HD",
                "SD"=>"SD",
                "4k"=>"4k",
                "2k"=>"2k",
            ]
        ))
        ->add('originalLanguage',LanguageType::class,array(
            "placeholder"=>"version original",
            "choice_value"=>function($value){
                return $value;
            },
            "group_by"=>function($value,$input,$index){
                $sep = "-";
                $input = transliterator_transliterate('Any-Latin;NFD;[:Nonspacing Mark:] Remove; Lower();',$input);

                $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ơ','ơ','Ư','ư','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ');

                $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');

                $slug = str_replace($a, $b, $input);
                $slug = preg_replace('#[^A-Za-z0-9]+#',$sep,trim($slug));
                $slug = preg_replace("#-+#", $sep, $slug);
                $slug = trim($slug,$sep);
                $slug = trim(strtolower($slug));

                return strtoupper($slug[0]);
            }
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
            "attr"=>["placeholder"=>"Lien de visionnage du trailer","class"=>"input-sm"]
        ))
        ->add('episode1',TextType::class,array(
            "attr"=>["placeholder"=>"Lien de visionnage episode 1","class"=>"input-sm"],
            "label"=>"Episode 1"
        ))
        ->add('episode2',TextType::class,array(
            "attr"=>["placeholder"=>"Lien de visionnage episode 2","class"=>"input-sm"],
            "label"=>"Episode 2"
        ))
         ->add('episode3',TextType::class,array(
            "attr"=>["placeholder"=>"Lien de visionnage episode 3","class"=>"input-sm"],
            "label"=>"Episode 3"
        ))
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options){
            $movie = $event->getData();
            $form = $event->getForm();

            if (!$movie) {
                return;
            }

            if(@$options["use_for"] == "upload"){
                $form
                ->remove('category')
                ->remove("name")
                ->remove('originalName')
                ->remove('synopsis')
                ->remove('inTheather')
                ->remove('hasExclusivity')
                ->remove('format')
                ->remove('trailer')
                ->remove('year_start')
                ->remove('year_end')
                ->remove('mention')
                ->remove('originalLanguage')
                ->remove('coverImg')
                ->remove('landscapeImg')
                ->remove('portraitImg')
                ->remove('trailer')
                ->remove('episode1')
                ->remove('episode2')
                ->remove('episode3');
            }

            if($movie->getCoverImg()){
                $path = $options['upload_dir'].'/'.$movie->getCoverImg();
                $movie->setCoverImg(new File($path));
            }

            if($movie->getLandscapeImg()){
                $path = $options['upload_dir'].'/'.$movie->getLandscapeImg();
                $movie->setLandscapeImg(new File($path));
            }

            if($movie->getPortraitImg()){
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
