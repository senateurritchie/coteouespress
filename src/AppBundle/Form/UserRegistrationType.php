<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Translation\TranslatorInterface;



use AppBundle\Form\UserType;

class UserRegistrationType extends AbstractType
{
    protected $translator;

    public function __construct(TranslatorInterface $translator){
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('salt')->remove('createAt')->remove('password')->remove('roles');

        $builder
        ->add('email', EmailType::class,array(
            "label"=>"Adresse email",
            "attr"=>array(
                "class"=>"form-control-sm"
            ),
        ))
        ->add('username', TextType::class,array(
            "label"=>"Nom",
             "attr"=>array(
                "class"=>"form-control-sm"
            ),
        ))
        ->add('userType',EntityType::class,array(
            "attr"=>array(
                "class"=>"form-control-sm custom-select"
            ),
            "class"=>\AppBundle\Entity\UserType::class,
            "choice_label"=>function($value,$key,$index){
                return ucwords($value->getName());
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.id', 'ASC')
                ->setMaxResults(3);
            },
            "label"=>$this->translator->trans("Je suis un",array(),"registration")."..."
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return UserType::class;
    }

}
