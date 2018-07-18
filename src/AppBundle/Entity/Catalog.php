<?php

namespace AppBundle\Entity;

class Catalog{
    private $category;


    public function getCategory(){
        return $this->category;
    }

    public function setCategory($category){
        $this->category = $category;
        return $this;
    }
}
