<?php

//session_start();
require 'dbh.inc.php';

if (isset($_POST['name'])){

//  $userid = $_SESSION['userid'];
  //$userid = 1;
  $name = $_POST['name'];
  $description = $_POST['description'];

  if(!empty($name)){
    $addedQuery = $db->prepare("
      INSERT INTO categories (name, description)
      VALUES (:name, :description)
    ");

    $addedQuery->execute([
      'name' => $name,
      'description' => $description,
    ]);
  }
}

header('Location: ../expenses_categories_new.php?action=entrycreated');

?>
