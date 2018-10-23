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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


use AppBundle\Entity\Director;
use AppBundle\Entity\Country;

class DirectorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name',TextType::class,array(
            "attr"=>["placeholder"=>"Nom du réalisateur","class"=>"input-sm"]
        ))
        ->add('description',TextareaType::class,array(
            "attr"=>["placeholder"=>"A propos du réalisateur","class"=>"input-sm"],
             "required"=>false,
        ))
        ->add('pays',CollectionType::class,array(
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
            "label"=>"Pays",
            "required"=>false,
            "mapped"=>false,
        ))
        ->add('image',FileType::class,array(
            "required"=>false,
            "label"=>"Photo",
            "attr"=>["accept"=>"image/png, image/jpeg, image/jpg","class"=>"hide"]
        ))
        ->add('inTheather',CheckboxType::class,array(
            "label"=>"A l'affiche",
            "required"=>false,
        ))
        ->add('hasExclusivity',CheckboxType::class,array(
            "label"=>"A la une",
            "required"=>false,
        ))
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options,&$builder){
            $model = $event->getData();
            $form = $event->getForm();

            if (!$model) {
                return;
            }   //

            if(@$options["use_for"] == "update"){

                /*$field = $form->get('pays');        
                $opts = $field->getOptions();      
                $opts['multiple'] = false;
                $type = $field->getType()->getInnerType(); 
                $builder->add('pays', $type, $opts);*/
            }

            if( $model->getId() && !$model->getUser()){
                $form->add('email', EmailType::class,[
                    "required"=>false,
                    "mapped"=>false,
                    "label"=>"Associé un compte utilisateur",
                    "attr"=>["placeholder"=>"Adresse email...","class"=>"input-sm"]
                ]);
            }

            if($model->getImage()){
                $path = $options['upload_dir'].'/'.basename($model->getImage());
                $model->setImage(new File($path));
            }

            $model->setInTheather($model->getInTheather()?true:false);
            $model->setHasExclusivity($model->getHasExclusivity()?true:false);
        });
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('upload_dir');

        $resolver->setDefaults(array(
            'data_class' => Director::class,
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
