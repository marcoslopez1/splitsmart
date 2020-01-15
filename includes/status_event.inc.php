<?php

//session_start();
require 'dbh.inc.php';

$event_id = $_GET['event_id'];
if(isset($_GET['status'])){
  $status = $_GET['status'];


  switch($status) {
      case 'close':
        $closeQuery = $db->prepare("
          UPDATE events SET status = '0'
          WHERE id = :event_id AND status = '1'
          ");

        $closeQuery->execute([
          'event_id' => $event_id,
        ]);

        break;

  }



  switch($status) {

    case 'open':
      $openQuery = $db->prepare("
        UPDATE events SET status = '1'
        WHERE id = :event_id AND status = '0'
        ");

      $openQuery->execute([
        'event_id' => $event_id,
      ]);

      break;

  }

}

if ($status == 'close'){
  header('Location: ../events_detail.php?eventid='.$event_id.'&action=statusclosed');
} elseif ($status == 'open') {
  header('Location: ../events_detail.php?eventid='.$event_id.'&action=statusopen');
} else {
  header('Location: ../events_detail.php?eventid='.$event_id);
}


 ?>
