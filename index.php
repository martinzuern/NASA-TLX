<?php
require_once('config.php');

session_start();
$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;
$_SESSION['token_time'] = time();

// Specify location of translation tables
$language = isset($_GET['lang']) ? $_GET['lang'] : $defaultLang;
putenv("LANG=$language");
setlocale(LC_ALL, $language);
bindtextdomain('messages', "./locale");
textdomain('messages');
bind_textdomain_codeset('messages', 'UTF-8');

if(!$dynamic_participants AND (!isset($_GET['id']) OR strlen($_GET['id']) < 1)) {
  die(gettext("You have to submit an ID."));
}

?>
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
      // Participant vars
      var participant_id = "<?php echo $_GET['id']; ?>";
      var token = "<?php echo $token; ?>";

      // Create a set of parallel arrays for each of the scales
      var scale = new Array();
      var left = new Array();
      var right = new Array();
      var def = new Array();
      var NUM_SCALES = 6;

      scale[0] = '<?= gettext("Mental Demand") ?>';
      left[0] = '<?= gettext("Low") ?>';
      right[0] = '<?= gettext("High") ?>';
      def[0] = '<?= gettext("How much mental and perceptual activity was required (e.g. thinking, deciding, calculating, remembering, looking, searching, etc)? Was the task easy or demanding, simple or complex, exacting or forgiving?") ?>';

      scale[1] = '<?= gettext("Physical Demand") ?>';
      left[1] = '<?= gettext("Low") ?>';
      right[1] = '<?= gettext("High") ?>';
      def[1] = '<?= gettext("How much physical activity was required (e.g. pushing, pulling, turning, controlling, activating, etc)? Was the task easy or demanding, slow or brisk, slack or strenuous, restful or laborious?") ?>';

      scale[2] = '<?= gettext("Temporal Demand") ?>';
      left[2] = '<?= gettext("Low") ?>';
      right[2] = '<?= gettext("High") ?>';
      def[2] = '<?= gettext("How much time pressure did you feel due to the rate of pace at which the tasks or task elements occurred? Was the pace slow and leisurely or rapid and frantic?") ?>';

      scale[3] = '<?= gettext("Performance") ?>';
      left[3] = '<?= gettext("Good") ?>';
      right[3] = '<?= gettext("Poor") ?>';
      def[3] = '<?= gettext("How successful do you think you were in accomplishing the goals of the task set by the experimenter (or yourself)? How satisfied were you with your performance in accomplishing these goals?") ?>';

      scale[4] = '<?= gettext("Effort") ?>';
      left[4] = '<?= gettext("Low") ?>';
      right[4] = '<?= gettext("High") ?>';
      def[4] = '<?= gettext("How hard did you have to work (mentally and physically) to accomplish your level of performance?") ?>';

      scale[5] = '<?= gettext("Frustration") ?>';
      left[5] = '<?= gettext("Low") ?>';
      right[5] = '<?= gettext("High") ?>';
      def[5] = '<?= gettext("How insecure, discouraged, irritated, stressed and annoyed versus secure, gratified, content, relaxed and complacent did you feel during the task?") ?>';

      var scale_error = '<?= gettext("A value must be selected for every scale!") ?>';
    </script>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/nasatlx.css">
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

          <p class="lead"><?= gettext("Click on each scale at the point that best indicates your experience of the task.") ?></p>

          <p><?= gettext("In other words: think back on what was easy and what was difficult or annoying about the task. How low or high were the mental demands, physical demands, etc... Drag your cursor over the question marks for more information.") ?></p>

          <p><strong><?= gettext("There are no right or wrong answers. If something is not entirely clear, follow your intuition.") ?></strong></p>

          <div id="scale0"></div>
          <div id="scale1"></div>
          <div id="scale2"></div>
          <div id="scale3"></div>
          <div id="scale4"></div>
          <div id="scale5"></div>

          <br>
          <input class="next btn btn-primary pull-right" id="next" type="button" value="<?= gettext("Next") ?> >>" onclick="buttonPart1();">
        </div>

        <div id="div_part2" style="display:none">

          <p class="lead"><?= gettext("One each of the following 15 screens, click on the scale title that represents the more important contributor to workload for the task.") ?></p>

          <p><strong><?= gettext("In other words: which factor made the task more tedious than the other?") ?></strong></p>

          <br>
          <input class="next btn btn-primary pull-right" id="next" type="button" value="<?= gettext("Next") ?> >>" onclick="buttonPart2();">
        </div>

        <div id="div_part3" style="display:none">

          <p class="lead"><?= gettext("Click on the factor that represents the more important contributor to workload for the task.") ?></p>

          <p><strong><?= gettext("In other words: which factor made the task more tedious than the other?") ?></strong></p>

          <br>


          <div class="row">
            <div class="col-md-8">
              <button class="pair btn btn-default btn-lg btn-block" id="pair1" type="button" onclick="buttonPair1();"></button>
            </div>
            <div class="col-md-4">
              <div id="pair1_def"></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8" style="text-align: center;">
              <?= gettext("or") ?>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8">
              <button class="pair btn btn-default btn-lg btn-block" id="pair2" type="button" onclick="buttonPair2();"></button>
            </div>
            <div class="col-md-4">
              <div id="pair2_def"></div>
            </div>
          </div>

        </div>

        <div id="div_part4" style="display:none">
          <p class="lead"><?= gettext("Results") ?></p>
          <div id="success" style="display:none">
            <div class="alert alert-success">
                <strong><?= gettext("All done!") ?></strong> <?= gettext("Results submitted. Thank you for participating.") ?>
            </div>

            <?php if($showCloseButton) {?>
            <a role="button" class="btn btn-primary btn-lg" href="javascript:window.open('','_self').close();"><?= gettext("Close") ?></a>
            <?php } ?>

          </div>
          <div id="error" class="alert alert-danger" style="display:none">
            <strong><?= gettext("Error!") ?></strong> <?= gettext("Something went wrong when submitting the results:") ?><br/>
            <span id='error_msg'></span>
          </div>
          <div id="results" <?php if(!$showResults) {?> style="display:none" <?php } ?>>
          </div>
        </div>

      </div>

    </div>

    <div id="footer">
      <div class="container">
        <p class="text-muted credit"></p>
      </div>
    </div>

    <script src="js/jquery-1.12.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/nasatlx.js"></script>
  </body>
</html>
