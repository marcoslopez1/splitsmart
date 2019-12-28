<?php

//session_start();
require 'dbh.inc.php';

if (isset($_POST['name'])){

//  $userid = $_SESSION['userid'];
  //$userid = 1;
  $name = $_POST['name'];
  $description = $_POST['description'];
  $start_date = $_POST['start_date'];
  $finish_date = $_POST['finish_date'];
  $status = 1;

  if(!empty($name)){
    $addedQuery = $db->prepare("
      INSERT INTO events (name, description, start_date, finish_date, status)
      VALUES (:name, :description, :start_date, :finish_date, :status)
    ");

    $addedQuery->execute([
      'name' => $name,
      'description' => $description,
      'start_date' => $start_date,
      'finish_date' => $finish_date,
      'status' => $status,
    ]);
  }
}

header('Location: ../events_new.php?action=entrycreated');

?>
