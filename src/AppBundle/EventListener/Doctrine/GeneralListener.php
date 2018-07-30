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

class GeneralListener{

    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if($entity instanceof MovieActor) {
            $el = $entity->getActor();
            $nbr = intval($el->getMovieNbr())+1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieDirector) {
            $el = $entity->getDirector();
            $nbr = intval($el->getMovieNbr())+1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieProducer) {
            $el = $entity->getProducer();
            $nbr = intval($el->getMovieNbr())+1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieCategory) {
            $el = $entity->getCategory();
            $nbr = intval($el->getMovieNbr())+1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieLanguage) {
            $el = $entity->getLanguage();
            $nbr = intval($el->getMovieNbr())+1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieGenre) {
            $el = $entity->getGenre();
            $nbr = intval($el->getMovieNbr())+1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof MovieCountry) {
            $el = $entity->getCountry();
            $nbr = intval($el->getMovieNbr())+1;
            $el->setMovieNbr($nbr);
        }
        else if ($entity instanceof ActorCountry) {
            $el = $entity->getCountry();
            $nbr = intval($el->getActorNbr())+1;
            $el->setActorNbr($nbr);
        }
        else if ($entity instanceof DirectorCountry) {
            $el = $entity->getCountry();
            $nbr = intval($el->getDirectorNbr())+1;
            $el->setDirectorNbr($nbr);
        }
        else if ($entity instanceof ProducerCountry) {
            $el = $entity->getCountry();
            $nbr = intval($el->getProducerNbr())+1;
            $el->setProducerNbr($nbr);
        }
        else if ($entity instanceof Movie) {
            $el = $entity->getCategory();
            $nbr = intval($el->getMovieNbr())+1;
            $el->setMovieNbr($nbr);
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
            $el = $entity->getCategory();
            $nbr = intval($el->getMovieNbr())-1;
            $el->setMovieNbr($nbr);
        }
    }
}