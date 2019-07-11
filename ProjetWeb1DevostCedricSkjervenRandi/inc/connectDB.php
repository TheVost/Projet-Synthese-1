<?php
/**
 * @file connectDB.php
 * @author Randi Skjerven et Cédric Devost
 * @version 1.0
 * @date 24 Novembre 2019
 * @brief assure la connection à la base de donné pour le gym de quartier
 */  
require_once("paramConnectDB.php");

$conn = mysqli_connect(HOST, USER, PASSWORD, DBNAME);

if (!$conn) {
?>
   <p>Erreur de connexion :
      <?php echo mysqli_connect_errno()." – ".utf8_encode(mysqli_connect_error()) ?>
   </p> 
<?php 
   exit;
}

mysqli_set_charset($conn, "utf8");
