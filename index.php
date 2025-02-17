<?php

require_once("./sites.php");
require_once("./clients.php");

$sites = new Sites();

$sites->select(["client_id", $sites->count]);
$sites->groupBy(["client_id"]);
$sites->having($sites->count, ">", 5);

var_dump($sites->get());

$clients = new Clients();

var_dump($clients->where(
  array(
    "last_name" => "Owen",
    "first_name" => "Ryan"
  )
)->get());

?>