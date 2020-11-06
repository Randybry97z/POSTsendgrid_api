<?php
require('vendor/autoload.php');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Origin, Content-Type");

/* $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri ); */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = (array) json_decode(file_get_contents('php://input'), TRUE);
  $email = new \SendGrid\Mail\Mail();
  $email->setFrom($input['from']);
  $email->setSubject($input['subject']);
  if(isset($input['cc'])){
    $email->addCc($input['cc']);
  }
  $email->addTo($input['to']);
  $email->addContent("text/html", $input['html']);
  if(!isset($input['api_key'])){
    echo "API KEY REQUIRED";
    return true;
  }
  $sendgrid = new \SendGrid($input['api_key']);

  try {
    $response = $sendgrid->send($email);
    print_r ($response->statusCode() . "\n");
    print_r ($response->headers());
    print_r ($response->body() . "\n");
  } catch (Exception $e) {
    print_r ('Caught exception: '. $e->getMessage() ."\n");
  }
}