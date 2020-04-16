<?php
// Slå på all felrapportering. Bra under utveckling, dåligt i produktion.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "private.php";

$dbh = new PDO('mysql:host=localhost;dbname=zoo;charset=UTF8', $user, $password);

$queryAll = "SELECT * FROM animals";

$statementAll = $dbh->prepare($queryAll, array(PDO::FETCH_ASSOC));    

$statementAll->execute();

$allAnimals = $statementAll->fetchAll();

// echo "<pre>";
// var_dump($allAnimals);
// echo "<br>";
// echo "</pre>";


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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Djurparken</title>
</head>
<body>
    <h1></h1>

    <label for="animal">Välj ett djur:</label>
    <select name="animal" id="animal">
    <option value="">  </option>
    <?php
        foreach ($allAnimals as $animal) {
            echo "<option value =".$animal['id'].">".$animal['name']."</option>";
        } 
    ?>
    </select>

</body>
</html>
