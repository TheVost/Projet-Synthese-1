<?php
/**
 * @file sql.php
 * @author Randi Skjerven et Cédric Devost
 * @version 1.0
 * @date 18 Janvier 2019
 * @brief fonctions reliées à l'application "Gym de Quartier"
 */


/**
 * @brief Afficher le message d'erreur de la dernière "query" SQL 
 *
 * @details Si la base de donnée "gym de quartier" retourne une erreur suite à requete, cette erreur 
 * s'affichera sur la page en cours
 *
 * @param $conn = contexte de connexion
 *
 * @return aucun
 */
function errSQL($conn) {
    ?>
    <p>Erreur de requête :
    <?php echo mysqli_errno($conn)." – ".mysqli_error($conn) ?>
    </p>
<?php 
}

/**
 * @brief Contrôler l'authentification de l'utilisateur dans la table 'utilisateurs'
 *
 * @details Valide les paramètres d'entrées "utilisateur_courriel" et "utilisateur_mot_de_passe" dans la table
 * utilisateur de la base de donnée gymQuartier
 *
 * @param $conn = contexte de connexion
 * @param $identifiant = string
 * @param $mot_de_passe = string
 *
 * @return string (client / instructeur / administrateur) ou booléen (false)
 */
function sqlControlerUtilisateur($conn, $identifiant, $mot_de_passe) {
    
    // requête SQL pour valider les information de connexion
    $req = "SELECT * FROM utilisateurs
            WHERE utilisateur_courriel=? AND utilisateur_mot_de_passe = SHA2(?, 256)";
        
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ss", $identifiant, $mot_de_passe); 
        
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $row = array();
        
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            
            // retourne l'information de la colonne "utilisateur_type" pour gestion de type de connextion 
            return $row['utilisateur_type'];
        } else {
            // retourne la valeur "false" si $identifiant et $mot_de_passe ne correspond à aucun utilisateur de la table
            return false;
        }
    }
    
}

/**
 * @brief ajouter un utilisateur, activité ou cours à l'horaire
 *
 * @details ajout d’une nouvelle ligne dans la table 'utilisateurs', 'activités' ou 'horaires'.  
 * La table impactée sera sélectionnée par le paramètre $type 
 *
 * @param $conn = contexte de connexion
 * @param $inscription = array (string)
 * @param $type = string (utilisateur, cours, activité)
 * @param $utilisateur_type  = string (client, entraineur ou administrateur)
 *
 * @return booléen (1 = complété, 0 = non-complété)
 */


