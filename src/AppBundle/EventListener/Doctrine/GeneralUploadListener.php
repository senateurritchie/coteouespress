<?php 
namespace AppBundle\EventListener\Doctrine;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;

use AppBundle\Entity\Director;
use AppBundle\Entity\Producer;
use AppBundle\Entity\Actor;
use AppBundle\Entity\MovieTrailer;
use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieScene;
use AppBundle\Entity\Metadata;

use AppBundle\Services\FileUploader;
use AppBundle\Services\PrivateFileUploader;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GeneralUploadListener{
    /**
    * @var AppBundle\Services\FileUploader
    */
    private $uploader;
    /**
    * @var AppBundle\Services\FileUploader
    */
    private $pvtUploader;

    public function __construct(ContainerInterface $container){
        $this->uploader = $container->get('app.uploader');
        $this->pvtUploader = $container->get('app.prv_uploader');
    }

    public function prePersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args){
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args){

        $entity = $args->getEntity();
        $fileName = null;

        if ($entity instanceof Director || ($entity instanceof Producer) || ($entity instanceof Actor) ||  ($entity instanceof MovieTrailer) ||  ($entity instanceof MovieScene)) {

        }
        else if($entity instanceof Movie){

        }
        else if($entity instanceof MovieScene){

        }
        else if($entity instanceof Metadata){

        }
    }

    public function postRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if ($entity instanceof Director || ($entity instanceof Producer) || ($entity instanceof Actor) ||  ($entity instanceof MovieTrailer) ||  ($entity instanceof MovieScene)) {
            
            if(($fileName = trim($entity->getImage()))){
                $this->uploader->remove($fileName);
            }
        }
        else if($entity instanceof Movie){
            if(($fileName = trim($movie->getCoverImg()))) {
                $this->uploader->remove($fileName);
            }
            if(($fileName = trim($movie->getLandscapeImg()))) {
                $this->uploader->remove($fileName);
            }
            if(($fileName = trim($movie->getPortraitImg()))) {
                $this->uploader->remove($fileName);
            }
        }
        else if($entity instanceof Metadata){
            if(($fileName = trim($entity->getFile()))){
                $this->pvtUploader->remove($fileName);
            }
        }
    }

    private function uploadFile($entity){
        
        if ($entity instanceof Director || ($entity instanceof Producer) || ($entity instanceof Actor) ||  ($entity instanceof MovieTrailer) ||  ($entity instanceof MovieScene)) {

            $file = $entity->getImage();

            // only upload new files
            if ($file instanceof UploadedFile) {
                $fileName = $this->uploader->upload($file);
                $entity->setImage($fileName);
            }
        }
        else if($entity instanceof Movie){

            if(($file = $entity->getCoverImg())) {
                // only upload new files
                if ($file instanceof UploadedFile) {
                    $fileName = $this->uploader->upload($file);
                    $entity->setCoverImg($fileName);
                }
            }

            if(($file = $entity->getLandscapeImg())){
                // only upload new files
                if ($file instanceof UploadedFile) {
                    $fileName = $this->uploader->upload($file);
                    $entity->setLandscapeImg($fileName);
                }
            }

            if(($file = $entity->getPortraitImg())){
                // only upload new files
                if ($file instanceof UploadedFile) {
                    $fileName = $this->uploader->upload($file);
                    $entity->setPortraitImg($fileName);
                }
            }
        }
        else if($entity instanceof Metadata){
            if(($file = $entity->getFile())){
                // only upload new files
                if ($file instanceof UploadedFile) {
                    $fileName = $this->pvtUploader->upload($file);
                    $entity->setFile($fileName);
                    $entity->setSize($file->getClientSize());
                }
            }
        }
    }
}