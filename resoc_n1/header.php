<header>
    <img src="resoc.jpg" alt="Logo de notre réseau social"/>
    <nav id="menu">
        <a href="registration.php">Inscription</a>
        <a href="news.php">Actualités</a>
        <a href="wall.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mur</a>
        <a href="feed.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Flux</a>
        <a href="tags.php?tag_id=<?php echo $_SESSION['connected_id']; ?>">Mots-clés</a>
    </nav>
    <nav id="user">
        <a href="#">Profil</a>
        <ul>
            <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Paramètres</a></li>
            <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mes suiveurs</a></li>
            <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mes abonnements</a></li>
            <?php if( isset($_SESSION['connected_id']) && $_SESSION['connected_id'] !== null ) : ?>
                <li><a href="logout.php">Se déconnecter</a></li>
            <?php else : ?>
                <li><a href="login.php">Se connecter</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>



<!-- <header>
  <a href='admin.php'><img src="resoc.jpg" alt="Logo de notre réseau social"/></a>
  <nav id="menu">
      <a href="news.php">Actualités</a>
      <a href="wall.php?user_id=5">Mur</a>
      <a href="feed.php?user_id=5">Flux</a>
      <a href="tags.php?tag_id=1">Mots-clés</a>
  </nav>
  <nav id="user">
      <a href="#">Profil</a>
      <ul>
        <li><a href="login.php?user_id=5">Se connecter</a></li>
        <li><a href="settings.php?user_id=5">Paramètres</a></li>
        <li><a href="followers.php?user_id=5">Mes suiveurs</a></li>
        <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
      </ul>
  </nav>
</header> -->

