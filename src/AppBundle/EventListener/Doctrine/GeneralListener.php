<?php 
namespace AppBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\MovieActor;
use AppBundle\Entity\MovieDirector;
use AppBundle\Entity\MovieProducer;
use AppBundle\Entity\MovieCategory;
use AppBundle\Entity\MovieLanguage;
use AppBundle\Entity\MovieGenre;
use AppBundle\Entity\MovieCountry;
use AppBundle\Entity\Movie;

use AppBundle\Entity\ActorCountry;
use AppBundle\Entity\DirectorCountry;
use AppBundle\Entity\ProducerCountry;
use AppBundle\Entity\CatalogDownload;
use AppBundle\Entity\CatalogStatic;
use AppBundle\Entity\User;
use AppBundle\Entity\Role;
use AppBundle\Entity\UserRole;
use AppBundle\Entity\OriginalLanguage;



class GeneralListener{

    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if($entity instanceof MovieActor) {
            if(($el = $entity->getActor())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }
        }
        else if ($entity instanceof MovieDirector) {
            if(($el = $entity->getDirector())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }
           
        }
        else if ($entity instanceof MovieProducer) {
            if(($el = $entity->getProducer())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }
        }
        else if ($entity instanceof MovieCategory) {
            if(($el = $entity->getCategory())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }
        }
        else if ($entity instanceof MovieLanguage) {
            if(($el = $entity->getLanguage())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }
        }
        else if ($entity instanceof MovieGenre) {
            if(($el = $entity->getGenre())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }
            
        }
        else if ($entity instanceof MovieCountry) {
            if(($el = $entity->getCountry())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }
        }
        else if ($entity instanceof MovieCatalog) {
            if(($el = $entity->getCatalog())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }
        }
        else if ($entity instanceof ActorCountry) {
            if(($el = $entity->getCountry())){
                $nbr = intval($el->getActorNbr())+1;
                $el->setActorNbr($nbr);
            }
        }
        else if ($entity instanceof DirectorCountry) {
            if(($el = $entity->getCountry())){
                $nbr = intval($el->getDirectorNbr())+1;
                $el->setDirectorNbr($nbr);
            }
        }
        else if ($entity instanceof ProducerCountry) {
            if(($el = $entity->getCountry())){
                $nbr = intval($el->getProducerNbr())+1;
                $el->setProducerNbr($nbr);
            }
            
        }
        else if ($entity instanceof Movie) {
            if(($el = $entity->getCategory())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }

            if(($el = $entity->getLanguage())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }

            if(($el = $entity->getSection())){
                $nbr = intval($el->getMovieNbr())+1;
                $el->setMovieNbr($nbr);
            }
        }
        else if ($entity instanceof CatalogDownload) {

            if(($el = $entity->getCatalog())){
                $nbr = intval($el->getDownloadNbr())+1;
                $el->setDownloadNbr($nbr);

                if(($el2 = $el->getCatalog())){
                    $nbr = intval($el2->getDownloadNbr())+1;
                    $el2->setDownloadNbr($nbr);
                }
            }
        }
        else if ($entity instanceof CatalogStatic) {
            $entity->setToken(\AppBundle\Entity\User::generateToken(64));
        }
        else if ($entity instanceof User){
            $rep = $em->getRepository(Role::class);
            $role = null;

            if($entity->getUserType()->getSlug() == "client"){
                $role = "ROLE_CUSTOMER";
            }
            else if($entity->getUserType()->getSlug() == "producteur"){
                $role = "ROLE_PRODUCER";
            }
            else if($entity->getUserType()->getSlug() == "realisateur"){
                $role = "ROLE_DIRECTOR";
            }
            else if($entity->getUserType()->getSlug() == "employe"){
                $role = "ROLE_SUBSCRIBER";
            }

           
            if($role){
                if(($role = $rep->findOneBy(["label"=>$role]))){
                    $userrole = new UserRole();
                    $userrole->setUser($entity);
                    $userrole->setRole($role);
                    $em->persist($userrole);
                }
            }
        }


    }

    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if($entity instanceof MovieActor) {
            $el = $entity->getActor();
            $nbr = intval($el->getMovieNbr())-1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieDirector) {
            $el = $entity->getDirector();
            $nbr = intval($el->getMovieNbr())-1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieProducer) {
            $el = $entity->getProducer();
            $nbr = intval($el->getMovieNbr())-1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieCategory) {
            $el = $entity->getCategory();
            $nbr = intval($el->getMovieNbr())-1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieLanguage) {
            $el = $entity->getLanguage();
            $nbr = intval($el->getMovieNbr())-1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieGenre) {
            $el = $entity->getGenre();
            $nbr = intval($el->getMovieNbr())-1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieCountry) {
            $el = $entity->getCountry();
            $nbr = intval($el->getMovieNbr())-1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof ActorCountry) {
            $el = $entity->getCountry();
            $nbr = intval($el->getActorNbr())-1;
            $el->setActorNbr($nbr);
        }
        else if ($entity instanceof DirectorCountry) {
            $el = $entity->getCountry();
            $nbr = intval($el->getDirectorNbr())-1;
            $el->setDirectorNbr($nbr);
        }
        else if ($entity instanceof ProducerCountry) {
            $el = $entity->getCountry();
            $nbr = intval($el->getProducerNbr())-1;
            $el->setProducerNbr($nbr);
        }
        else if ($entity instanceof Movie) {
           if(($el = $entity->getCategory())){
                $nbr = intval($el->getMovieNbr())-1;
                $el->setMovieNbr($nbr);
           }
            
        }
    }
}