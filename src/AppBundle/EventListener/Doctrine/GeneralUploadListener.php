<?php 
namespace AppBundle\EventListener\Doctrine;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;

use AppBundle\Entity\Director;
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

        if (!$entity instanceof Director) {
            return;
        }

        if ($fileName = $entity->getImage()) {
            //$entity->setImage(new File($this->uploader->getTargetDirectory().'/'.$fileName));
        }
    }

    private function uploadFile($entity){
        if (!$entity instanceof Director) {
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