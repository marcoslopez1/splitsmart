<?php
//session_start();
require 'dbh.inc.php';

$event_name = $_POST['event'];
$person_name = $_POST['made_by'];
if (!empty($_POST['category'])):
  $category_name = $_POST['category'];
endif;

//We select the event id:
$itemsQuery1 = $db->prepare("
  SELECT id, name, description, start_date, finish_date, creation_date_stamp
  FROM events
  WHERE name = :name
  ORDER BY creation_date_stamp ASC
");
$itemsQuery1->execute([
  'name' => $event_name
]);
$items1 = $itemsQuery1->rowCount() ? $itemsQuery1 : [];
if(!empty($items1)):
  foreach ($items1 as $item):
    $event_id = $item['id'];
  endforeach;
endif;

//We select the person id:
$itemsQuery2 = $db->prepare("
  SELECT id, name, description, creation_date_stamp
  FROM people
  WHERE name = :name
  ORDER BY creation_date_stamp ASC
");
$itemsQuery2->execute([
  'name' => $person_name
]);
$items2 = $itemsQuery2->rowCount() ? $itemsQuery2 : [];
if(!empty($items2)):
  foreach ($items2 as $item):
    $person_id = $item['id'];
  endforeach;
endif;

//We select the category id:
if (!empty($_POST['category'])):
  $itemsQuery3 = $db->prepare("
    SELECT id, name, description, creation_date_stamp
    FROM categories
    WHERE name = :name
    ORDER BY creation_date_stamp ASC
  ");
  $itemsQuery3->execute([
    'name' => $category_name

  ]);
  $items3 = $itemsQuery3->rowCount() ? $itemsQuery3 : [];
  if(!empty($items3)):
    foreach ($items3 as $item):
      $category_id = $item['id'];
    endforeach;
  endif;
endif;



if (isset($_POST['amount'])){

//  $userid = $_SESSION['userid'];
  //$userid = 1;
  $amount = $_POST['amount'];
  $name = $_POST['name'];
  $description = $_POST['description'];
  if (!empty($_POST['category'])):
    $category = $_POST['category'];
  else:
    $category = "";
    $category_id = "";
  endif;
  $date = $_POST['date'];
  $made_by = $_POST['made_by'];
  $event = $_POST['event'];

  if(!empty($name)){
    $addedQuery = $db->prepare("
      INSERT INTO expenses (amount, name, description, category, category_id, date, made_by, made_by_id, event, event_id)
      VALUES (:amount, :name, :description, :category, :category_id, :date, :made_by, :made_by_id, :event, :event_id)
    ");

    $addedQuery->execute([
      'amount' => $amount,
      'name' => $name,
      'description' => $description,
      'category' => $category,
      'category_id' => $category_id,
      'date' => $date,
      'made_by' => $made_by,
      'made_by_id' => $person_id,
      'event' => $event,
      'event_id' => $event_id,
    ]);
  }
}



header('Location: ../expenses_new.php?action=entrycreated');

?>
