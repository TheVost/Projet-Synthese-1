<?php

/**
 * @file deconnexion.php
 * @author Randi Skjerven et Cédric Devost
 * @version 1.0
 * @date 31 Janvier 2019
 * @brief page pour gérer la déconnexion d'un usager
 */

session_start();
session_unset();
session_destroy();
header('Location: ../index.php'); 
?>