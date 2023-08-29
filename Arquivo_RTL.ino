#include <SoftwareSerial.h>  // Biblioteca para comunicação serial
#include <TinyGPS++.h>  // Biblioteca para interpretar dados do GPS

// Configuração do módulo GPS
#define GPS_SERIAL_RX_PIN 2  // Pino RX do módulo GPS conectado ao pino 2 do Arduino
#define GPS_SERIAL_TX_PIN 3  // Pino TX do módulo GPS conectado ao pino 3 do Arduino

// Configuração do módulo Wi-Fi
#define RX 9  // Pino RX do módulo Wi-Fi conectado ao pino 9 do Arduino
#define TX 10  // Pino TX do módulo Wi-Fi conectado ao pino 10 do Arduino

SoftwareSerial gpsSerial(GPS_SERIAL_RX_PIN, GPS_SERIAL_TX_PIN);  // Objeto da comunicação serial para o módulo GPS
TinyGPSPlus gps;  // Objeto para interpretar os dados do GPS
SoftwareSerial esp8266(RX, TX);  // Objeto da comunicação serial para o módulo Wi-Fi

String HOST = "20.71.94.247";  // Endereço IP do servidor
String PATH = "/gps.php?";  // Caminho inicial para a requisição
String PORT = "80";  // Porta utilizada para a comunicação

int countTrueCommand;  // Contador de comandos bem-sucedidos
int countTimeCommand;  // Contador de tempo para comandos
boolean found = false;  // Indicador de sucesso na execução do comando 

void setup() {
  Serial.begin(9600);  // Inicializa a comunicação serial com a taxa de 9600 bps
  gpsSerial.begin(9600);  // Inicializa a comunicação serial com o módulo GPS na taxa de 9600 bps
}

