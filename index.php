<?php
// Slå på all felrapportering. Bra under utveckling, dåligt i produktion.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "private.php";

$dbh = new PDO('mysql:host=localhost;dbname=zoo;charset=UTF8', $user, $password);

//select all animals
$queryAll = "SELECT * FROM animals";

$statementAll = $dbh->prepare($queryAll, array(PDO::FETCH_ASSOC));    

$statementAll->execute();

$allAnimals = $statementAll->fetchAll();

// echo "<pre>";
// var_dump($allAnimals);
// echo "<br>";
// echo "</pre>";

//get input from user's search
if(isset($_POST['search'])) {
    $menuInput = $_POST['animal'];
    $textInput = $_POST['userText'];
    // echo $menuInput;
    // echo $textInput;

    if(isset($_POST['userText'])) {
        $textInput = cleanData($_POST['userText']);   
        
        // check if text input only contains letters and whitespace
        if (!preg_match("/^[a-zA-ZåäöÅÄÖ ]*$/",$textInput)) {
            echo "incorrect text syntax";
            $syntaxError = "ange giltigt namn format";
        } else {
            $syntaxError = "";
            echo $textInput;
        }     
    } else {
        $textInput = "";
    }

    if(isset($_POST['animal'])){
        $menuInput = $_POST['animal'];
        echo $menuInput;
    }
}

//a function to sanitise user text input
function cleanData ($data) {
    $data = trim($data);        
    $data = stripslashes($data);
    $data = htmlspecialchars($data);    
    return $data;
}


//select an animal from drop-down menu
$query = "SELECT * FROM animals where name = ?";

$statement = $dbh->prepare($query, array(PDO::FETCH_ASSOC));  

$statement->execute(array('Gädda'));

$selection = $statement->fetchAll();

echo "<pre>";
var_dump($selection);
echo "</pre>";

//select an animal from input box
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


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"     
        method="post"
    >
        <label for="animal">Välj ett djur:</label>
        <select name="animal" id="animal">
        <option value="">  -- välj -- </option>
        <?php
            foreach ($allAnimals as $animal) {
                echo "<option>".$animal['name']."</option>";
            } 
            ?>
        </select>

        <label for="search">Sök efter namn: </label>
        <input type="text" name="userText">
        <div>
            <input name="search" type="submit">        
        </div>
        
    </form>
    <!-- <p><?php echo $success; ?></p> -->


</body>
</html>
