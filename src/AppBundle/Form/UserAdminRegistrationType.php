<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use AppBundle\Form\UserType;
use AppBundle\Entity\Role;

class UserAdminRegistrationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('salt')->remove('createAt')->remove('password');

        $builder
        ->add('username', TextType::class,array(
            "label"=>"Nom & Prénoms",
            "attr"=>["class"=>"input-sm"]
        ))
        ->add('email',EmailType::class,array(
            "attr"=>["class"=>"input-sm"]
        ))
        ->add('roles',EntityType::class,array(
            "placeholder"=>"",
            "mapped"=>false,
            "class"=>Role::class,
            "choice_label"=>"name",
            "attr"=>["class"=>"input-sm"],
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('r');

                $qb->where($qb->expr()->eq('r.type',':role'))
                ->setParameter('role',"role")
                ->orderBy('r.name', 'ASC');
                return $qb;
            },
        ))
        ->add('privileges',EntityType::class,array(
            "required"=>false,
            "class"=>Role::class,
            "choice_label"=>"name",
            "choice_value"=>"label",
            "mapped"=>false,
            "multiple"=>true,
            "attr"=>["class"=>"input-sm"],
            'group_by' => function($index, $key, $value) {
                $e = explode("_", $value);
                if(count($e) >= 2){
                    $e = array_slice($e, 0,2);
                    $e = implode("_", $e);
                }
                else{
                    $e = $value;
                }
                return $e;
            },
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('r');

                $qb->where($qb->expr()->eq('r.type',':role'))
                ->setParameter('role',"privilege")
                ->orderBy('r.name', 'ASC');

                return $qb;
            },
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
