<?php
/**
 * @file sessionUtilisateur.php
 * @author Randi Skjerven et Cédric Devost
 * @version 1.0
 * @date 24 Novembre 2019
 * @brief permet de voir si l'admin est coinnecté, sinon redirrige vers la page de connexion. utilisé pour la mpage admin.php
 */
session_start();

if (!isset($_SESSION['administrateur'])) {
    // redirection vers la page authentification.php
    // pour la saisie de l'identifiant et du mot de passe 
    header('Location: connexion.php'); }

?>