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
use AppBundle\Services\FileUploader;

class GeneralUploadListener{
    private $uploader;

    public function __construct(FileUploader $uploader){
        $this->uploader = $uploader;
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

        if (!$entity instanceof Director && (!$entity instanceof Producer) && (!$entity instanceof Actor) &&  (!$entity instanceof MovieTrailer)) {
            return;
        }

        if ($fileName = $entity->getImage()) {
            
        }
    }

     public function postRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if (!$entity instanceof Director && (!$entity instanceof Producer) && (!$entity instanceof Actor) &&  (!$entity instanceof MovieTrailer)) {
            return;
        }

        if ($fileName = $entity->getImage()) {
            $this->uploader->remove($fileName);
        }
    }

    private function uploadFile($entity){
        if (!$entity instanceof Director && (!$entity instanceof Producer) && (!$entity instanceof Actor) &&  (!$entity instanceof MovieTrailer)) {
            return;
        }

        $file = $entity->getImage();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);
            $entity->setImage($fileName);
        }
    }
}