function sqlAjouter($conn, $inscription, $type, $utilisateur_type) {
    
    // requête SQL pour la table "Utilisateur"
    if ($type == "utilisateur") {
         $req = "INSERT INTO utilisateurs (utilisateur_id, utilisateur_nom, utilisateur_prenom, utilisateur_courriel, utilisateur_adresse_rue, utilisateur_adresse_ville, utilisateur_adresse_cp, utilisateur_pays, utilisateur_type, utilisateur_fk_paiment_id, utilisateur_mot_de_passe) VALUES (default, ?, ?, ?, ?, ?, ?, 'canada', '$utilisateur_type', ?, SHA2(?, 256))";
        
         $stmt = mysqli_prepare($conn, $req);
         mysqli_stmt_bind_param($stmt, "ssssssis", $inscription[0], $inscription[1], $inscription[2], $inscription[3], $inscription[4], $inscription[5], $inscription[6], $inscription[7]);
    }
    
    // requête SQL pour la table "horaires"
    else if ($type == "cours") {
         $req = "INSERT INTO horaires (horaire_duree, horaire_date, horaire_heure, horaires_fk_activite_id, horaire_fk_entraineur_id) VALUES (?, ?, ?, ?, ?)";
        
         $stmt = mysqli_prepare($conn, $req);
         mysqli_stmt_bind_param($stmt, "isiii", $inscription[0], $inscription[1], $inscription[2], $inscription[3], $inscription[4]);
    }
    
    // requête SQL pour la table "activites"
    else if ($type == "activite") {
         $req = "INSERT INTO activites (activite_nom, activite_max_inscription) VALUES (?, ?)";
        
         $stmt = mysqli_prepare($conn, $req);
         mysqli_stmt_bind_param($stmt, "si", $inscription[0], $inscription[1]);
    }

    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * @brief modifier un utilisateur, un cours à l’horaire, une activité ou une inscription
 *
 * @details mettre à jour toutes les valeurs d’une ligne avec les nouvelles informations 
 * fourni dans la table 'utilisateurs', 'horaires', 'activites' ou 'utilisateurs_horaires'. 
 * La table sera sélectionnée par le paramètre $type
 *
 * @param $conn = contexte de connexion
 * @param $modification = array[string]
 * @param $type = string (cours/utilisateur/activité/inscription)
 * @param $id = int (id du cours, utilisateur, activité)
 *
 * @return booléen (1 = complété, 0 = non-complété)
 */


function sqlModification($conn, $modification, $type, $id) {
   // requête SQL pour la table "horires" 
   if ($type == "cours") {

        $req = "UPDATE horaires 
                SET horaire_duree = ?, horaire_date = '$modification[1]', horaire_heure = ?, horaires_fk_activite_id = ?, horaire_fk_entraineur_id = ?
                WHERE horaire_id=".$id;
    
        $stmt = mysqli_prepare($conn, $req);
        mysqli_stmt_bind_param($stmt, "iiii", $modification[0],  $modification[2],$modification[3], $modification[4]);
   }
   // requête SQL pour la table "utilisateurs" 
   else if ($type == "utilisateur"){

        $req = "UPDATE utilisateurs 
                SET utilisateur_nom  = ?, utilisateur_prenom  = ?, utilisateur_courriel  = ?, utilisateur_adresse_rue = ?, utilisateur_adresse_ville  = ?, utilisateur_adresse_cp = ?,   utilisateur_type = ?, utilisateur_fk_paiment_id = ?
                WHERE utilisateur_id=".$id;
    
        $stmt = mysqli_prepare($conn, $req);
        mysqli_stmt_bind_param($stmt, "sssssssi", $modification[0], $modification[1], $modification[2], $modification[3], $modification[4], $modification[5], $modification[6], $modification[7] );
            }
    // requête SQL pour la table "activites" 
    else if ($type == "activite"){

        $req = "UPDATE activites 
                SET activite_nom = ?, activite_max_inscription  = ?, activite_status  = ?
                WHERE activite_id=".$id;
    
        $stmt = mysqli_prepare($conn, $req);
        mysqli_stmt_bind_param($stmt, "sis", $modification[0], $modification[1], $modification[2]);
            }
    // requête SQL pour la table "utilisateurs_horaires" 
    else if ($type == "inscription"){

        $req = "UPDATE utilisateurs_horaires 
                SET utilisateur_horaires_accepte = ?
                WHERE uh_fk_utilisateur_id= ? AND uh_fk_horaire_id= ?";
    
        $stmt = mysqli_prepare($conn, $req);
        mysqli_stmt_bind_param($stmt, "iii", $modification[2], $modification[0], $modification[1]);
            }

    if (mysqli_stmt_execute($stmt)) {
        return mysqli_stmt_affected_rows($stmt);
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * @brief supprimer un cours à l’horaires ou un utilisateur inscrit à un cours
 *
 * @details supprime une ligne dans la table 'horaires' ou 'tilisateurs_horaires'. 
 * Pour ta table 'horaire', S’il y a des clients inscrit dans un cours à l’horaire, 
 * la fonction supprimera tous les utilisateurs (clé étrangère) 
 *
 * @param $conn = contexte de connexion
 * @param $type = string (cours / utilisateur)
 * @param $id = int (id du cours ou utilisateur)
 * @param $inscrits = int (nombre de client inscrit à un cours)
 *
 * @return booléen (1 = complété, 0 = non-complété) 
*/

function sqlSupprimer($conn, $type, $id, $inscrits) {
    
    if ($type == "cours") {
        if($inscrits > 0){
            $req = "DELETE utilisateurs_horaires, horaires
            FROM utilisateurs_horaires 
            LEFT JOIN horaires ON horaire_id = uh_fk_horaire_id
            WHERE uh_fk_horaire_id =".$id;
        }
        else{
            $req = "DELETE FROM horaires WHERE horaire_id=".$id;
        }
    }
    if ($type == "utilisateur") {
        $req = "DELETE
                FROM utilisateurs_horaires
                WHERE uh_fk_utilisateur_id =".$id." AND uh_fk_horaire_id =".$inscrits;
    }
    
    if (mysqli_query($conn, $req)) {
        return 1;
    } else {
        errSQL($conn);
        exit;
    }
  }

/**
 * @brief Afficher le nom de l'utilisateur connecté
 *
 * @details extrait le nom complet d’un utilisateur selon son adresse courriel dans la table ‘utilisateur’ 
 *
 * @param    $conn = contexte de connexion
 * @param    $user = string (courriel de l'utilisateur)
 *
 * @return   string (nom complet de l'utilisateur) ou booléen (false)
 *
 */

function afficherUser($conn, $user){
    $req = "SELECT  CONCAT(utilisateur_prenom, ' ', utilisateur_nom) AS nom
            FROM utilisateurs
            WHERE utilisateur_courriel=?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "s", $user); 
        
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $row = array();
        
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            
            return $row['nom'];
        } else {
            return false;
        }
    }
}

