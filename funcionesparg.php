<?php

//echo "soy funciones.php";
session_start();

function validarRegistro($datos){
  $errores =[];
  $datosFinales = [];

  foreach ($datos as $posicion => $valor) {
    if($posicion !== "hobbies"){
    $datosFinales[$posicion] = trim($valor);
    }
  }
  //validar Imagen
  if ($_FILES){
    //var_dump($_FILES);
    if($_FILES["avatar"]["name"] == ""){
      $errores["avatar"] = "No se seleccionó archivo.";
    } elseif($_FILES["avatar"]["error"]!==0){
      $errores["avatar"] = "Hubo un error en la subida del archivo";
    } else{
      //chequear que sea un archivo con la extensión deseada;
      $ext = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
      if($ext!== "jpg"){
        $errores["avatar"]= "El archivo debe ser de tipo jpg, jpeg o png";
      }
    }
  }

  //name
  if(strlen($datosFinales["user"]) == 0){
    $errores["user"] = "Campo obligatorio";
  }


  //email
  if(strlen($datosFinales["email"]) == 0){
    $errores["email"] = "Campo obligatorio";
  } else if (filter_var($datosFinales["email"], FILTER_VALIDATE_EMAIL) == false){
      $errores["email"] = "Ingrese un email válido.";
  } else if(existeElUsuario($datosFinales["email"])){
      $errores["email"] = "El email ya se encuentra registrado";
  }
  //password
  if(strlen($datosFinales["pass"]) == 0){
    $errores["pass"] = "Campo obligatorio";
  } else if(strlen($datosFinales["pass2"]) == 0){
    $errores["pass"] = "Por favor verifique la contraseña";
  } else if ($datosFinales["pass"] !== $datosFinales["pass2"]){
    $errores["pass"] = "Las contraseñas no coinciden";
  }

  return $errores;
}

function nextId(){
  //Que pasa si no existe db.json
  if(!file_exists("db.json")){
    $json = "";
  } else {
    $json= file_get_contents("db.json");
  }

  //Que pasa si devuelve una cadena vacía
  if($json === ""){
    return $lastID = 1;
  }
  $usuariosArray = json_decode($json, true);

  $lastUser = array_pop($usuariosArray["usuarios"]);
  $lastId = $lastUser["id"];
  return $lastId + 1;
}

function armarUsuario(){
  return [
    "id"=>nextId(), //El resultado de la función nextId
    "user"=>trim($_POST["user"]),
    "email"=>trim($_POST["email"]),
    "password"=>password_hash($_POST["pass"], PASSWORD_DEFAULT),
  ];
}

function guardarUsuario($user){
  $json = file_get_contents("db.json");
  $array = json_decode($json, true);

  $array["usuarios"][] = $user;
  $array = json_encode($array, JSON_PRETTY_PRINT);

  file_put_contents("db.json", $array);

}

function buscarPorEmail($email) {
 //Si pasamos esta validación al array de errores hay que chequear primero que exista el archivo .json a donde queremos guardar los datos de lo contrario la llamada romperá por intentar buscar un archivo que no existe.
 if(!file_exists("db.json")){
   $usuarios = "";
 } else {
   $usuarios= file_get_contents("db.json");
 }

 if ($usuarios == "") {
   return null;
 }

 $array= json_decode($usuarios, true);

 foreach ($array["usuarios"] as $usuario){
   if($email==$usuario["email"]){
     return $usuario;
    }
  }
  return null; //En caso de que no encuentre un usuario, la función devolverá NULL.
}

function buscarPorID($id){
  $json = file_get_contents("db.json");
  $array = json_decode($json, true);

  foreach ($array["usuarios"] as $usuario => $datos) {
    if($id === $datos["id"]){
      return $usuario;
    }
    return null;
  }
  }


function existeElUsuario($email){
  return buscarPorEmail($email) !== null;
}

function listaDeUsuarios(){
  $json = file_get_contents("db.json");
  $array = json_decode($json, true);

  return $array;
}

// agregar funciones para LOGIN//validar LOGIN

function validarLogin($datos){

  $errores=[];
  $datosFinales=[];

  foreach ($datos as $posicion =>$valor){
$datosFinales[$posicion] =trim($valor);

  }
  //email para login
  if(strlen($datosFinales["email"]) == 0){
    $errores["email"] = "Campo obligatorio";
  } else if (filter_var($datosFinales["email"], FILTER_VALIDATE_EMAIL) == false){
      $errores["email"] = "Ingrese un email válido.";
  } else if(!existeElUsuario($datosFinales["email"])){
      $errores["email"] = "El email no se encuentra registrado";
  }
  //password para Login
  $usuario = buscarPorEmail($datosFinales["email"]);
  if(!password_verify($datosFinales["pass"], $usuario["password"])){
    $errores["pass"] = "La contraseña es incorrecta.";
  }

  return $errores;

}
  function loguearUsuario($email){
    $_SESSION["email"]= $email;
  }

  function traerUsuarioLogueado(){
    if(isset($_SESSION["email"])){
    return buscarPorEmail($_SESSION["email"]);
    }
    return false;
  }
  //LOGOUT
  function usuarioLogueado(){
    return isset($_SESSION["email"]);
  }
?>
