<?php


class Database {
  private $connection;
  
  public function __construct() {
    try {  
      $env = parse_ini_file("./.env");
      DEFINE("DB_HOST", $env["DB_HOST"]);
      DEFINE("DB_USER", $env["DB_USER"]);
      DEFINE("DB_PASS", $env["DB_PASS"]);
      DEFINE("DB_NAME", $env["DB_NAME"]);
    } catch(ErrorException $err) {
      echo "Error parsing .env file: $err";
    }
  }

  public function connect($dbName) {
    $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, $dbName);

    if ($this->connection->errno) {
      throw new ErrorException("Failed to connect to database: {$this->connection->errno} $this->connection->error");
    }
  }

  public function fetch_all($query) {
    $data = array();
    $result = $this->connection->query($query);
  
    if ($result === false) {
      return $result;
    }
  
    while($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
    }
  
    $result->free();
  
    return $data;
  }

  public function fetch_record(string $query, string $types, array $values) {
    try {
      $statement = $this->connection->prepare($query);
      $statement->bind_param($types, ...$values);
  
      // $result = $connection->query($query);
  
      $statement->execute();
  
      $result = $statement->get_result();
  
      $data = $result->fetch_assoc();
  
      $statement->close();
  
      return $data;
    } catch (ErrorException $err) {
      throw new ErrorException("Something went wrong: " . $this->connection->error);
    }
  }

  public function run_mysql_query(string $query, string $types, array $values) {
    # 1. Prepare
    $statement = $this->connection->prepare($query);
  
    if (!$statement) {
      throw new Exception("Failed to prepare statement: " . $this->connection->error);
    }
  
    # 2. Bind and Execute
    $statement->bind_param($types, ...$values);
  
    # 3. Execute the Statement
    $result = $statement->execute();
    $statement->close();
    if ($result) {
      $insertId = $this->connection->insert_id;
      if ($insertId > 0) {
          return $insertId;
      }
    } else {
        throw new Exception("Query execution failed: " . $statement->error);
    }
  }

  public function escape_this_string($string) {
    return $this->connection->real_escape_string($string);
  }
}


$db = new Database();

?>