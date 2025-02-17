<?php

require_once("./database.php");

class QueryBuilder extends Database {
  public $query = "";
  private $table;

  public function __construct($table) {
    $this->connect("lead_gen_business");
    $this->table = $table;
  }

  public function select(array $fields) {
    $this->query = "SELECT " . implode(", ", $fields) . " FROM $this->table ";
    return $this;
  }

  public function where(array $object) {
    if (empty($this->query)) {
      $this->query = "SELECT * FROM $this->table";
    }

    $this->query .= " WHERE ";

    $counter = 0;
    $multipleConditions = false;

    if (count($object) > 0) {
      $multipleConditions = true;
    }

    foreach ($object as $key => $value) {
      if ($multipleConditions && $counter > 0) {
        $this->query .= " AND ";
      }

      $this->query .= " $key LIKE '%$value%'";

      $counter++;
    }

    return $this;
  }

  public function groupBy(array $fields) {
    $this->query .= "GROUP BY " . implode(", ", $fields) . " ";
    return $this;
  }

  public function having($left, $condition, $right) {
    $this->query .= "HAVING " . $left . " $condition" . " $right";
    return $this;
  }

  public function get() {
    $this->query .= ";";

    try {
      return $this->fetch_all($this->query);
    } catch (ErrorException $err) {
      throw new ErrorException("Something went wrong: " . $err);
    }
  }
}

?>