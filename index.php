<?php
require('vendor/autoload.php');

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

  $input = (array) json_decode(file_get_contents('php://input'), TRUE);
  $email = new \SendGrid\Mail\Mail();
  $email->setFrom($input['from']);
  $email->setSubject($input['subject']);
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
