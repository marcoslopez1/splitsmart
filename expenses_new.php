<?php

//require 'config.php';
//session_start();
require 'includes/dbh.inc.php';

$itemsQuery1 = $db->prepare("
  SELECT id, name, description, start_date, finish_date, creation_date_stamp, status
  FROM events
  WHERE status = :status
  ORDER BY creation_date_stamp ASC
");

//Here we force the value of the username, facking a session start:

$itemsQuery1->execute([
//  'user' => $_SESSION['userid']
  'status' => '1'
]);

//Now we get the previous query on a array:
$items1 = $itemsQuery1->rowCount() ? $itemsQuery1 : [];
//foreach($items as $item) {
//  print_r($item);
//}



$itemsQuery2 = $db->prepare("
  SELECT id, name, description, creation_date_stamp
  FROM people
  ORDER BY creation_date_stamp ASC
");

//Here we force the value of the username, facking a session start:

$itemsQuery2->execute([
//  'user' => $_SESSION['userid']
]);

//Now we get the previous query on a array:
$items2 = $itemsQuery2->rowCount() ? $itemsQuery2 : [];
//foreach($items as $item) {
//  print_r($item);
//}




$itemsQuery3 = $db->prepare("
  SELECT id, name, description, creation_date_stamp
  FROM categories
  ORDER BY name
");

//Here we force the value of the username, facking a session start:

$itemsQuery3->execute([
//  'user' => $_SESSION['userid']
]);

//Now we get the previous query on a array:
$items3 = $itemsQuery3->rowCount() ? $itemsQuery3 : [];
//foreach($items as $item) {
//  print_r($item);
//}

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
            <a class="" href="">
              <i class="fa fa-folder"></i>
              <span>Events</span>
            </a>
            <ul class="sub">
              <li><a href="events_list.php">List of events</a></li>
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
            <a class="active" href="">
              <i class="fa fa-dollar"></i>
              <span>Expenses</span>
            </a>
            <ul class="sub">
              <li><a href="expenses_list.php">List of expenses</a></li>
              <li class="active"><a href="expenses_new.php">Create new expense</a></li>
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

        <h2 class="centered">Create a new expense</h2>
        <div class="row mt">
          <div class="col-lg-12">
            <div class="form-panel">
            </br>
              <form class="form-horizontal style-form" method="post" action="includes/new_expense.inc.php">
                <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Amount:</label>
                  <div class="col-sm-10">
                    <input class="form-control" id="focusedInput" type="number" name="amount" placeholder="Enter a name for the event" required>
                  </div>
                  </br></br>
                  <label class="col-sm-2 col-sm-2 control-label">Name:</label>
                  <div class="col-sm-10">
                    <input class="form-control" id="focusedInput" type="text" name="name" placeholder="Enter a name for the event" required>
                  </div>
                  </br></br>
                  <label class="col-sm-2 col-sm-2 control-label">Description:</label>
                  <div class="col-sm-10">
                    <input type="text" name="description" class="form-control" placeholder="Add a description if you need it">
                  </div>
                  </br></br>
                  <label class="col-sm-2 col-sm-2 control-label">Category:</label>
                  <div class="col-sm-10">
                    <select name="category" autocomplete="off" class="form-control">

                      <option disabled selected value> --Please, select --</option>
                      <?php if(!empty($items3)): ?>
                        <?php foreach($items3 as $item): ?>
                          <?php echo '<option value="'.$item['name'].'">'; ?>
                            <?php echo "<p align='left'>".$item['name']."</p>"; ?>
                          </option>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <p>You have to create at least one Category.</p>
                      <?php endif; ?>

                    </select>
                  </div>
                  </br></br>
                  <label class="col-sm-2 col-sm-2 control-label">Date:</label>
                  <div class="col-sm-10">
                    <input type="date" name="date" class="form-control" placeholder="Select a date">
                  </div>
                  </br></br>
                  <label class="col-sm-2 col-sm-2 control-label">Made by:</label>
                  <div class="col-sm-10">
                    <select name="made_by" autocomplete="off" id="focusedInput" class="form-control" required>

                      <option disabled selected value> --Please, select --</option>
                      <?php if(!empty($items2)): ?>
                        <?php foreach($items2 as $item): ?>
                          <?php echo '<option value="'.$item['name'].'">'; ?>
                            <?php echo "<p align='left'>".$item['name']."</p>"; ?>
                          </option>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <p>You have to create at least one Person.</p>
                      <?php endif; ?>

                    </select>
                  </div>
                  </br></br>
                  <label class="col-sm-2 col-sm-2 control-label">Event:</label>
                  <div class="col-sm-10">
                    <select name="event" autocomplete="off" id="focusedInput" class="form-control" required>

                      <option disabled selected value> --Please, select --</option>
                      <?php if(!empty($items1)): ?>
                        <?php foreach($items1 as $item): ?>
                          <?php echo '<option value="'.$item['name'].'">'; ?>
                            <?php echo "<p align='left'>".$item['name']."</p>"; ?>
                          </option>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <p>You have to create at least one Event.</p>
                      <?php endif; ?>

                    </select>
                  </div>


                </div>
                <input type="submit" value="create" class="submit">
              </form>
            </div>
          </div>
          <!-- col-lg-12-->
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
            title: "The entry has been created!",
            // (string | mandatory) the text inside the notification
            text: "Go to Llist of expenses page to see the list of active events.",
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
