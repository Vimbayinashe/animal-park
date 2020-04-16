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

// $today = strtotime("today");
// $d=strtotime("2014-04-15"); 
// $diff = $today - $d;;
// echo $today."<br>";
// echo $d."<br>";
// echo "Difference in years is " . (date("Y", $diff) - 1970) ."<br>";
// echo "Created date is " . date("Y-m-d h:i:sa", $d)."<br>";
// echo "Today date is " . date("Y-m-d h:i:sa", $today)."<br>";