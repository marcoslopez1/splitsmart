<?php

//require 'config.php';
//session_start();
require 'includes/dbh.inc.php';

$itemsQuery1 = $db->prepare("
  SELECT id, name, description, start_date, finish_date, creation_date_stamp
  FROM events
  WHERE id = :id
  ORDER BY creation_date_stamp ASC
");

//Here we force the value of the username, facking a session start:
$event_id = $_GET['eventid'];
$itemsQuery1->execute([
//  'user' => $_SESSION['userid']
  'id' => $event_id
]);

//Now we get the previous query on a array:
$items1 = $itemsQuery1->rowCount() ? $itemsQuery1 : [];
//foreach($items as $item) {
//  print_r($item);
//}


$itemsQuery2 = $db->prepare("
  SELECT id, amount, name, description, category, date, made_by, event_id, creation_date_stamp
  FROM expenses
  WHERE event_id = :event_id
  ORDER BY creation_date_stamp ASC
");
$itemsQuery2->execute([
  'event_id' => $event_id
]);
$items2 = $itemsQuery2->rowCount() ? $itemsQuery2 : [];

$itemsQuery3 = $db->prepare("
    SELECT * FROM (
      SELECT id, SUM(amount), name, description, category, date, made_by, event_id, creation_date_stamp
      FROM expenses
      WHERE event_id = :event_id
      GROUP BY made_by) AS data
    ORDER BY `SUM(amount)` DESC
");
$itemsQuery3->execute([
  'event_id' => $event_id
]);
$items3 = $itemsQuery3->rowCount() ? $itemsQuery3 : [];


$itemsQuery4 = $db->prepare("
  SELECT * FROM (
    SELECT id, SUM(amount), name, description, category, date, made_by, event_id, creation_date_stamp
    FROM expenses
    WHERE event_id = :event_id
    GROUP BY made_by) AS data
  ORDER BY `SUM(amount)` DESC
");
$itemsQuery4->execute([
  'event_id' => $event_id,
]);
$items4 = $itemsQuery4->rowCount() ? $itemsQuery4 : [];









?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
  <title>SplitSmart - Split your expenses</title>

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="lib/chart-master/Chart.js"></script>

</head>

<body>
  <section id="container">
    <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
      </div>
      <!--logo start-->
      <a href="index.php" class="logo"><b>Split<span>Smart</span></b></a>
      <!--logo end-->

      <!--<div class="top-menu">
        <ul class="nav pull-right top-menu">
          <li><a class="logout" href="login.html">Logout</a></li>
        </ul>
      </div> -->
    </header>
    <!--header end-->
    <!-- **********************************************************************************************************************************************************
        MAIN SIDEBAR MENU
        *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
      <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">

          <li class="sub-menu">
            <a class="active" href="">
              <i class="fa fa-folder"></i>
              <span>Events</span>
            </a>
            <ul class="sub">
              <li class="active"><a href="events_list.php">List of events</a></li>
              <li><a href="events_new.php">Create new event</a></li>
            </ul>
          </li>
          <li class="sub-menu">
            <a class="" href="">
              <i class="fa fa-users"></i>
              <span>People</span>
            </a>
            <ul class="sub">
              <li><a href="people_list.php">List of people</a></li>
              <li><a href="people_new.php">Create new person</a></li>
            </ul>
          </li>
          <li class="sub-menu">
            <a class="" href="">
              <i class="fa fa-dollar"></i>
              <span>Expenses</span>
            </a>
            <ul class="sub">
              <li><a href="expenses_list.php">List of expenses</a></li>
              <li><a href="expenses_new.php">Create new expense</a></li>
              <li><a href="expenses_categories_list.php">List of categories</a></li>
              <li><a href="expenses_categories_new.php">Create new category</a></li>
            </ul>
          </li>
        </ul>
        <!-- sidebar menu end-->
      </div>
    </aside>
    <!--sidebar end-->
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">

        <h2 class="centered"><?php
        if(!empty($items1)):
          foreach($items1 as $item):
            echo $item['name'];
          endforeach;
        endif;
        ?></h2>


    <a href="<?php echo 'expenses_new.php?event='.$event_id; ?>"><input type="submit" value="create a new expense" class="submit"></a>


    <!-- Payments so far table -->
    <div class="row mt">
      <div class="col-md-12">
        <div class="content-panel">
          <table class="table table-bordered table-striped table-condensed">
            <h4><i class="fa fa-angle-right"></i> Payments made so far:</h4>
            <hr>
            <thead>
              <tr>
                <th><i class="fa fa-user"></i> Person</th>
                <th><i class="fa fa-dollar"></i> Amount</th>
              </tr>
            </thead>
            <tbody>
            <?php if(!empty($items3)): ?>
              <?php foreach($items3 as $item): ?>
                <tr>
                  <td><?php echo $item['made_by'];?></td>
                  <td><?php echo $item['SUM(amount)']." €";?></td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <!-- /content-panel -->
      </div>
      <!-- /col-md-12 -->
    </div>
    <!-- End payments so far table -->










    <!-- Start split cost program -->
    <?php
      //Total event payment:
      //Number of people:
      //Payments array:
      $total_event_cost = 0;
      $number_of_people = 0;
      $payments_array = array();
      $payers_array = array();
      foreach ($items4 as $item){
        $total_event_cost = $total_event_cost + $item['SUM(amount)'];
        $number_of_people = $number_of_people + 1;
        array_push($payments_array, $item['SUM(amount)']);
        array_push($payers_array, $item['made_by']);
      }
      //Splitted event cost:
      $splitted_cost = $total_event_cost / $number_of_people;

      //echo "</br>Total_event_cost: ".$total_event_cost;
      //echo "</br>number_of_people: ".$number_of_people;
      //echo "</br>Splitted_cost: ".$splitted_cost;
      //print_r($payments_array);
      //print_r($payers_array);

      //Splitted cost array:
      $i = 0;
      $splitted_cost_array = array();
      while ($i < $number_of_people) {
        array_push($splitted_cost_array, -$splitted_cost);
        $i = $i + 1;
      }
      //print_r($splitted_cost_array);

      //To pay array:
      $difference_array = array_map(function () {
          return array_sum(func_get_args());
      }, $payments_array, $splitted_cost_array);
      //print_r($difference_array);


      //Generation of the Debtors Array:
      function debtors($array){
        $n = sizeof($array);
        $debtors_array = array();
        //$i = 1;
        foreach ($array as $array_item) {
          if ($array_item < 0){
            array_push($debtors_array, $array_item);
          }
          else{
            array_push($debtors_array, 0);
          }
        }
        return $debtors_array;
      }
      $debtors_array = debtors($difference_array);
      //print_r ($debtors_array);

      //Generation of the Owners Array:
      function owners($array){
        $n = sizeof($array);
        $owners_array = array();
        //$i = 1;
        foreach ($array as $array_item) {
          if ($array_item > 0){
            array_push($owners_array, $array_item);
          }
          else{
            array_push($owners_array, 0);
          }
        }
        return $owners_array;
      }
      $owners_array = owners($difference_array);
      //print_r ($owners_array);


      //Function to select the maximum value in an array:
      function maximum($array){
        $n = sizeof($array);
        $max = 0;
        foreach ($array as $array_item) {
          if ($array_item > $max){
            $max = $array_item;
          }
        }
        return $max;
      }
      $max = maximum($owners_array);
      //print $max;

      //Function to select the minimum value in an array:
      function minimum($array){
        $n = sizeof($array);
        $min = 0;
        foreach ($array as $array_item) {
          if ($array_item < $min){
            $min = $array_item;
          }
        }
        return $min;
      }
      $min = minimum($debtors_array);
      //print $min;

      //print_r($owners_array[1]);

      //Begining of the table containing the results:
      echo '
        <div class="row mt">
          <div class="col-md-12">
            <div class="content-panel">
              <table class="table table-bordered table-striped table-condensed">
                <h4><i class="fa fa-angle-right"></i> Splitted Cost:</h4>
                <h6><i class="fa fa-question-circle"></i> With the expenses made so far in this event, this is the optimal combination to split costs equally among all the participants.</h6>
                <hr>
                <tbody>';
      //While the sim of Owners Array is different than 0, that means there's still debt to pay to those people:
      while (array_sum($owners_array) > 0.001){
        //Calculation of the maximum and minimum to converge to the smallest number of combinantions of payments:
        $owners_max = maximum($owners_array);
        $debtors_min = minimum($debtors_array);

        //Iteration of the payment:
        $index = 0;
        foreach ($owners_array as $owners_array_item) {
          if ($owners_array_item == $owners_max){
            $index_owners = $index;
          }
          $index = $index + 1;
        }
        $index = 0;
        foreach ($debtors_array as $debtors_array_item) {
          if ($debtors_array_item == $debtors_min){
            $index_debtors = $index;
          }
          $index = $index + 1;
        }

        $operation_result = $owners_max + $debtors_min;

        if($operation_result == 0){
          //Both people have close the debt
          $owners_array_replacement = array($index_owners => 0);
          $owners_array = array_replace($owners_array, $owners_array_replacement);

          $debtors_array_replacement = array($index_debtors => 0);
          $debtors_array = array_replace($debtors_array, $debtors_array_replacement);

      		$amount_to_pay = $owners_max;
        }
        elseif ($operation_result > 0) {
          //The owner still has to receive from other person
      		//The debtor has payed his debt
          $owners_array_replacement = array($index_owners => $owners_max + $debtors_min);
          $owners_array = array_replace($owners_array, $owners_array_replacement);

          $debtors_array_replacement = array($index_debtors => 0);
          $debtors_array = array_replace($debtors_array, $debtors_array_replacement);

          $amount_to_pay = -$debtors_min;
        }
        else {
          //The owner has received all of his owns
      		//The debtor still has to pay
          $owners_array_replacement = array($index_owners => 0);
          $owners_array = array_replace($owners_array, $owners_array_replacement);

          $debtors_array_replacement = array($index_debtors => $debtors_min + $owners_max);
          $debtors_array = array_replace($debtors_array, $debtors_array_replacement);

          $amount_to_pay = $owners_max;
        }




                      echo '<tr>
                              <td><strong>';
                                print_r($payers_array[$index_debtors]);
                        echo '</strong></td>
                              <td>';
                         echo " has to pay <strong>".number_format((float)$amount_to_pay, 2, ',', '')." €</strong> to ";
                         echo '</td>
                               <td><strong>';
                               print_r($payers_array[$index_owners]);
                         echo '</strong></td>';
                         echo '<td>
                              to split equally the costs of the event.</td>
                             </tr>';



      }
      //Ending of the table containing the results:
                echo '
              </tbody>
            </table>
          </div>
        </div>
      </div>';
    ?>
    <!--End split cost program -->





        <!-- Start expenses table -->
        <div class="row mt">
          <div class="col-md-12">
            <div class="content-panel">
              <table class="table table-bordered table-striped table-condensed">
                <h4><i class="fa fa-angle-right"></i> List of Expenses:</h4>
                <hr>
                <thead>
                  <tr>
                    <th><i class="fa fa-bookmark"></i> Name</th>
                    <th><i class="fa fa-question-circle"></i> Descrition</th>
                    <th><i class="fa fa-dollar"></i> Amount</th>
                    <th><i class=" fa fa-calendar"></i> Date</th>
                    <th><i class=" fa fa-user"></i> Made by</th>
                    <th><i class=" fa fa-edit"></i> Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php if(!empty($items2)): ?>
                  <?php foreach($items2 as $item): ?>
                    <tr>
                      <td><?php echo $item['name'];?></td>
                      <td><?php echo $item['description'];?></td>
                      <td><?php echo $item['amount']." €";?></td>
                      <td><?php echo $item['date'];?></td>
                      <td><?php echo $item['made_by'];?></td>
                      <td>
                        <a href= "<?php echo 'includes/delete.inc.php?status=delete&item='.$item['id'].'&eventid='.$event_id;?>"><button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button></a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <!-- /content-panel -->
          </div>
          <!-- /col-md-12 -->
        </div>
        <!-- /row -->
      </section>
      <?php
      //Version variables
      require 'config.php';
      echo '</br><p align="center" class="terms">'.$version_id.'<br/>made with <a class="regular">♥</a> and <strong>< / ></strong> by <a class="regular" href="https://marcoslopezsite.com" target="blank">Marcos López</a></p>';
      ?>
    </section>
    <!--main content end-->
    <!--
    <footer class="site-footer">
      <div class="text-center">
        <p>
          &copy; Copyrights <strong>Dashio</strong>. All Rights Reserved
        </p>
        <div class="credits">
          Created with Dashio template by <a href="https://templatemag.com/">TemplateMag</a>
        </div>
        <a href="index.html#" class="go-top">
          <i class="fa fa-angle-up"></i>
          </a>
      </div>
    </footer>
    -->
  </section>
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="lib/jquery/jquery.min.js"></script>

  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="lib/jquery.sparkline.js"></script>
  <!--common script for all pages-->
  <script src="lib/common-scripts.js"></script>
  <script type="text/javascript" src="lib/gritter/js/jquery.gritter.js"></script>
  <script type="text/javascript" src="lib/gritter-conf.js"></script>
  <!--script for this page-->
  <script src="lib/sparkline-chart.js"></script>
  <script src="lib/zabuto_calendar.js"></script>

  <!--script for confirmation message-->
  <?php
    if(isset($_GET['action'])){
        echo '
        <script type="text/javascript">
        $(document).ready(function() {
          var unique_id = $.gritter.add({
            // (string | mandatory) the heading of the notification
            title: "The entry has been deleted!",
            // (string | mandatory) the text inside the notification
            text: "Check the list of expenses inside the event for more information.",
            // (bool | optional) if you want it to fade out on its own or just sit there
            sticky: false,
            // (int | optional) the time you want it to be alive for before fading out
            time: 4000,
            // (string | optional) the class name you want to apply to that specific message
            class_name: "my-sticky-class"
          });

          return false;
        });
      </script>
        ';
    }
  ?>

  <script type="application/javascript">
    $(document).ready(function() {
      $("#date-popover").popover({
        html: true,
        trigger: "manual"
      });
      $("#date-popover").hide();
      $("#date-popover").click(function(e) {
        $(this).hide();
      });

      $("#my-calendar").zabuto_calendar({
        action: function() {
          return myDateFunction(this.id, false);
        },
        action_nav: function() {
          return myNavFunction(this.id);
        },
        ajax: {
          url: "show_data.php?action=1",
          modal: true
        },
        legend: [{
            type: "text",
            label: "Special event",
            badge: "00"
          },
          {
            type: "block",
            label: "Regular event",
          }
        ]
      });
    });

    function myNavFunction(id) {
      $("#date-popover").hide();
      var nav = $("#" + id).data("navigation");
      var to = $("#" + id).data("to");
      console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
    }
  </script>
</body>

</html>
