<?php
require_once 'funcionesparg.php';

$usuario = traerUsuarioLogueado();
//var_dump($_SESSION, $usuario);

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<!--HEAD-->
<?php include("head.php"); ?>

<header class="nav-bar">
  <div class="perfil">
    <?php if (usuarioLogueado()): ?>
        <a class="user"href="listado.php">
      <img class="avatar" src="img/<?= $usuario["id"] . ".jpg"?>" alt="">
      <br>
      <span class="user"><?= $usuario["user"] ?></span>
     </a>
    <?php else: ?>
      <div class="ingreso">
      <a class="menu-small" href="login.php"><i class="fas fa-user"></i></a>
      <br>
      <a class="user"href="login.php">Login</a>
      </div>
    <?php endif; ?>
  </div>
  <div class="puntoarg">
  <a href="index.php"><h1 class="big-title">puntoARG</h1></a>
  </div>


  <div class="menu-small">
    <ul class="menu1">
      <li> <a href="logout.php"><i class="fas fa-bars"></a></i> </li>
    </ul>
  </div>

  <div class="menu-big">
    <ul class="menu2">
      <li><a href="#"><i class="fas fa-search"></i></a></li>


      <li> <a href="faq.php"><i id="ayuda"class="far fa-question-circle"></i></a> </li>
        <li><a href="#"><i class="fas fa-shopping-cart"></i></a></li>
        <?php if (usuarioLogueado()): ?>
      <li>
          <a class="btn btn-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
      </li>
          <?php else: ?>
      <li> <a href="registro.php"><i id="user"class="fas fa-user-plus"></i></a> </li>
          <?php endif; ?>

      <!--<li><a href="#">Promociones</a></li>
      <li><a href="#">Destacados</a></li>
      <li><a href="#">Paquetes</a></li>-->
    </ul>
  </div>

</header>
