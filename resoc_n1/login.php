<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
    // si on recoit un champ email rempli il y a une chance que ce soit un traitement
    $enCoursDeTraitement = isset($_POST['email']);
    
    if ($enCoursDeTraitement) {
        // Etape 2: récupérer ce qu'il y a dans le formulaire
        $emailAVerifier = $_POST['email'];
        $passwdAVerifier = $_POST['motpasse'];

        //Etape 3 : Ouvrir une connexion avec la base de donnée.
        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
        //Etape 4 : Petite sécurité
        // pour éviter les injections SQL : https://www.w3schools.com/sql/sql_injection.asp
        $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
        $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
        // on crypte le mot de passe pour éviter d'exposer notre utilisatrice en cas d'intrusion dans nos systèmes
        $passwdAVerifier = md5($passwdAVerifier);
        // NB: md5 est pédagogique mais n'est pas recommandé pour une vraie sécurité
        //Etape 5 : construction de la requete
        $lInstructionSql = "SELECT * "
            . "FROM users "
            . "WHERE "
            . "email LIKE '" . $emailAVerifier . "'";

        // Etape 6: Vérification de l'utilisateur
        $res = $mysqli->query($lInstructionSql);
        $user = $res->fetch_assoc();

        if (!$user || $user["password"] != $passwdAVerifier) {
            echo "La connexion a échoué.";
        } else {
            // Etape 7 : Se souvenir que l'utilisateur s'est connecté pour la suite
            // documentation: https://www.php.net/manual/fr/session.examples.basic.php
            $_SESSION['connected_id'] = $user['id'];
            
            // Redirection vers le mur de l'utilisateur
            $userId = $user['id'];
            header("Location: wall.php");
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php include("header.php");?>

        <div id="wrapper" >
            <aside>
                <h2>Présentation</h2>
                <p>Bienvenue sur notre réseau social.</p>
            </aside>
            <main>
                <article>
                    <h2>Connexion</h2>
                    <?php
                    if (isset($_SESSION['connected_id'])) {
                        echo "Vous êtes déjà connecté.";
                    } else {
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type='hidden' name='email' value=''>
                            <dl>
                                <dt><label for='email'>E-Mail</label></dt>
                                <dd><input type='email' name='email'></dd>
                                <dt><label for='motpasse'>Mot de passe</label></dt>
                                <dd><input type='password' name='motpasse'></dd>
                            </dl>
                            <input type='submit'>
                        </form>
                        <p>
                            Pas de compte?
                            <a href='registration.php'>Inscrivez-vous.</a>
                        </p>
                        <?php
                    }
                    ?>
                </article>
            </main>
        </div>
    </body>
</html>
