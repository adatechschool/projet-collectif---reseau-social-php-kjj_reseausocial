<?php
session_start();
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
<?php include("header.php"); ?>

<div id="wrapper">    <?php
        if (isset($_SESSION['connected_id'])) {
            $userId = intval($_GET['user_id']);
                } else {
                    echo 'Vous devez être connecté pour accéder à cette page.';
                        exit;
                        }
    ?>

    <?php
    /**
     * Etape 1: Le mur concerne un utilisateur en particulier
     * La première étape est donc de trouver quel est l'id de l'utilisateur
     * Celui-ci est indiqué en paramètre GET de la page sous la forme user_id=...
     * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
     * ... mais en résumé, c'est une manière de passer des informations à la page en ajoutant des choses dans l'URL
     */
    $userId = intval($_GET['user_id']);
    ?>
    <?php
    /**
     * Etape 2: se connecter à la base de donnée
     */
    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");

    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_post = $_POST['message'];

        $new_post = $mysqli->real_escape_string($new_post);
        $newPostSql = "INSERT INTO posts (id, user_id, content, created) 
                        VALUES (NULL, 
                                $userId, 
                                '$new_post', 
                                NOW())";
        $ok = $mysqli->query($newPostSql);
        if (!$ok) {
            echo "Erreur lors de l'ajout du message : " . $mysqli->error;
        }
    }
    ?>

    <aside>
        <?php
        /**
         * Etape 3: récupérer le nom de l'utilisateur
         */
        $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$userId'
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);

        $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
        $lesInformations = $mysqli->query($laQuestionEnSql);
        $user = $lesInformations->fetch_assoc();
        //@todo: afficher le résultat de la ligne ci-dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
        ?>
        <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
        <section>
            <h3>Présentation</h3>
            <p>Sur cette page, vous trouverez tous les messages de l'utilisatrice : <?php echo $user['alias'] ?>
                (n° <?php echo $userId ?>)
                Coucou
            </p>
        </section>

        <form method="POST" action="followers.php">
        <label for="user_id" hidden >ID de l'utilisateur : <?php echo $followers['user_id'] ?></label>
        <input type="text" hidden name="user_id" id="user_id" required>

        <button type="submit">S'abonner</button>
    </form>

    </aside>
    <main>
        <form class="form-wall" action="<?php echo $_SERVER['PHP_SELF'] . '?user_id=' . $userId; ?>" method="post">
            <input type='hidden' name='id' value=''>
            <dl>
                <dt><label for="<?php echo $newPostSql; ?>"></label></dt>
                <dd><textarea name='message'  placeholder="Votre message ..."></textarea></dd>
            </dl>
            <input type='submit'>
        </form>
        <?php
        /**
         * Etape 3: récupérer tous les messages de l'utilisatrice
         */
        $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name, 
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC
                    ";
        $lesInformations = $mysqli->query($laQuestionEnSql);
        if (!$lesInformations) {
            echo("Échec de la requête : " . $mysqli->error);
        }

        /**
         * Etape
         *  4: @todo Parcourir les messages et remplir correctement le HTML avec les bonnes valeurs php
         */

        while ($post = $lesInformations->fetch_assoc()) {
            ?>
            <article>
                <h3>
                    <time datetime='2020-02-01 11:12:13'><?php echo $post['created'] ?></time>
                </h3>
                <address>par <?php echo $post['author_name'] ?></address>
                <div>
                    <p><?php echo $post['content'] ?></p>
                </div>
                <footer>
                    <small>♥ <?php echo $post['like_number'] ?></small>
                    <a href=“”><?php
                        $tags = explode(',', $post['taglist']);
                        foreach ($tags as $tag) {
                            echo '#' . trim($tag) . ' ';
                        }
                        ?>
                    </a>
                </footer>
            </article>
        <?php } ?>
    </main>
</div>
</body>
</html>
