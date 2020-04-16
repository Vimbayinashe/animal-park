<?php
// Slå på all felrapportering. Bra under utveckling, dåligt i produktion.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbh = new PDO('mysql:host=localhost;dbname=zoo;charset=UTF8', "zookeeper", "mhuka");

$queryAll = "SELECT * FROM animals";

$statementAll = $dbh->prepare($queryAll, array(PDO::FETCH_ASSOC));    

$statementAll->execute();

$animals = $statementAll->fetchAll();

echo "<pre>";
var_dump($animals);
echo "</pre>";

//select by name
$query = "SELECT * FROM animals where name = ?";

$statement = $dbh->prepare($query, array(PDO::FETCH_ASSOC));  

$statement->execute(array('Gädda'));

$selection = $statement->fetchAll();

echo "<pre>";
var_dump($selection);
echo "</pre>";


$query = "SELECT * FROM animals where name like :name";

$statement = $dbh->prepare($query, array(PDO::FETCH_ASSOC));  

$statement->execute(array(':name' =>'Tor%'));

$selection = $statement->fetchAll();

echo "<pre>";
var_dump($selection);
echo "</pre>";



// Är det här  ett säkert sätt att skriva SQL query?

// Jag förväntar mig att en användare kommer att skriva en del ett namn istället av hela namnet och jag vill kunna hantera detta. 