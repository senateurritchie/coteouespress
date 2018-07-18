<?php

namespace AppBundle\Form;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



use AppBundle\Entity\Department;
use AppBundle\Entity\WebsiteMail;
use AppBundle\Entity\WebsiteReferer;

class WebsiteMailType extends AbstractType
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
        $builder
        ->add('firstname',TextType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
                "placeholder"=>$this->translator->trans("Votre nom",array(),"contact")
            ),
        ))
        ->add('lastname',TextType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
                "placeholder"=>$this->translator->trans("Votre prénom",array(),"contact")
            ),
        ))
        ->add('email',EmailType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
                "placeholder"=>$this->translator->trans("Votre adresse email",array(),"contact")
            ),
        ))
        ->add('subject',TextType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
                "placeholder"=>$this->translator->trans("Objet",array(),"contact")
            ),
        ))
        ->add('message',TextareaType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm",
                'style'=>'min-height:170px;resize:none',
                "placeholder"=>$this->translator->trans("Exprimez-vous",array(),"contact")
            ),
        ))
        ->add('department',EntityType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "required"=>false,
            "placeholder"=>$this->translator->trans("Webmaster",array(),"contact"),
            "class"=>Department::class,
            "choice_label"=>function($department,$key,$index){
                return ucwords($department->getName());
            },
            'group_by' => function($value, $key, $value) {
                return strtoupper($value[0]);
            },
            "choice_value"=>"slug",
            "label"=>$this->translator->trans("Service à contacter",array(),"contact")
        ))
        ->add('referer',EntityType::class,array(
            "expanded"=>true,
            "attr"=>array(
                "class"=>"form-control-sm"
            ),
            "placeholder"=>$this->translator->trans("referer.message",array(),"contact"),
            "class"=>WebsiteReferer::class,
            "choice_label"=>function($referer,$key,$index){
                return ucfirst($referer->getName());
            },
            "label"=>$this->translator->trans("referer.message",array(),"contact"),
            'choice_attr' => function($value, $key, $value) {
                $attrs = [];
                if($value == 6){
                    $attrs["checked"] = "checked";
                }
                return $attrs;
            },
        ));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => WebsiteMail::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_websitemail';
    }


}
