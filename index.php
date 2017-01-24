<?php
session_start();
$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;
$_SESSION['token_time'] = time();

require_once('config.php');?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>NASA Task Load Index</title>

    <!--

     This implements the NASA TLX via a single web page using JavaScript.
     It first collects the user's rating for 6 scale, the user can
     click on one of 20 different positions equating to a rating of 5-100
     in increments of 5. The user then selects the more important scale
     in 15 pairings presented in random order.

     Copyright 2011 by Keith Vertanen
     http://www.keithv.com/software/nasa_tlx

    -->

    <script language="JavaScript" type="text/javascript">
    <!--
      // Participant vars
      var participant_id = "<?php echo $_GET['id']; ?>";
      var token = "<?php echo $token; ?>";

      // Create a set of parallel arrays for each of the scales
      var scale = new Array();
      var left = new Array();
      var right = new Array();
      var def = new Array();
      var NUM_SCALES = 6;

      scale[0] = "Mental Demand";
      left[0] = "Low";
      right[0] = "High";
      def[0] = "How much mental and perceptual activity was required (e.g. thinking, deciding, calculating, remembering, looking, searching, etc)? Was the task easy or demanding, simple or complex, exacting or forgiving?";

      scale[1] = "Physical Demand";
      left[1] = "Low";
      right[1] = "High";
      def[1] = "How much physical activity was required (e.g. pushing, pulling, turning, controlling, activating, etc)? Was the task easy or demanding, slow or brisk, slack or strenuous, restful or laborious?";

      scale[2] = "Temporal Demand";
      left[2] = "Low";
      right[2] = "High";
      def[2] = "How much time pressure did you feel due to the rate of pace at which the tasks or task elements occurred? Was the pace slow and leisurely or rapid and frantic?";

      scale[3] = "Performance";
      left[3] = "Good";
      right[3] = "Poor";
      def[3] = "How successful do you think you were in accomplishing the goals of the task set by the experimenter (or yourself)? How satisfied were you with your performance in accomplishing these goals?";

      scale[4] = "Effort";
      left[4] = "Low";
      right[4] = "High";
      def[4] = "How hard did you have to work (mentally and physically) to accomplish your level of performance?";

      scale[5] = "Frustration";
      left[5] = "Low";
      right[5] = "High";
      def[5] = "How insecure, discouraged, irritated, stressed and annoyed versus secure, gratified, content, relaxed and complacent did you feel during the task?";

      var scale_error = 'A value must be selected for every scale!';
      // -->
    </script>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nasatlx.css">

    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/nasatlx.js"></script>

  </head>


  <body onload="onLoad();">

    <div id="wrap">

      <div class="container">

        <div class="page-header">
          <h1>NASA-TLX</h1>
        </div>

<?php if($dynamic_participants) { ?>
        <div class="row" id="participant_id_form">
          <div class="col-md-6">
            <div class="form-group">
              <label for="part_id_1">ID #1</label>
              <input type="text" class="form-control" id="part_id_1" placeholder="01">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="part_id_2">ID #2</label>
              <input type="text" class="form-control" id="part_id_2" placeholder="01">
            </div>
          </div>
        </div>
<?php } ?>

        <div id="div_part1">

          <p class="lead">Click on each scale at the point that best indicates your experience of the task.</p>

          <p>In other words: think back on what was easy and what was difficult or annoying about the task. How low or high were the mental demands, physical demands, etc... Drag your cursor over the question marks for more information.</p>

          <p><strong>There are no right or wrong answers. If something is not entirely clear, follow your intuition.</strong></p>

          <div id="scale0"></div>
          <div id="scale1"></div>
          <div id="scale2"></div>
          <div id="scale3"></div>
          <div id="scale4"></div>
          <div id="scale5"></div>

          <br>
          <input class="next btn btn-primary pull-right" id="next" type="button" value="Continue &gt;&gt;" onclick="buttonPart1();">
        </div>

        <div id="div_part2" style="display:none">

          <p class="lead">One each of the following 15 screens, click on the scale title that represents the more important contributor to workload for the task.</p>

          <p><strong>In other words: which factor made the task more tedious than the other?</strong></p>

          <br>
          <input class="next btn btn-primary pull-right" id="next" type="button" value="Continue &gt;&gt;" onclick="buttonPart2();">
        </div>

        <div id="div_part3" style="display:none">

          <p class="lead">Click on the factor that represents the more important contributor to workload for the task.</p>

          <p><strong>In other words: which factor made the task more tedious than the other?</strong></p>

          <br>
          <table>
            <tbody><tr>
                <td><input class="pair" id="pair1" type="button" value="" onclick="buttonPair1();"> </td>
                <td class="def"><div id="pair1_def"></div></td>
              </tr>
              <tr>
                <td align="center"> or </td>
                <td></td>
              </tr>
              <tr>
                <td><input class="pair" id="pair2" type="button" value="" onclick="buttonPair2();"></td>
                <td class="def"><div id="pair2_def"></div></td>
              </tr>
            </tbody></table>
        </div>

        <div id="div_part4" style="display:none">
          <p class="lead">Results</p>
          <div id="success" class="alert alert-success" style="display:none">
            <strong>All done!</strong> Results submitted. Thank you for participating.
          </div>
          <div id="error" class="alert alert-danger" style="display:none">
            <strong>Error!</strong> Something went wrong when submitting the results:<br/>
            <span id='error_msg'></span>
          </div>
          <div id="results">
          </div>
        </div>

      </div>

    </div>

    <div id="footer">
      <div class="container">
        <p class="text-muted credit"></p>
      </div>
    </div>


  </body>
</html>
