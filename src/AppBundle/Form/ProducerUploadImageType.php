<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Form\Extension\Core\Type\FileType;

use AppBundle\Form\ProducerType;
use AppBundle\Entity\Producer;

class ProducerUploadImageType extends AbstractType
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
            $producer = $event->getData();
            $form = $event->getForm();

            if (!$producer) {
                return;
            }

            if($producer->getImage()){
                $path = $options['upload_dir'].'/'.basename($producer->getImage());
                $producer->setImage(new File($path));
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
            'data_class' => Producer::class
        ));
    }

    public function getParent()
    {
        return ProducerType::class;
    }


}
