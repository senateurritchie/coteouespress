<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Metadata;

class MetadataType extends AbstractType{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('type',EntityType::class,array(
            "required"=>false,
            "placeholder"=>"source de données...",
            "label"=>"Source",
            "class"=>\AppBundle\Entity\MetadataType::class,
            "choice_label"=>"name",
        ))
        ->add('file',FileType::class,array(
            "required"=>true
        ))
        ->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)use(&$options){
            $file = $event->getData();
            $form = $event->getForm();

            if (!$file) {
                return;
            }

            if($file->getFile()){
                $path = $options['upload_dir'].'/'.basename($file->getFile());
                $file->setFile(new File($path));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setRequired('upload_dir');

        $resolver->setDefaults(array(
            'data_class' => Metadata::class
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix(){
        return null;
    }


}
