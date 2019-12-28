<?php

//session_start();
require 'dbh.inc.php';

$event_id = $_GET['eventid'];
if(isset($_GET['status'], $_GET['item'])){
  $status = $_GET['status'];
  $item = $_GET['item'];


  switch($status) {
      case 'delete':
        $doneQuery = $db->prepare("
          DELETE from expenses
          WHERE id= :item
          ");

        $doneQuery->execute([
          'item' => $item,
        ]);
        break;
  }

}

header('Location: ../events_detail.php?eventid='.$event_id.'&action=itemdeleted');

 ?>
