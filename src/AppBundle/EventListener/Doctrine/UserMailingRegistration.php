<?php 
namespace AppBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\User;

class UserMailingRegistration{

    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        $em = $args->getEntityManager();

        $mailer = $this->container->get('mailer');
        $twig = $this->container->get('twig');
        $encoder = $this->container->get('security.password_encoder');

        $password = uniqid();
        $encoded = $encoder->encodePassword($entity, $password);
        $token = User::generateToken(64);

        $entity->setPassword($encoded);
        $entity->setSignUpToken($token);
        $entity->setState("pending");

        // message a envoyer a l'utilisateur
        $appsite = $this->container->getParameter('app.site');
        $message = (new \Swift_Message("Création de compte"))
        ->setFrom([$appsite["email"] => $appsite["name"]])
        ->setTo([$entity->getEmail()=>$entity->getUsername()])
        ->setBody(
            $twig->render(
                'admin/user/email/registration.html.twig',
                array(
                    "username"=>$entity->getUsername(),
                    "email"=>$entity->getEmail(),
                    "password"=>$password,
                    "token"=>$token
                )
            ),
            'text/html'
        );

        $mailer->send($message);

        // message a envoyer au webmaster
        $user_type = $entity->getUserType();

        $appsite = $this->container->getParameter('app.site');
        $message = (new \Swift_Message("Création de compte [".$user_type->getName()."]"))
        ->setFrom([$appsite["email"] => $appsite["name"]])
        ->setTo([$appsite["email"] => $appsite["name"]])
        ->addCc("communication@coteouest.tv")
        ->setBody(
            $twig->render(
                'admin/user/email/registration-self-notice.html.twig',
                [
                    "user_type"=>$user_type->getName(),
                    "username"=>$entity->getUsername(),
                    "email"=>$entity->getEmail(),
                    "department"=>['name'=>"Acquisition"]
                ]
            ),
            'text/html'
        );

        if($user_type->getSlug() == "client") {
            //$message->addCc("sales@coteouest.tv");
            $message->addCc("zakeszako@gmail.com");
        }
        else if (in_array($user_type->getSlug(), array("producteur","réalisateur"))) {
            $message->addCc("acquisition@coteouest.tv");
        }
        
        $mailer->send($message);
    }
}