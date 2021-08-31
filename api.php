<?php

include "functions.php";
include_once "DBManager.php";

header('Content-Type: application/json');

$output = [
  'status' => false
];

if (
  array_key_exists('page', $_GET) && 
  is_string($_GET['page'])
) {
  $db_manager = new DBManager(getPageName());
  // Add funkcionalitāte
  if (
    isset($_POST['task']) &&
    is_string($_POST['task']) &&
    !empty($_POST['task'])
  ) { 
    $output = $db_manager->addTask($_POST['task']);
  }
    // Delete funkcionalitāte
  elseif (
    array_key_exists('delete', $_GET) &&
    is_numeric($_GET['delete'])
  ) {
    $output['status'] = $db_manager->deleteTask($_GET['delete']);
  }
  elseif (
    array_key_exists('update', $_GET) &&
    is_numeric($_GET['update'])
  ) {
    if (
      array_key_exists('new_text', $_GET) && 
      is_string($_GET['new_text'])
    ) {
      $new_text = $db_manager->updateTask($_GET['update'], $_GET['new_text']);
      if (is_string($new_text)) {
        $output['status'] = true;
        $output['message'] = "Updated successfully";
        $output['task'] = $new_text;
      }
      else {
        $output['message'] = "Update failed, no such ID";
      }
    }
    elseif (
      array_key_exists('done', $_GET) && 
      is_string($_GET['done'])
    ) {
      $complition_status = $db_manager->updateTaskStatus($_GET['update'], $_GET['done']);
      if ($complition_status) {
        $output['status'] = true; 
        $output['message'] = "Task completion status changed";
      }
    }
  }
}


echo json_encode($output, JSON_PRETTY_PRINT);