/**
 * @brief Afficher les informations pour le cours concerné
 *
 * @details extrait les toutes les informations nécessaires pour l’affichage d’un cours, 
 * selon la date et l’heure selectionnés, de la table 'horaire' en jonction avec la table 
 * 'utilisateurs', 'activité', 'utilisateurs_horaires'
 *
 * @param    $conn = contexte de connexion
 * @param    $date = string (la date du cours)
 * @param    $heure = string (l'heure de l'Activité)
 *
 * @return   array  = $liste[cours_nom, cours_activite, cours_inscrits, cours_attente,  
 * cours_max, cours_duree, cours_id]
 *
*/

function afficherInfoCours($conn, $date, $heure){
    $req = "SELECT CONCAT(U.utilisateur_prenom, ' ', U.utilisateur_nom) AS nom,         
            A.activite_nom AS activite,
            (SELECT COUNT(DISTINCT uh_fk_utilisateur_id)
            FROM horaires AS H
            INNER JOIN utilisateurs_horaires As UH ON UH.uh_fk_horaire_id = H.horaire_id
            WHERE H.horaire_date =? AND H.horaire_heure =?) AS inscrits, 
           (SELECT COUNT(utilisateur_horaires_accepte)
            FROM horaires AS H
            INNER JOIN utilisateurs_horaires As UH ON UH.uh_fk_horaire_id = H.horaire_id
            WHERE H.horaire_date =? AND H.horaire_heure =? AND UH.utilisateur_horaires_accepte =0) as attentes, 
            A.activite_max_inscription AS max, H.horaire_duree AS duree, H.horaire_id AS id
            FROM horaires AS H
            INNER JOIN activites AS A ON A.activite_id = H.horaires_fk_activite_id
            INNER JOIN utilisateurs AS U ON U.utilisateur_id = H.horaire_fk_entraineur_id
            WHERE H.horaire_date =? AND H.horaire_heure =?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ssssss", $date, $heure, $date, $heure, $date, $heure);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                
                        $liste [] = array(
                                    'cours_nom'             => $row['nom'],
                                    'cours_activite'        => $row['activite'],
                                    'cours_inscrits'        => $row['inscrits'],
                                    'cours_attente'        => $row['attentes'],
                                    'cours_max'             => $row['max'],
                                    'cours_duree'           => $row['duree'],
                                    'cours_id'              => $row['id']
                                    );
            }
        }
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
* @brief Afficher les inscrits à l'activité concernée
*
* @details extraction du nom complet et du id des utilisateurs inscrit à un cours à l’horaire dans la table ‘utilisateurs_horaires’ en jonction avec la table ‘utilisateurs’ et la table ‘horaires’ ainsi que le statut de du paiement
*
* @param    $conn = contexte de connexion
* @param    $date = string (date du cours)
* @param    $heure = string (heure du cours)
*
* @return   array = $liste[inscrit_nom, inscrit_status, utilisateur_id]
*/