void loop() { 
  gpsSerial.listen();  // Passa o controle para o módulo GPS

  while (gpsSerial.available() > 0) {  // Verifica se há dados disponíveis para leitura do módulo GPS
    if (gps.encode(gpsSerial.read())) {  // Lê os dados do módulo GPS e os interpreta usando a biblioteca TinyGPS++
      if (gps.location.isValid()) {  // Verifica se os dados de localização são válidos
        float latitude = gps.location.lat();  // Obtém a latitude
        float longitude = gps.location.lng();  // Obtém a longitude
        int year = gps.date.year();  // Obtém o ano
        byte month = gps.date.month();  // Obtém o mês
        byte day = gps.date.day();  // Obtém o dia
        byte hour = gps.time.hour();  // Obtém a hora
        byte minute = gps.time.minute();  // Obtém o minuto
        byte second = gps.time.second();  // Obtém o segundo


        // Exibir os dados de localização no Monitor Serial

        Serial.print("Latitude: "); //IMPRIME O VALOR LATITUDE
        Serial.println(latitude, 6);  // Imprime o valor da latitude com 6 casas decimais
        Serial.print("Longitude: ");  // Imprime a string "Longitude: "
        Serial.println(longitude, 6);  // Imprime o valor da longitude com 6 casas decimais
        Serial.print("Data: ");  // Imprime a string "Data: "
        Serial.print(day);  // Imprime o valor do dia
        Serial.print("/");  // Imprime o caractere '/'
        Serial.print(month);  // Imprime o valor do mês
        Serial.print("/");  // Imprime o caractere '/'
        Serial.println(year);  // Imprime o valor do ano


       
  String postData = "latitude=" + String(latitude, 6) + "&longitude=" + String(longitude, 6) +
        "&data=" + String(year) + "/" + String(month) + "/" + String(day) ; // Uma string que contém os dados formatados para serem enviados ao servidor.
        // " " + String(hour) + ":" + String(minute) + ":" + String(second);


  String Caminho= PATH + postData; //Uma string que contém o caminho completo para a requisição HTTP
  Serial.println(Caminho); //mprime no Monitor Serial o valor da string Caminho
  Serial.println(Caminho.length()); //: Imprime no Monitor Serial o tamanho da string Caminho.

// PostRequest Uma string que contém o texto completo da requisição HTTP a ser enviada ao servidor.
  String postRequest = "GET " + Caminho  + " HTTP/1.1\r\n" + //A linha inicial da requisição, indicando o método GET e o caminho completo.
                       "Host: " + HOST + "\r\n" + //O cabeçalho Host que especifica o endereço do servidor.
                       "Accept: *" + "/" + "*\r\n" + //O cabeçalho Accept, indicando que todos os tipos de conteúdo são aceitos.
                       "Content-Length: 8\r\n" + //O cabeçalho Content-Length, indicando o comprimento do conteúdo da requisição.
                       "Content-Type: application/json\r\n" + //O cabeçalho Content-Type, especificando o tipo de conteúdo como JSON.
                       "\r\n" ; //Uma linha vazia que indica o fim dos cabeçalhos da requisição HTTP.
                 
  Serial.println(postRequest); // Imprime no Monitor Serial o valor da string 
  Serial.println(postRequest.length()); // Imprime no Monitor Serial o comprimento da string postRequest.

  esp8266.begin(115200); //Inicializa a comunicação com o módulo ESP8266 na velocidade de 115200 bps.
  esp8266.println("AT+RST"); //Envia o comando "AT+RST" para o módulo ESP8266.
  esp8266.println("AT"); //: Envia o comando "AT" para o módulo ESP8266.
  Serial.println(esp8266.read()); //  Lê e imprime no Monitor Serial a resposta do módulo ESP8266.
  sendCommandToESP8266("AT", 5, "OK");//Chama a função sendCommandToESP8266 para enviar o comando "AT" para o módulo ESP8266, esperando a resposta "OK" dentro de 5 segundos.
  sendCommandToESP8266("AT+CWMODE=1", 5, "OK");  // Chama a função sendCommandToESP8266 para enviar o comando "AT+CWMODE=1" esperando a resposta "OK" dentro de 5 segundos
  //sendCommandToESP8266("AT+CWJAP=\"HUAWEI\",\"teste123\"", 30, "OK");  
  sendCommandToESP8266("AT+CWJAP=\"Vodafone-4D336C\",\"ZM9Kx7pdhB\"", 30, "OK");
  //
  esp8266.listen();    //  Passa  para a comunicação com o módulo ESP826
  while (esp8266.available() > 0) //Enquanto houver dados disponíveis do módulo ESP8266 para leitura:
  {
    sendCommandToESP8266("AT+CIPMUX=1", 5, "OK"); // Chama a função sendCommandToESP8266 para enviar o comando "AT+CIPMUX=1" esperando a resposta "OK" dentro de 5 segundos.
    sendCommandToESP8266("AT+CIPSTART=0,\"TCP\",\"" + HOST + "\"," + PORT, 15, "OK"); //para enviar o comando "AT+CIPSTART=0,"TCP","" + HOST + ""," + PORT" para estabelecer uma conexão TCP com o servidor 
    String cipSend = "AT+CIPSEND=0," + String(postRequest.length() + Caminho.length());   //Cria uma string cipSend contendo o comando "AT+CIPSEND=0," seguido pelo tamanho total dos dados a serem enviados em bytes 
    sendCommandToESP8266(cipSend, 1, ">"); //Chama a função sendCommandToESP8266 para enviar o comando cipSend para o módulo ESP8266
    esp8266.print(postRequest);//Envia os dados contidos na string postRequest para o módulo ESP8266.
    sendData(Caminho); //Chama a função sendData para enviar os dados contidos na string Caminho.
    sendCommandToESP8266("AT+CIPCLOSE=0", 5, "OK"); // Chama a função sendCommandToESP8266 para enviar o comando "AT+CIPCLOSE=0" (encerrar a conexão) 
  }
  delay(15000);    //Aguarda um intervalo de 15 segundos.  
     }
    }
  }
}
void sendCommandToESP8266(String command, int maxTime, char readReplay[]) { //Definição da função sendCommandToESP8266 que envia um comando para o módulo ESP8266 e espera por uma determinada resposta
  Serial.print(countTrueCommand);//: Imprime o número de comandos executados com sucesso no Monitor Serial.
  Serial.print(". at command => ");//  Imprime a sequência ". at command => " no Monitor Serial.
  Serial.print(command);// Imprime o comando atual no Monitor Serial.
  Serial.print(" ");// Imprime um espaço no Monitor Serial.
  while (countTimeCommand < (maxTime * 1))// Enquanto o tempo do comando atual for menor que o tempo máximo permitido:
  {
    esp8266.println(command); //Envia o comando para o módulo ESP8266.
    if (esp8266.find(readReplay)) //Envia o comando para o módulo ESP8266.
    {
      found = true; // Define found como verdadeiro se a resposta foi encontrada.
      break; // Sai do loop while
    }
    countTimeCommand++; // Incrementa o tempo do comando atual.
  }
  if (found == true) //Se a resposta foi encontrada
  {
    Serial.println("Success"); // Imprime "Success" no Monitor Serial.
    countTrueCommand++; //Incrementa o número de comandos executados com sucesso.
    countTimeCommand = 0; // Reinicia o tempo do comando atual.
  }
  if (found == false) //  Se a resposta não foi encontrada:
  {
    Serial.println("Fail"); //  Imprime "Fail" no Monitor Serial.
    countTrueCommand = 0; // Zera o número de comandos executados com sucesso.
    countTimeCommand = 0; //  Reinicia o tempo do comando atual.
  }
  found = false; //Define found como falso
}
void sendData(String postRequest) { //  Definição da função sendData que envia os dados contidos na string postRequest.
  Serial.println(postRequest); // Imprime a string postRequest no Monitor Serial.
  esp8266.println(postRequest); //  Envia a string postRequest para o módulo ESP8266.
  delay(1500); //  Aguarda 1500 milissegundos (1,5 segundos).
  countTrueCommand++; //Incrementa o número de comandos executados com sucesso.
  delay(20000); // Atraso de 20 segundos antes do próximo envio
}