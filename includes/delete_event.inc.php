<?php

//session_start();
require 'dbh.inc.php';

$event_id = $_GET['eventid'];
if(isset($_GET['status'])){
  $status = $_GET['status'];


  switch($status) {
      case 'delete':
        $deleteQuery = $db->prepare("
          DELETE from events
          WHERE id= :event_id
          ");

        $deleteQuery->execute([
          'event_id' => $event_id,
        ]);
        break;
  }


  switch($status) {
      case 'delete':
        $deleteQuery = $db->prepare("
          DELETE from expenses
          WHERE event_id= :event_id
          ");

        $deleteQuery->execute([
          'event_id' => $event_id,
        ]);
        break;
  }

}

header('Location: ../events_list.php?action=itemdeleted');

 ?>
