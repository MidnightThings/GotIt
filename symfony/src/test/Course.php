<?php
//TODO: DELETE LATER

namespace App\test;

class Course
{
    public $id;
    public $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?id $id): self
    {
        $this->id = $id;
        
        return $this;
    }


    public function getname(): ?string
    {
        return $this->name;
    }

    public function setname(?name $name): self
    {
        $this->name = $name;

        return $this;
    }
}
