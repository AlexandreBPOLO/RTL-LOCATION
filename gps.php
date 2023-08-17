<?php

$nome = $_GET['nome'];

$data = $_GET['data'];

$long = $_GET['longitude'];

$lat = $_GET['latitude'];

$hour = $_GET['hour'];

$minute = $_GET['minute'];

$second = $_GET['second'];

 

if (isset($data)) {

    $liga = mysqli_connect('localhost', 'root', 'root', 'RTL');

 

    $data = mysqli_real_escape_string($liga, $data);

    $long = mysqli_real_escape_string($liga, $long);

    $lat = mysqli_real_escape_string($liga, $lat);

    $hour = mysqli_real_escape_string($liga, $hour);

    $minute = mysqli_real_escape_string($liga, $minute);

    $second = mysqli_real_escape_string($liga, $second);

 

    $sql = "INSERT INTO gps (Data, Longitude, Latitude, Hour, Minute, Second) VALUES ('$data', '$long', '$lat', '$hour', '$minute', '$second')";

 

    if ($liga->query($sql) === TRUE) {

        echo "Dados inseridos com sucesso";

    } else {

        echo "Erro ao inserir dados: " . $liga->error;

    }

} else {

    echo "Dados inválidos";

}

?>


