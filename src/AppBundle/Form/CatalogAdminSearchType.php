<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityRepository;



use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use AppBundle\Form\CatalogType;
use AppBundle\Entity\Catalog;
use AppBundle\Entity\Actor;

class CatalogAdminSearchType extends AbstractType
{
    protected $translator;
    protected $requestStack;

    public function __construct(TranslatorInterface $translator,RequestStack $requestStack){

        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $this->requestStack->getCurrentRequest();

        $builder
        ->add('state',ChoiceType::class,array(
            "required"=>false,
            "placeholder"=>"status...",
            "choices"=>[
                "En attente"=>"pre-moderate",
                "Rejeté"=>"rejected",
                "Approuvé"=>"approved",
                "En modération"=>"workflow",
                "Désactivé"=>"deactivated",
            ],
            'choice_attr' => function($value, $key, $index) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("state") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            "mapped"=>false,
        ))
        ->add('published',ChoiceType::class,array(
            "required"=>false,
            "placeholder"=>"publish state...",
            "choices"=>[
                "Publié"=>"yes",
                "Dépublié"=>"no",
            ],
            'choice_attr' => function($value, $key, $index) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("published") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            "mapped"=>false,
        ))
        ->add('in_theather',CheckboxType::class,array(
            "required"=>false,
            "mapped"=>false,
            "data"=>$request->query->get("in_theather")?true:false
        ))
        ->add('has_exclusivity',CheckboxType::class,array(
            "required"=>false,
            "mapped"=>false,
            "data"=>$request->query->get("has_exclusivity")?true:false
        ))
        /*->add('is_published',CheckboxType::class,array(
            "required"=>false,
            "mapped"=>false,
            "data"=>$request->query->get("is_published")?true:false
        ))*/
        ->remove("year")

        ->add('year',IntegerType::class,array(
            "required"=>false,
            "mapped"=>false,
            "data"=>$request->query->get("year")?intval($request->query->get("year")):null,
            "attr"=>array(
                "placeholder"=>"debut de production",
            )
        ))
        ->add('year_end',IntegerType::class,array(
            "required"=>false,
            "mapped"=>false,
            "data"=>$request->query->get("year_end")?intval($request->query->get("year_end")):null,
            "attr"=>array(
                "placeholder"=>"Fin de production",
            )
        ))
       ->add('actor',EntityType::class,array(
            "required"=>false,
            "placeholder"=>"Acteur...",
            "label"=>"Casting",
            "class"=>Actor::class,
            "choice_label"=>"name",
            "choice_value"=>"slug",
            'choice_attr' => function($value, $key, $index) {
               $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                $query = $request->query->get("catalog");

                if($query == $value->getSlug() || (is_array($query) && in_array($value->getSlug(), $query))){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            'group_by' => function($value, $key, $index) {
                return strtoupper($value->getSlug()[0]);
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },
            "mapped"=>false,
        ))
       ->add('catalog',EntityType::class,array(
            "required"=>false,
            "placeholder"=>"catalogue...",
            "label"=>"Catalogues",
            "class"=>\AppBundle\Entity\CatalogType::class,
            "choice_label"=>"name",
            "choice_value"=>"slug",
            'choice_attr' => function($value, $key, $index) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                $query = $request->query->get("catalog");

                if($query == $value->getSlug() || (is_array($query) && in_array($value->getSlug(), $query))){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },
            "mapped"=>false,
        ))
       ->add('section',EntityType::class,array(
            "required"=>false,
            "placeholder"=>"section...",
            "label"=>"Section",
            "class"=>\AppBundle\Entity\CatalogSection::class,
            "choice_label"=>"name",
            "choice_value"=>"slug",
            'choice_attr' => function($value, $key, $index) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                $query = $request->query->get("section");

                if($query == $value->getSlug() || (is_array($query) && in_array($value->getSlug(), $query))){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },
            "mapped"=>false,
        ))
       ->add('sectionCategory',EntityType::class,array(
            "required"=>false,
            "placeholder"=>"section catégory...",
            "label"=>"Section catégorie",
            "class"=>\AppBundle\Entity\CatalogSectionCategory::class,
            "choice_label"=>"name",
            "choice_value"=>"slug",
            'choice_attr' => function($value, $key, $index) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                $query = $request->query->get("sectionCategory");

                if($query == $value->getSlug() || (is_array($query) && in_array($value->getSlug(), $query))){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },
            "mapped"=>false,
        ))
       ->add('order_name',ChoiceType::class,array(
            "required"=>false,
            "placeholder"=>"ordre alphabetique...",
            "choices"=>[
                "A-Z"=>"ASC",
                "Z-A"=>"DESC",
            ],
            'choice_attr' => function($value, $key, $index) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("order_name") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            "mapped"=>false,
        ))
       ->add('order_year',ChoiceType::class,array(
            "required"=>false,
            "placeholder"=>"ordre chronologique...",
            "choices"=>[
                "croissant"=>"ASC",
                "décroissant"=>"DESC",
            ],
            'choice_attr' => function($value, $key, $index) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("order_year") == $value){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            "mapped"=>false,
        ))
        ->add('order_id',ChoiceType::class,array(
            "required"=>false,
            "placeholder"=>"",
            "label"=>"Ordre d'entrée",
            "choices"=>[
                "premier entré"=>"ASC",
                "dernier entré"=>"DESC",
            ],
            'choice_attr' => function($value, $key, $index) {
                $attrs = [];
                $request = $this->requestStack->getCurrentRequest();
                if($request->query->get("order_id") == $value){
                    $attrs["selected"] = "selected";
                }
                else if(!$request->query->get("order_id") && $value == "DESC"){
                    $attrs["selected"] = "selected";
                }
                return $attrs;
            },
            "mapped"=>false,
        ))
        ->add('limit',IntegerType::class,array(
            "required"=>false,
            "label"=>"Resultats",
            'attr' => [
                "value"=>$request->query->get("limit") ? intval($request->query->get("limit")) : 20,
                "min"=>1,
            ],
            "mapped"=>false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => Catalog::class
        ));
    }

    public function getParent(){
        return CatalogType::class;
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix(){
        return null;
    }


}
