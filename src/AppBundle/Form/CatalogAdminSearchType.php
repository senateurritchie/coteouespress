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
                if($request->query->get("director") == $value){
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
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Catalog::class
        ));
    }

    public function getParent()
    {
        return CatalogType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }


}
