<?php

namespace AppBundle\Form;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
 

use AppBundle\Entity\Catalog;
use AppBundle\Entity\Language;
use AppBundle\Entity\Category;
use AppBundle\Entity\Genre;
use AppBundle\Entity\Country;
use AppBundle\Entity\Producer;
use AppBundle\Entity\Director;
use AppBundle\Entity\Creator;

class CatalogType extends AbstractType
{
    protected $translator;
    protected $requestStack;

    public function __construct(TranslatorInterface $translator,RequestStack $requestStack){

        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options){

        $request = $this->requestStack->getCurrentRequest();

        $builder
        ->add('genre',EntityType::class,array(
            "required"=>false,
            "placeholder"=> $this->translator->trans("Genre",array(),"catalogue"),
            "class"=>Genre::class,
            "choice_label"=>function($item,$key,$index){
                return $item->getName();
            },
            "choice_value"=>"slug",
            'choice_attr' => function($value, $key, $value) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("genre") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            "mapped"=>false,
        ))
        ->add('mention',ChoiceType::class,array(
            "required"=>false,
            "placeholder"=>$this->translator->trans("Définition",array(),"catalogue"),
            "choices"=>[
                "HD"=>"HD",
                "SD"=>"SD",
                "4k"=>"4k",
                "2k"=>"2k",
            ],
            'choice_attr' => function($value, $key, $value) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("mention") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            "mapped"=>false,
        ))
        ->add('country',EntityType::class,array(
            "required"=>false,
            "placeholder"=>$this->translator->trans("Pays",array(),"catalogue"),
            "class"=>Country::class,
            "choice_label"=>"name",
            "choice_value"=>"slug",
            'choice_attr' => function($value, $key, $value) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("country") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            'group_by' => function($value, $key, $value) {
                return strtoupper($value[0]);
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },

            "mapped"=>false,
        ))
        ->add('category',EntityType::class,array(
            "required"=>false,
            "placeholder"=>$this->translator->trans("Catégorie",array(),"catalogue"),
            "class"=>Category::class,
            "choice_label"=>function($item,$key,$index){
                return $item->getName();
            },
            "choice_value"=>"slug",
            "mapped"=>false,
            'choice_attr' => function($value, $key, $value) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("category") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },

        ))
        ->add('language',EntityType::class,array(
            "required"=>false,
            "placeholder"=>$this->translator->trans("Langue",array(),"catalogue"),
            "class"=>Language::class,
            "choice_label"=>"name",
            "choice_value"=>"slug",
            'choice_attr' => function($value, $key, $value) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("language") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            "mapped"=>false,
        ))
        ->add('producer',EntityType::class,array(
            "required"=>false,
            "placeholder"=>$this->translator->trans("Producteur",array(),"catalogue"),
            "class"=>Producer::class,
            "choice_label"=>"name",
            "choice_value"=>"slug",
            'choice_attr' => function($value, $key, $value) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("producer") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            'group_by' => function($value, $key, $value) {
                return strtoupper($value[0]);
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },
            "mapped"=>false,
        ))
        ->add('director',EntityType::class,array(
            "required"=>false,
            "placeholder"=>$this->translator->transChoice("Réalisateur",1,array(),"catalogue"),
            "class"=>Director::class,
            "choice_label"=>"name",
            "choice_value"=>"slug",
            'choice_attr' => function($value, $key, $value) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("director") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            'group_by' => function($value, $key, $value) {
                return strtoupper($value[0]);
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },
            "mapped"=>false,
        ))
        ->add('creator',EntityType::class,array(
            "required"=>false,
            "placeholder"=>$this->translator->trans("Créateur",array(),"catalogue"),
            "class"=>Creator::class,
            "choice_label"=>"name",
            "choice_value"=>"slug",
            "mapped"=>false,
            'choice_attr' => function($value, $key, $value) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("creator") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            'group_by' => function($value, $key, $value) {
                return strtoupper($value[0]);
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },
        ))
        ->add('name',TextType::class,array(
            "required"=>false,
            "mapped"=>false,
            "data"=>$this->requestStack->getCurrentRequest()->query->get("name"),
            "attr"=>array(
                "placeholder"=>$this->translator->trans("Nom du programme",array(),"catalogue")
            )
        ))
        ->add('in_theatres',CheckboxType::class,array(
            "required"=>false,
            "mapped"=>false,
            "data"=>$request->query->get("in_theatres")?true:false
        ))
        ->add('year',IntegerType::class,array(
            "required"=>false,
            "mapped"=>false,
            "data"=>$request->query->get("year")?intval($request->query->get("year")):null,
            "attr"=>array(
                "placeholder"=>$this->translator->trans("Année",array(),"catalogue")
            )
        ))
        ->add('search', SubmitType::class, array("label"=>"Recherche"))
        ->addEventListener(FormEvents::PRE_SET_DATA ,function(FormEvent $event){

            $category = $event->getData();
            $form = $event->getForm();

            if (!$category) {
                return;
            }
        });

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => Catalog::class
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