function listerInscrits($conn, $date, $heure){
    
    $req = "SELECT CONCAT(U.utilisateur_prenom, ' ', U.utilisateur_nom) AS nom,          
            UH.utilisateur_horaires_accepte AS status,
            U.utilisateur_id as utilisateur_id
            FROM utilisateurs AS U 
            INNER JOIN utilisateurs_horaires AS UH ON UH.uh_fk_utilisateur_id = U.utilisateur_id
            INNER JOIN horaires AS H ON H.horaire_id = uh_fk_horaire_id
            WHERE H.horaire_date =? AND H.horaire_heure =?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ss", $date, $heure);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                
                        $liste [] = array(
                                    'inscrit_nom'           => $row['nom'],
                                    'inscrit_status'        => $row['status'], 
                                    'utilisateur_id'        => $row['utilisateur_id']
                                    );              
            }
        }
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
}
/**
 * @brief filtre pour la sélection des entraineurs, activités offertes et type de paiement
 *
 * @details extraction des id et nom de la table ‘utilisateurs’ avec un filtre pour les entraineurs, 
 * de la table ‘activités’ avec un filtre pour les actifs et de la table ‘paiments’
 *
 *
 * @param $conn = contexte de connexion
 * @param $type = string (entraineur / activite / paiement)
 *
 * @return array = $filtre[filtre_id, filtre_nom]
 */

function sqlFiltreSelection($conn, $type) {

    if ($type == "entraineur") { 
        
        $req = "SELECT 
            utilisateur_id as filtre_id, 
            concat(utilisateur_prenom, ' ', utilisateur_nom) as filtre_nom
            FROM `utilisateurs` 
            WHERE utilisateur_type = 'entraineur'"; 
    
    } else if ($type == "activite") {
        
        $req = "SELECT 
            activite_id as filtre_id, 
            activite_nom as filtre_nom
            FROM `activites`
            where `activite_status` = 'actif'"; 
        
    } else if ($type == "paiement") {
        
        $req = "SELECT 
            paiment_id as filtre_id, 
            paiment_nom as filtre_nom
            FROM `paiments`"; 
    }
          
    if ($result = mysqli_query($conn, $req, MYSQLI_STORE_RESULT)) {
        $nbResult = mysqli_num_rows($result);
        $filtre = array();
                
        if ($nbResult) {
             mysqli_data_seek($result, 0);
            
             while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    
                        $filtre [] = array(
                            'filtre_id'  => $row['filtre_id'],
                            'filtre_nom'  => $row['filtre_nom'],
                            );     
            }
        }
        mysqli_free_result($result);
        return $filtre;
        
    } else {
        errSQL($conn);
        exit;
            }
}

/**
 * @brief populer tableau avec la table horaires
 *
 * @details filtre permettant de populer les détails d'un cours selon un ID fournit par l'utilisateur 
 *  
 *
 * @param $conn = contexte de connexion
 * @param $type = string (entraineur / activite)
 * @param $id = int
 *
 * @return array = $liste(variable selon le type)
 */
function sqlFiltreDetail($conn, $type, $id) {

    if ($type == "cours")  { 
    $req = "SELECT * 
            FROM `horaires` 
            where horaire_id = $id";

        if ($result = mysqli_query($conn, $req, MYSQLI_STORE_RESULT)) {
            $nbResult = mysqli_num_rows($result);
            $detail = array();
                    
            if ($nbResult) {
                 mysqli_data_seek($result, 0);
                
                 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $detail [] = array(
                                        'horaire_id'       => $row['horaire_id'],
                                        'horaire_duree'    => $row['horaire_duree'],
                                        'horaire_date'     => $row['horaire_date'],
                                        'horaire_heure'    => $row['horaire_heure'],
                                        'activite_id'      => $row['horaires_fk_activite_id'],
                                        'entraineur_id'    => $row['horaire_fk_entraineur_id'],
                                        );
                 }
            }    
            
            mysqli_free_result($result);
            return $detail;
        } else {
            errSQL($conn);
            exit;
        }
   } else if ($type == "utilisateur")  { 
        $req = "SELECT * 
            FROM `utilisateurs` 
            where utilisateur_id = $id";

        if ($result = mysqli_query($conn, $req, MYSQLI_STORE_RESULT)) {
            $nbResult = mysqli_num_rows($result);
            $detail = array();
                    
            if ($nbResult) {
                 mysqli_data_seek($result, 0);
                
                 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $detail [] = array(
                                        'utilisateur_id'            => $row['utilisateur_id'],
                                        'utilisateur_nom'           => $row['utilisateur_nom'],
                                        'utilisateur_prenom'        => $row['utilisateur_prenom'],
                                        'utilisateur_courriel'      => $row['utilisateur_courriel'],
                                        'utilisateur_adresse_rue'   => $row['utilisateur_adresse_rue'],
                                        'utilisateur_adresse_ville' => $row['utilisateur_adresse_ville'],
                                        'utilisateur_adresse_cp'    => $row['utilisateur_adresse_cp'],
                                        'utilisateur_type'          => $row['utilisateur_type'],
                                        'utilisateur_fk_paiment_id' => $row['utilisateur_fk_paiment_id'],
                                        );
                 }
            }
            mysqli_free_result($result);
            return $detail;
        } else {
            errSQL($conn);
            exit;
        }
   } else if ($type == "activite")  { 
        $req = "SELECT * 
            FROM `activites` 
            where activite_id = $id";

        if ($result = mysqli_query($conn, $req, MYSQLI_STORE_RESULT)) {
            $nbResult = mysqli_num_rows($result);
            $detail = array();
                    
            if ($nbResult) {
                 mysqli_data_seek($result, 0);
                
                 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            $detail [] = array(
                                        'activite_id'                => $row['activite_id'],
                                        'activite_nom'               => $row['activite_nom'],
                                        'activite_max_inscription'   => $row['activite_max_inscription'],
                                        'activite_status'            => $row['activite_status'],
                                        );
                 }
            }
            mysqli_free_result($result);
            return $detail;
        } else {
            errSQL($conn);
            exit;
        }
   } 
}

