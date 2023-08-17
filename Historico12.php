<?php
$liga = mysqli_connect('localhost', 'root', 'root', 'RTL');

if ($liga === false) {
  die("Erro na conexão: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Se o método da requisição for POST, significa que estamos adicionando uma nova localização

  // Obter a data e hora atual
  $data = date('Y-m-d H:i:s');

  // Fazer a inserção no banco de dados
  $sql = "INSERT INTO gps (data) VALUES ('$data')";
  $result = $liga->query($sql);

  if ($result === false) {
    die("Erro ao inserir a nova localização: " . $liga->error);
  }

  // Obter os dados da nova localização para retornar ao JavaScript
  $novaLocalizacao = array(
    'data' => $data,
    'localizacao' => '',
    'latitude' => '',
    'longitude' => '',
  );

  echo json_encode([$novaLocalizacao]);
} else {
  // Se o método da requisição for GET, significa que estamos obtendo o histórico de localizações

  $sql = "SELECT * FROM gps";
  $result = $liga->query($sql);

  if ($result === false) {
    die("Erro na consulta: " . $liga->error);
  }

  $dadosLocalizacao = array();

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $latitude = $row['latitude'];
      $longitude = $row['longitude'];
      $data = $row['dt'];

      // Fazer a solicitação à API de geocodificação para obter o nome do local
      // Substitua {API_KEY} pelo seu valor de chave de API válida
      $geocodingAPIURL = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&key=AIzaSyBf2a8jJFDPp9gPOzdi9tirTZKomFjTmZc";

      // Fazer a solicitação à API de geocodificação
      $geocodingResponse = file_get_contents($geocodingAPIURL);
      if ($geocodingResponse === false) {
        die("Erro ao obter a resposta da API de geocodificação");
      }

      $geocodingData = json_decode($geocodingResponse);

      // Extrair o nome do local da resposta
      $locationName = '';
      if ($geocodingData && isset($geocodingData->results) && count($geocodingData->results) > 0) {
        $locationName = $geocodingData->results[0]->formatted_address;
      }

      // Adicionar os dados ao array de localizações
      $dadosLocalizacao[] = array(
        'data' => $data,
        'localizacao' => $locationName,
        'latitude' => $latitude,
        'longitude' => $longitude,
      );
    }
  }

  echo json_encode($dadosLocalizacao);
}

$liga->close();
?>
