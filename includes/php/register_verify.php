<?php
    // On démarre la session
    session_start();

    // On inclut le fichier de connexion à la base de données
    include('connect_db.php');


    /*=============================================
    =         Vérification de l'username          =
    =============================================*/

    function verifUsername($bdd, $username) {

        // On stocke la longueur du pseudo
        $usernameLength = strlen($username);

        // On définit une longueur minimum et maximum pour le pseudo
        $usernameMinLength = 3;
        $usernameMaxLength = 16;

        // On créer un regex pour vérifier le pseudo
        $regexUsername = "#^[a-zA-Z0-9_]+$#";

        // Si le pseudo est d'une taille valide
        if($usernameLength >= $usernameMinLength && $usernameLength <= $usernameMaxLength) {
                    
            // Si le pseudo est validé par le regex
            if(preg_match($regexUsername, $username)) {

                // On cherche dans la base de donnée un username qui correspond à celui reçu en POST
                $selectUser = $bdd->prepare('SELECT username FROM membres WHERE username = :username');
                $selectUser->execute(array(
                    'username' => $username
                ));

                // Si aucun utilisateur portant ce nom existe
                if($selectUser->rowCount() == 0) {

                    // L'username est valide
                    return true;

                // Sinon si un utilisateur portant ce nom existe
                } else {
                                
                    // On stocke le message d'erreur dans la variable session pour l'afficher sur la page d'inscription
                    $_SESSION['errorUsernameMessage'] = "Ce pseudo est déjà pris par un autre utilisateur.";
                }

            // Sinon si le pseudo n'est pas validé par le regex
            } else {
                
                // On stocke le message d'erreur dans la variable session pour l'afficher sur la page d'inscription
                $_SESSION['errorUsernameMessage'] = "Le pseudo est invalide.";
            }  
        
        // Sinon si le pseudo n'est pas d'une taille valide
        } else {

            // On stocke le message d'erreur dans la variable session pour l'afficher sur la page d'inscription
            $_SESSION['errorUsernameMessage'] = "Le pseudo doit être de minimum " . $usernameMinLength .  " caractères et maximum " . $usernameMaxLength . " caractères.";
        }
    }


    /*=============================================
    =            Vérification du Mail             =
    =============================================*/

    function verifMail($mail) {

        // Si le mail est validé par le regex
        if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {

            // Le mail est valide
            return true;

        // Sinon si le mail n'est pas validé par le regex
        } else {

            // On stocke le message d'erreur dans la variable session pour l'afficher sur la page d'inscription
            $_SESSION['errorMailMessage'] = "L'e-mail est invalide.";
        }
    }

    
    /*=============================================
    =          Vérification du password           =
    =============================================*/

    function verifPassword($password, $passwordConfirm) {
        
        // On stocke la longueur du mot de passe
        $passwordLength = strlen($password);

        // On définit une taille minimum et maximum pour le mot de passe
        $passwordMinLength = 6;
        $passwordMaxLength = 60;

        // Si le mot de passe correspond à la confirmation du mot de passe
        if($password == $passwordConfirm) {
                                
            // Si le mot de passe est d'une taille valide
            if($passwordLength >= $passwordMinLength && $passwordLength <= $passwordMaxLength)  {
        
                return true;

            // Sinon si le mot de passe n'est pas d'une taille valide
            } else {

                // On stocke le message d'erreur dans la variable session pour l'afficher sur la page d'inscription
                $_SESSION['errorPasswordMessage'] = "Le mot de passe doit être de minimum " . $passwordMinLength . " caractères et maximum " . $passwordMaxLength . " caractères.";
            }

        // Sinon si le mot de passe ne correspond pas à la confirmation du mot de passe
        } else {
    
            // On stocke le message d'erreur dans la variable session pour l'afficher sur la page d'inscription
            $_SESSION['errorPasswordMessage'] = "La confirmation du mot de passe est incorrect.";
        }
    }


    /*=============================================
    =      Ajout d'un utilisateur dans la DB      =
    =============================================*/
    
    function addNewUser($bdd, $username, $mail, $password) {
                                           
        // On créer un hash de son mot de passe
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // On insere le nouvel utilisateur dans la base de données
        $insertUser = $bdd->prepare('INSERT INTO membres (username, password, mail, date_register) VALUES (:username, :password, :mail, NOW())');
        $insertUser->execute(array(
            'username' => $username,
            'password' => $passwordHash,
            'mail' => $mail
        ));
    }


    /*=============================================
    =                   MAIN                      =
    =============================================*/

    // Si on est déjà connecté
    if(isset($_SESSION['id']) && isset($_SESSION['username'])) {
        
        // On envoi un header de redirection HTTP
        header('Location: ../../index.php');

    // Sinon si l'utilisateur n'est pas déjà connecté
    } else {

        // Si tout les champs on bien été envoyé
        if(isset($_POST['username']) && isset($_POST['mail']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
        
            // On stocke dans des variables les champs qui ont été envoyé
            $username = $_POST['username'];
            $mail = $_POST['mail'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['confirmPassword'];

            // Si les champs ne sont pas vide.
            // Une variable est considéré comme vide par empty() si elle contient 0, on blinde donc notre condition
            if( (!empty($username) || $username == "0") && (!empty($mail) || $mail == "0") && (!empty($password) || $password == "0") && (!empty($passwordConfirm) || $passwordConfirm == "0") ) {

                // On appel les fonctions qui vont vérifier si l'username, le mail et le pseudo sont valide
                $usernameValid = verifUsername($bdd, $username);
                $mailValid = verifMail($mail);
                $passwordValid = verifPassword($password, $passwordConfirm);

                // Si l'username, le mail et le password sont valide 
                if($usernameValid && $mailValid && $passwordValid) {
                    
                    // On appel la fonction qui va ajouter un utilisateur
                    addNewUser($bdd, $username, $mail, $password);
                    
                    // On stocke le message de réussite dans la variable session pour l'afficher sur la page d'inscription
                    $_SESSION['successMessage'] = "Inscription réussie !";

                    // On envoi un header de redirection HTTP
                    header('Location: ../../register.php');

                // Sinon si l'username, le mail et le password ne sont pas valide
                } else {
            
                    // On envoi un header de redirection HTTP
                    header('Location: ../../register.php');
                }
                                         
            // Sinon si les champs sont vide 
            } else {

                // On stocke le message d'erreur dans la variable session pour l'afficher sur la page d'inscription
                $_SESSION['errorMessage'] = "Veuillez remplir tout les champs.";

                // On envoi un header de redirection HTTP
                header('Location: ../../register.php');
            }

        // Sinon si tout les champs n'on pas été envoyé
        } else {

            // On envoi un header de redirection HTTP
            header('Location: ../../register.php');
        }
    }
?>
