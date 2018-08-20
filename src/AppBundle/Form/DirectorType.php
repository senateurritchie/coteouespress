<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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
            "attr"=>["placeholder"=>"A propos du réalisateur","class"=>"input-sm"]
        ))
        ->add('pays',EntityType::class,array(
            "mapped"=>false,
            "required"=>false,
            "multiple"=>true,
            "label"=> "Pays d'origine",
            "class"=>Country::class,
            "choice_label"=>function($item,$key,$index){
                return $item->getName();
            },
            "choice_value"=>"slug",
            'group_by' => function($value, $key, $index) {
                return strtoupper($value->getSlug()[0]);
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Director::class
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
