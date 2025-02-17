<?php

require_once("./query-builder.php");

class Sites extends QueryBuilder {
  public $count = "COUNT(client_id)";
  private $table = "sites";

  public function __construct() {
    parent::__construct($this->table);
  }
}

?>