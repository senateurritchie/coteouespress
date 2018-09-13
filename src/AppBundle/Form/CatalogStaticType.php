<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use AppBundle\Entity\CatalogStatic;

class CatalogStaticType extends AbstractType{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('catalog',EntityType::class,array(
            "placeholder"=>"Catalogue...",
            "label"=>"Catalogue",
            "class"=>\AppBundle\Entity\CatalogType::class,
            "choice_label"=>function($item,$key,$index){
                return $item->getName();
            }
        ))
        ->add('file',FileType::class,array(
            "label"=> "Fichier"
        ))
        ->add('year',DateType::class,array(
            "attr"=>["class"=>"input-sm"],
            "widget"=>"single_text",
            "label"=>"Annéé",
        ))
        ->add('published',CheckboxType::class,array(
           "label"=>"Publier directement",
            "required"=>false,
        ))
        
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options){
            $catalog = $event->getData();
            $form = $event->getForm();

            if (!$catalog) {
                return;
            }

            if($catalog->getFile()){
                $path = $options['upload_dir'].'/'.basename($catalog->getFile());
                $catalog->setFile(new File($path));
            }

            $catalog->setPublished($catalog->getPublished()?true:false);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setRequired('upload_dir');

        $resolver->setDefaults(array(
            'data_class' => CatalogStatic::class
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix(){
        return null;
    }
}
