<?php
    // On démarre la session
    session_start();

    // Si on est déjà connecté
    if(isset($_SESSION['id']) && isset($_SESSION['username'])) {
        
        // On envoi un header de redirection HTTP
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Membre | Inscription</title>
    <link rel="stylesheet" href="includes/css/register.css">
</head>
<body>
    <h1>Inscription</h1>
    <form action="includes/php/register_verify.php" method="post">
        
        <input type="text" name="username" placeholder="Votre pseudo ...">
        <?php
            // Si la variable session contient un message d'erreur par rapport au pseudo
            if(isset($_SESSION['errorUsernameMessage'])) {
                
                // On affiche le message d'erreur
                echo "<span class='message_error'>" . $_SESSION['errorUsernameMessage'] . "</span>";

                // On supprime le message d'erreur de la variable session apres son affichage
                unset($_SESSION['errorUsernameMessage']);
            }
        ?>

        <input type="email" name="mail" placeholder="Votre e-mail ...">
        <?php
            // Si la variable session contient un message d'erreur par rapport à l'email
            if(isset($_SESSION['errorMailMessage'])) {
                
                // On affiche le message d'erreur
                echo "<span class='message_error'>" . $_SESSION['errorMailMessage'] . "</span>";

                // On supprime le message d'erreur de la variable session apres son affichage
                unset($_SESSION['errorMailMessage']);
            }
        ?>

        <input type="password" name="password" placeholder="Votre mot de passe ...">
        <input type="password" name="confirmPassword" placeholder="Confirmation du mot de passe ...">
        <?php
            // Si la variable session contient un message d'erreur par rapport au password
            if(isset($_SESSION['errorPasswordMessage'])) {
                
                // On affiche le message d'erreur
                echo "<span class='message_error'>" . $_SESSION['errorPasswordMessage'] . "</span>";

                // On supprime le message d'erreur de la variable session apres son affichage
                unset($_SESSION['errorPasswordMessage']);
            }
        ?>

        <input type="submit" value="S'inscrire">
    </form>

    <?php
        // Si la variable session contient un message d'erreur
        if(isset($_SESSION['errorMessage'])) {
            
            // On affiche le message d'erreur
            echo "<span class='message_error'>" . $_SESSION['errorMessage'] . "</span>";

            // On supprime le message d'erreur de la variable session apres son affichage
            unset($_SESSION['errorMessage']);
        
        // Sinon si la variable session contient un message de réussite
        } else if(isset($_SESSION['successMessage'])) {

            // On affiche le message de réussite
            echo "<span class='message_success'>" . $_SESSION['successMessage'] . "</span>";
            echo "<br>";
            echo "Vous pouvez maintenant vous <a href='login.php'>connecter</a>";

            // On supprime le message de réussite de la variable session apres son affichage
            unset($_SESSION['successMessage']);
        }
    ?>
</body>
</html>