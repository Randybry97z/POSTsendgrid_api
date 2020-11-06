<?php
require('vendor/autoload.php');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  echo "HOLA";
  http_response_code(201);
  return 'hola';
}

/* $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri ); */

  $input = (array) json_decode(file_get_contents('php://input'), TRUE);
  $email = new \SendGrid\Mail\Mail();
  $email->setFrom($input['from']);
  $email->setSubject($input['subject']);
  if(isset($input['cc'])){
    $email->addCc($input['cc']);
  }
  $email->addTo($input['to']);
  $email->addContent("text/html", $input['html']);
  $sendgrid = new \SendGrid($input['api_key']);

  try {
    $response = $sendgrid->send($email);
    return $response->statusCode() . "\n";
    return($response->headers());
    return $response->body() . "\n";
  } catch (Exception $e) {
    return 'Caught exception: '. $e->getMessage() ."\n";
  }
