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
$uploadMessage = "";

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

//File Uploading
if ($_FILES) {

    $uploadDir = "./UserImages/";
    $uploadPath = $uploadDir . basename($_FILES['fileToUpload']['name']);
    unset($uploadMessage);

    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadPath)) {
        $uploadMessage = basename( $_FILES["fileToUpload"]["name"])." är uppladdad";
    } else {
        $uploadMessage = "Något gick fel";
    }
}


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

    
    <!-- Data Input from User -->
    <div id = "queries">
        <div>
            <p class = "info">Här kan du läsa intressanta fakta om djuren</p>

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
                <div class = "button">
                    <input name="search" type="submit">        
                </div>
            </form>

            <p id = "message"><?php echo $message; ?></p>
        </div>

        <!-- File Uploading -->
        <div id = "file-upload">
            <p class = "info">Här kan du ladda upp bilder</p>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
                method="post" 
                enctype="multipart/form-data"
            >
                <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
                <input type="file" name="fileToUpload" id="fileToUpload"/>
                <div class = "button">
                    <input type="submit" name="upload" value="Ladda upp fil" />
                </div>
            </form>
            <div id = "upload-message"> <?php echo $uploadMessage; ?> </div>
        </div>

    </div>

    <!-- Results from search -->
    <div id = "result"> <?php echo $sentence; ?> </div>


</body>
</html>
