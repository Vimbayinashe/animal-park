<?php

class Animal {
    public function __construct($id, $name, $category, $birthday) {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->birthday = $birthday;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getAge() {
        $today = strtotime("today");
        $diff = $today - $this->birthday;
        $age = $diff - 1970;

        return $age;
    }

}