/**
 * @brief sort une liste des usagers, peu importe leur type avec leur id et leur type
 *
 * @details extraction du nom complet, type et id selon un type choisi dans la table ‘utilisateurs’
 *
 * @param $conn = contexte de connexion
 * @param $type = string (client/entraineur/administrateur)
 *
 * @return array = $liste[utilisateur_nom, utilisateur_type, utilisateur_id]
 */
function listerUsagers($conn, $type){
    $req="SELECT CONCAT(utilisateur_prenom, ' ', utilisateur_nom) AS nom, utilisateur_type as type, utilisateur_id AS id
    FROM utilisateurs
    WHERE utilisateur_type =?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "s", $type);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                               
                        $liste [] = array(
                                    'utilisateur_nom'            => $row['nom'],
                                    'utilisateur_type'           => $row['type'],
                                    'utilisateur_id'             => $row['id']
                                    );
                
            }
        }
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
 * @brief filtre pour extraire le type d’usager ou activité
 *
 * @details filtre pour extraire tous les ‘utilisateur_type’ de la table ‘utilisateur’ ou tous les ‘activite_status’ de la table ‘activites’
 *
 * @param $conn = contexte de connexion
 * @param $quoi = le type de l'élément à sortir la liste, soit des utilisateur, ou des activités
 *
 * @return array = $liste[type]
 */

function listerType($conn, $quoi){
    
    if($quoi == "utilisateur"){
        $req="SELECT utilisateur_type AS type
        FROM utilisateurs
        GROUP BY utilisateur_type";
    }
    else if($quoi == "activite"){
        $req="SELECT activite_status AS type
        FROM activites
        GROUP BY activite_status";
    }
    
    if ($result = mysqli_query($conn, $req, MYSQLI_STORE_RESULT)) {
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                           
                        $liste [] = array(
                                    'type'           => $row['type']
                                    );       
            }
        }
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
* @brief afficher l'id de l'usager en question
*
* @details extraction de ‘utilisateur_id’ selon le nom complet dans la table ‘utilisateurs’
*
* @param    $conn = contexte de connexion
* @param    $user = string (le nom de l'usager)
*
* @return   string = $row['id'] ou booléen (false)
*
*/

function afficherUserId($conn, $user){
    $req = "SELECT utilisateur_id AS id
            FROM utilisateurs
            WHERE CONCAT(utilisateur_prenom, ' ', utilisateur_nom) =?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "s", $user); 
        
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $row = array();
        
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            
            return $row['id'];
        } else {
            return false;
        }
    }
}

/**
* @brief afficher l'id de la plage horraire en question
*
* @details extraction de ‘horaire_id’ selon l’heure et la date dans la table ‘horaires
*
* @param    $conn = contexte de connexion
* @param    $date = string (date de l'activité)
* @param    $heure = string (heure de l'Activité)
*
* @return   string = $row['id'] ou booléen (false)
*/

function afficherHoraireId($conn, $date, $heure){
    $req = "SELECT horaire_id AS id
            FROM horaires
            WHERE horaire_date =? AND horaire_heure =?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ss", $date, $heure); 
        
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $row = array();
        
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            
            return $row['id'];
        } else {
            return false;
        }
    }
}

