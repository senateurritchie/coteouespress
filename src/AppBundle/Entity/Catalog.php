<?php

namespace AppBundle\Entity;

class Catalog{
    protected $category;
    protected $name;
    protected $year;


    public function getCategory(){
        return $this->category;
    }

    public function setCategory($category){
        $this->category = $category;
        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }

    public function getYear(){
        return $this->year;
    }

    public function setYear($year){
        $this->year = $year;
        return $this;
    }
}
