<?php
function connectdb(){
    $username = "root";
    $password = "root";
    $dbname = "mabdd";
    $host = 'localhost';

    try {
        $bdd = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $username, $password); // /!\ port 3307
    } catch (PDOException $e) {
        echo 'Erreur de connexion : ' . $e->getMessage();
        exit();
    }
    return $bdd;
}
?>
