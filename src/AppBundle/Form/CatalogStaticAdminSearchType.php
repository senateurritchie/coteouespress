<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityRepository;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


use AppBundle\Entity\CatalogStatic;
use AppBundle\Form\CatalogStaticType;

class CatalogStaticAdminSearchType extends AbstractType
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $this->requestStack->getCurrentRequest();

        $builder
        ->remove('file')
        ->add('catalog',EntityType::class,array(
            "placeholder"=>"Catalogue...",
            "required"=>false,
            "label"=>"Catalogue",
            "class"=>\AppBundle\Entity\CatalogType::class,
            "choice_label"=>function($item,$key,$index){
                return $item->getName();
            }
        ))
        ->add('year',DateType::class,array(
            "attr"=>["class"=>"input-sm"],
            "widget"=>"single_text",
            "label"=>"Année",
            "required"=>false,
        ))
        ->add('published',ChoiceType::class,array(
            "required"=>false,
            "attr"=>["class"=>"input-sm"],
            "placeholder"=>"état...",
            "label"=>"Etat de publication",
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
            'data_class' => CatalogStatic::class
        ));
    }

    public function getParent(){
        return CatalogStaticType::class;
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix(){
        return null;
    }

}
