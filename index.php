<?php
// Slå på all felrapportering. Bra under utveckling, dåligt i produktion.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "private.php";

//connect to database
$dbh = new PDO('mysql:host=localhost;dbname=zoo;charset=UTF8', $user,$password);

//define search variables
$input = "";
$message = "";
$menuInput = "";
$textInput = "";
// $selection;
$sentence = "";
$result = "";
// $results = array();
// $tableheader = "";


//select all animals
$queryAll = "SELECT * FROM animals";

$statementAll = $dbh->prepare($queryAll, array(PDO::FETCH_ASSOC));    

$statementAll->execute();

$allAnimals = $statementAll->fetchAll();


//get input from user's search
if(isset($_POST['search'])) {

    $message = "";

    //setting $input variable from user's selection
    if(isset($_POST['userText']) && ($_POST['userText'] != "")) {
        $textInput = cleanData($_POST['userText']);   
        
        // check if text input only contains letters and whitespace
        // incorrect input values will nullify user input
        if (!preg_match("/^[a-zA-ZåäöÅÄÖ ]*$/",$textInput)) {
            $message = "Du behöver ange giltigt namn format";
            unset($textInput);    
        } else {
            $input = $textInput;
        }

    } else if(isset($_POST['animal']) && ($_POST['animal'] != "")){
        //setting $menuInput variable from user's selection
        $menuInput = $_POST['animal'];
        $input = $menuInput; 

    } else if (($_POST['animal']) == "" && ($_POST['userText']) == "") {
        $message = "Du behöver ange ett sökvärde";
    } 
 

    // if (!(isset($selection))  ) {
    //     //echo count($selection);
    //     $message = "Sökträff inte hittade";
    // }

    //PLEASE CONFIRM IF THIS IS NECESSARY
    //if either input is "alla" - populate website with $allAnimals  
    // if($menuInput == "alla" || $textInput == "alla") {
    //     foreach ($allAnimals as $animal) {
    //         echo $animal['name']."<br>";
    //     } 

        // unset($menuInput);
        // unset($textInput);
    // }

}

//a function to sanitise user text input
function cleanData ($data) {
    $data = trim($data);        
    $data = stripslashes($data);
    $data = htmlspecialchars($data);    
    return $data;
}


//select an animal 
$query = "SELECT * FROM animals where name = :name";

$statement = $dbh->prepare($query, array(PDO::FETCH_ASSOC));  

$statement->execute(array(':name' => $input));

$selection = $statement->fetchAll();

// echo "<pre>";
// var_dump($selection);
// echo "</pre>";

if(($selection)){
    $result = $selection[0]['name'];
    $sentence = "<div><p>Namn: ". $selection[0]['name'] ."</p><p>Kategori: ".$selection[0]['category']."</p><p>Födelsedatum: ".$selection[0]['birthday']."</p></div>";
} 




//select an animal from input box
// $query = "SELECT * FROM animals where name like :name";

// $statement = $dbh->prepare($query, array(PDO::FETCH_ASSOC));  

// $statement->execute(array(':name' => $textInput));

// $textOutput = $statement->fetchAll();

// $results[] = $textOutput;

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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Djurparken</h1>
    <p id = "about">Här kan du läsa intressanta fakta om djuren</p id = "info">

    <!-- Data Input from User -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"     
        method="post"
    >
        <label for="animal">Välj ett djur:</label>
        <select name="animal" id="animal">
        <option value="">   -- välj --  </option>
        <!-- <option value="alla">  Alla djur </option> -->
        <?php
            foreach ($allAnimals as $animal) {
                echo "<option>".$animal['name']."</option>";
            } 
        ?>
        </select>
        <span><b>eller</b></span>
        <label for="search">Sök efter namn: </label>
        <input type="text" name="userText">
        <div>
            <input name="search" type="submit">        
        </div>
        
    </form>

    <p id = "message"><?php echo $message; ?></p>

    <!-- Results from search -->
    <div>
        <?php
            // foreach ($results as $animal) {
                echo $result;
                echo $sentence;
            // } 
        ?>          
    </div>
    <!-- <div><p>Namn: </p><p>Kategori: </p><p>Födelsedatum: </p></div> -->

</body>
</html>
