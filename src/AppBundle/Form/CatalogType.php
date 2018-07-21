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
        ->add('country',CountryType::class,array(
            "required"=>false,
            "placeholder"=>$this->translator->trans("Pays",array(),"catalogue"),
            /*"class"=>Country::class,
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
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
            },*/
            'group_by' => function($value, $input, $index) {
                $sep = "-";
                $input = transliterator_transliterate('Any-Latin;NFD;[:Nonspacing Mark:] Remove; Lower();',$input);

                $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ơ','ơ','Ư','ư','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ');

                $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');

                $slug = str_replace($a, $b, $input);
                $slug = preg_replace('#[^A-Za-z0-9]+#',$sep,trim($slug));
                $slug = preg_replace("#-+#", $sep, $slug);
                $slug = trim($slug,$sep);
                $slug = trim(strtolower($slug));

                return strtoupper($slug[0]);
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
