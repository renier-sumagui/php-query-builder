<?php

require_once("./query-builder.php");

class Clients extends QueryBuilder {
  private $table = "clients";

  public function __construct() {
    parent::__construct($this->table);
  }
}

?>