<?php
foreach ($_POST as $key => $val) {
    echo "Key: {$key} = {$val} <br>";
}

if (isset($_POST['lat'])) {
    $mylat = $_POST['lat'];
    echo "My Lat: <b>{$mylat} </b><br>";
}
else {echo "No lat";}

if (isset($_POST['long'])) {
    $mylong = $_POST['long'];
    echo "My Long: <b>{$mylong}</b> ";
}
else {echo "No long";}
?>

<!DOCTYPE html> 
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>My first PHP website</h1>

    <form method="post" action="">
        Lat: <input type="text" name="lat"><br>
        Long:<input type="text" name="long" ><br>
        Alt:<input type="text" name="alt"><br>
        <input type="submit">


    </form>

    <?PHP
    echo "Hello World!";
    echo "<br>";
    echo "<p> This is <b>paragram</b></p>";

    echo "<p style='color: red;'>Hello World!<p>";

    $a = 5;
    $b = 5.34;
    $c = "25";
    $d = $a + $b;
    $e = $a + $c;

    echo $e;
    echo "<br>";
    echo $d; 
    echo "<br>";
    echo "$a  $b  $c";

    echo "<table>
        <tr>
            <td>First Number</td>
            <td>1</td>        
        </tr>
         <tr>
            <td>a</td>
            <td>{$a}</td>        
        </tr>
         <tr>
            <td>b</td>
            <td>{$b}</td>        
        </tr>
    </table><br>";

    // Genarel Array
    $myArray = ["dog","cat","fish"];
    echo "{$myArray[0]} {$myArray[1]} {$myArray[2]}";
    echo "<br>";

    // Associative Array (Javascript Object)
    $myAssoArray = ["color"=>"red","brand"=>"Toyota"];
    echo "My car color is <b>{$myAssoArray['color']}</b>";
    


    ?>
</body>

</html>