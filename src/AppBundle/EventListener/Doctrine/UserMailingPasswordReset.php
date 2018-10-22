<?php 
namespace AppBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\PasswordReset;
use AppBundle\Entity\User;

class UserMailingPasswordReset{

    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if (!$entity instanceof PasswordReset) {
            return;
        }

        $em = $args->getEntityManager();
        $mailer = $this->container->get('mailer');
        $twig = $this->container->get('twig');

        $user = $entity->getUser();

        $token = User::generateToken(64);
        $entity->setToken($token);
        $entity->setOldPassword($user->getPassword());

        // message a envoyer a l'utilisateur
        $appsite = $this->container->getParameter('app.site');
        $message = (new \Swift_Message("Réinitialisation mot de passe"))
        ->setFrom([$appsite["email"] => $appsite["name"]])
        ->setTo([$user->getEmail()=>$user->getUsername()])
        ->setBody(
            $twig->render(
                'admin/user/email/password-reset.html.twig',
                array(
                    "username"=>$user->getUsername(),
                    "email"=>$user->getEmail(),
                    "token"=>$token,
                )
            ),
            'text/html'
        );
        $mailer->send($message);
    }
}