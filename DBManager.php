<?php
class DBManager
{
  private $conn;
  private $task_list = [];
  private $table_name = "todo-list";

  public function __construct($page) {
    $this->table_name .= "-" . $page;
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "bootcamp_todo_list";

    $this->conn = new mysqli($servername, $username, $password, $dbname);
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
  }

  public function __destruct() {
    $this->conn->close();
  }

  public function getTasksInverseJson() {
    $task_list = $this->task_list;
    krsort($task_list);
    return json_encode($task_list);
  }

  public function getData() {
    $sql = "SELECT id, text, status FROM `$this->table_name`";
    $result = $this->conn->query($sql);

    if ($result->num_rows > 0) {
      $data = $result->fetch_all(MYSQLI_ASSOC);
      $this->task_list = [];
      foreach ($data as $row) {
        $id = $row['id'];
        unset($row['id']);
        $row['status'] = ($row['status'] == 1);
        $this->task_list[$id] = $row;
      }

      return $this->task_list;
    } else {
      return [];
    }
  }

  public function addTask($task_message) {
    $task_message = htmlentities($task_message, ENT_QUOTES);
    $task_message = $this->conn->real_escape_string($task_message);
    $sql = "INSERT INTO `$this->table_name` (text)
            VALUES ('$task_message')";

    if ($this->conn->query($sql) === true) {
      $id = $this->conn->insert_id;
      $this->task_list[$id] = [
        "text" => $task_message,
        "status" => false
      ];
      return [
        'id' => $id,
        'task' => $this->task_list[$id],
        'status' => true
      ];
    } else {
      return [
        'status' => false,
        'message' => "Error: " . $sql . "<br>" . $this->conn->error
      ];
    }
  }

  public function deleteTask(int $id) {
    $sql = "DELETE FROM `$this->table_name` WHERE id=$id";

    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * @return false || $text - task description
   */
  public function updateTask(int $id, $new_text) {
    $new_text = htmlentities($new_text, ENT_QUOTES);
    $new_text = $this->conn->real_escape_string($new_text);
    $sql = "UPDATE `$this->table_name` SET text='$new_text' WHERE id=$id";



    if ($this->conn->query($sql) === TRUE) {
      $this->task_list[$id]['text'] = $new_text;
      return $new_text;
    } else {
      return false;
    }
  }

  public function updateTaskStatus(int $id, string $done) {
    $status = ($done === 'true');
    $this->task_list[$id]['status'] = $status;

    $status_for_db = (int) $status; 

    $sql = "UPDATE `$this->table_name` SET status=$status_for_db WHERE id=$id";

    if ($this->conn->query($sql) === true) {
      return $status;
    } 
  }

}