/**
* @brief permet à un utilisateur de s'inscrire à une activité
*
* @details insertion de utilisateur_id, horaire_id et status non payer dans la table utilisateurs_horaires
*
* @param    $conn = contexte de connexion
* @param    $idUser = int (id le l'utilisateur)
* @param    $idHoraire = int (id de la plage horaire)
*
* @return   Booleen (1) ou string (message d'erreur)
*
*/

function inscrireAcrtivite($conn, $idUser, $idHoraire){
    $req = "INSERT INTO utilisateurs_horaires
            VALUES(?,?, 0)";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ii", $idUser, $idHoraire); 
        
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        return 1;
    } else {
        $message = "Vous êtes déjà inscrit!";
        return $message;
    }
}

/**
* @brief permet de trouver le nombre d'inscrits à une activité
*
* @details Compte les id des utilisateurs selon une plage horaire fourni dans la table ‘horaires’
*
* @param    $conn = contexte de connexion
* @param    $id = int (l'id l'activité)
*
* @return   Int = $row['insrits'] ou Booleen (false) 
*/

function trouverNbInscrits($conn, $id){
    $req = "SELECT COUNT(DISTINCT uh_fk_utilisateur_id) AS insrits
            FROM horaires AS H
            INNER JOIN utilisateurs_horaires As UH ON UH.uh_fk_horaire_id = H.horaire_id
            WHERE horaire_id =?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "i", $id); 
        
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $row = array();
        
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            
            return $row['insrits'];
        } else {
            return false;
        }
    }
}



/**
* @brief pour l’affichage d’activités offerts par l’établissement (actif) ou non (inactif)
*
* @details extraction de toutes les activités selon s’elle ont le status actif ou non-actif dans la table ‘activité’
*
* @param    $conn = contexte de connexion
* @param    $typeDebut = String (actif / non-actif)
*
* @return   array = $liste[activite_nom, activite_id]
*/

function afficherActivites($conn, $typeDebut){
    $req = "SELECT activite_nom AS nom, activite_id AS id
            FROM activites
            WHERE activite_status =?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "s", $typeDebut); 
        
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $liste = array();
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                           
                        $liste [] = array(
                                    'activite_nom'      => $row['nom'],
                                    'activite_id'       => $row['id']
                                    );       
            }
        }
        mysqli_free_result($result);
        return $liste;
    } else {
        errSQL($conn);
        exit;
    }
}

/**
* @brief pour l’affichage du nom complet d’un utilisateur
*
* @details extraction du nom complet de l’utilisateur selon son ID dans la table ‘utilisateur’
*
* @param    $conn = contexte de connexion
* @param    $id = int (id de l’utilisateur)
*
* @return   string = $row['user'] ou booléen (false)
*/

function trouverNom($conn, $id){
    $req = "SELECT CONCAT(utilisateur_prenom, ' ', utilisateur_nom) AS user
            FROM utilisateurs
            WHERE utilisateur_id =?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "i", $id); 
        
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $row = array();
        
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            
            return $row['user'];
        } else {
            return false;
        }
    }
}

/**
* @brief pour l’affichage du statut d’incription à un cours pour un utilisateur donnée et une plage horaire
*
* @details extraction de la valeur dans `utilisateur_horaires_accepte` selon un utilisateur ID et un horaire ID dans la table ‘utilisateurs_horaires’
*
* @param    $conn = contexte de connexion
* @param    $userID = int (id de l’utilisateur)
* @param    $horaireID = int (id de l’horaire)
*
* @return   booléen = $row['utilisateur_horaires_accepte'] ou int (2)
*/

function confirmationInscription($conn, $userID, $horaireID){
    $req = "SELECT utilisateur_horaires_accepte 
            FROM utilisateurs_horaires
            WHERE uh_fk_utilisateur_id =? and uh_fk_horaire_id=?";
    
    $stmt = mysqli_prepare($conn, $req);
    mysqli_stmt_bind_param($stmt, "ii", $userID, $horaireID); 
        
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $nbResult = mysqli_num_rows($result);
        $row = array();
        
        if ($nbResult) {
            mysqli_data_seek($result, 0);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            
         
            return $row['utilisateur_horaires_accepte'];
        } else {
            // retourne la valeur 2 si aucune valeur trouvé car $row['utilisateur_horaires_accepte'] est un booléen. 
            return 2;
        }
    }
}
?>