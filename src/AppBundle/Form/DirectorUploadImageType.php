<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Form\Extension\Core\Type\FileType;

use AppBundle\Form\DirectorType;
use AppBundle\Entity\Director;

class DirectorUploadImageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->remove('name')
        ->remove('description')
        ->remove('pays')
        ->add('image',FileType::class,array(
            "required"=>true
        ))
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options){
            $director = $event->getData();
            $form = $event->getForm();

            if (!$director) {
                return;
            }

            if($director->getImage()){
                $path = $options['upload_dir'].'/'.basename($director->getImage());
                $director->setImage(new File($path));
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
            'data_class' => Director::class
        ));
    }

    public function getParent()
    {
        return DirectorType::class;
    }


}
