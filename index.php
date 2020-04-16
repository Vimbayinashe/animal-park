<?php
// Slå på all felrapportering. Bra under utveckling, dåligt i produktion.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "private.php";

//connect to database
$dbh = new PDO('mysql:host=localhost;dbname=zoo;charset=UTF8', $user,$password);

//define search variables
$message = "";
$menuInput = "";
$results = array();
$textInput = "";
$tableheader = "";


//select all animals
$queryAll = "SELECT * FROM animals";

$statementAll = $dbh->prepare($queryAll, array(PDO::FETCH_ASSOC));    

$statementAll->execute();

$allAnimals = $statementAll->fetchAll();


//get input from user's search
if(isset($_POST['search'])) {

    $message = "";

    //setting $textInput variable from user's selection
    if(isset($_POST['userText'])) {
        $textInput = cleanData($_POST['userText']);   
        
        // check if text input only contains letters and whitespace
        // incorrect input values will nullify user input
        if (!preg_match("/^[a-zA-ZåäöÅÄÖ ]*$/",$textInput)) {
            $message = "ange giltigt namn format";
            $textInput = "";    
        } else {
            $syntaxError = "";
            // echo $textInput;
        }     
    } else {
        $textInput = "";
    }
    
    //setting $menuInput variable from user's selection
    if(isset($_POST['animal'])){
        $menuInput = $_POST['animal'];
        // echo $menuInput;
    } else {
        $menuInput = "";
    }

    //handling same search values so that only one result is shown
    if ($menuInput == $textInput) {
        unset($menuInput);
        unset($textInput);
        $message = "Gör endast ett val";
    } 

    //handling empty search values from both inputs
    if($menuInput == "" && $textInput == "") {
        $message = "Du behöver ange ett sökvärde";
    }

    //PLEASE CONFIRM IF THIS IS NECESSARY
    //if either input is "alla" - populate website with $allAnimals  
    if($menuInput == "alla" || $textInput == "alla") {
        foreach ($allAnimals as $animal) {
            echo $animal['name']."<br>";
        } 
        // $menuInput = "";
        // $textInput = "";
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

$statement->execute(array($menuInput));

$menuOutput = $statement->fetchAll();

$results[] = $menuOutput;


//select an animal from input box
$query = "SELECT * FROM animals where name like :name";

$statement = $dbh->prepare($query, array(PDO::FETCH_ASSOC));  

$statement->execute(array(':name' => $textInput));

$textOutput = $statement->fetchAll();

$results[] = $textOutput;

// echo "<pre>";
// var_dump($results);
// echo "</pre>";

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

    <p><?php echo $message; ?></p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"     
        method="post"
    >
        <label for="animal">Välj djur:</label>
        <select name="animal" id="animal">
        <option value="">   -- välj --  </option>
        <option value="alla">  Alla djur </option>
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

    <div>
        <?php
            foreach ($results as $animal) {
                if(isset($animal[0])) {
                    echo "<p>".$animal[0]['name']."</p>";
                }
            } 
        ?>          
    </div>


</body>
</html>
