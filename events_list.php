<?php

//require 'config.php';
//session_start();
require 'includes/dbh.inc.php';

$itemsQuery = $db->prepare("
  SELECT id, name, description, start_date, finish_date, creation_date_stamp
  FROM events
  WHERE status = :status
  ORDER BY creation_date_stamp ASC
");

//Here we force the value of the username, facking a session start:

$itemsQuery->execute([
//  'user' => $_SESSION['userid']
  'status' => '1'
]);

//Now we get the previous query on a array:
$items = $itemsQuery->rowCount() ? $itemsQuery : [];
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
      <a href="index.html" class="logo"><b>Split<span>Smart</span></b></a>
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

        <h2 class="centered">List of active events</h2>
        <div class="row content-panel">

          <div class="col-md-10 col-md-offset-1 mt mb">
            <div class="accordion" id="accordion2">


              <?php if(!empty($items)): ?>
                <?php foreach($items as $item): ?>
                  <div class="accordion-group">
                    <div class="accordion-heading">
                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="<?php echo 'events_list.php#collapse'.$item['id'];?>">
                        <em class="glyphicon glyphicon-chevron-right icon-fixed-width"></em>
                        <?php
                          echo $item['name']; ?>
                          </a>
                        </div>
                        <div id="<?php echo 'collapse'.$item['id'];?>" class="accordion-body collapse">
                          <div class="accordion-inner">
                            <p>
                              <?php
                              echo '<strong>Description: </strong>'.$item['description'];
                              echo '</br><strong>Creation date: </strong>'.$item['creation_date_stamp'];
                              ?>
                            </br></br>
                            Click <a href="events_detail.php?eventid=<?php echo $item['id'];?>">here</a> to open the event.
                          </p>
                        </div>
                      </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
              <p>You haven't added any event yet. Go to <a href=events_new.php>Create new event</a> page to start.</p>
            <?php endif; ?>

          </div>
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
