<?php
error_reporting(E_ALL);

//echo "<h2>TCP/IP Connection</h2>\n";

/* Obtener el puerto para el servicio WWW. */
$service_port = "8085";

/* Obtener la dirección IP para el host objetivo. */
$address = "127.0.0.1";

/* Crear un socket TCP/IP. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    //echo "socket_create() falló: razón: " . socket_strerror(socket_last_error()) . "\n";
} else {
//    echo "OK.\n";
}

//echo "Intentando conectar a '$address' en el puerto '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    //echo "socket_connect() falló.\nRazón: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    //echo "OK.\n";
}

$primero="";
$segundo="";

if(isset($_POST['primero'])==true){
    $primero=$_POST['primero'];
}
if(isset($_POST['segundo'])==true){
    $segundo=$_POST['segundo'];
}


$in = $primero."+".$segundo."\n";
$out = '';

//echo "Enviando petición HTTP HEAD ...";
socket_write($socket, $in, strlen($in));
//echo "OK.\n";

//echo "Leyendo respuesta:\n\n";
$answer="";
while ($out = socket_read($socket, 2048)) {
    $answer.=trim($out);
}

//echo "Cerrando socket...";
socket_close($socket);

$data=array("success"=>"false","suma"=>"0");

if(strlen(trim($answer))>0){
    $data=array("success"=>"true","suma"=>$answer);
}
//echo "OK.\n\n";

echo json_encode($data);
?>