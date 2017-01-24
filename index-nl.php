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

      scale[0] = "Mentale Eisen";
      left[0] = "Laag";
      right[0] = "Hoog";
      def[0] = "Hoeveel mentale en perceptuele activiteit was er vereist (bv. denken, beslissen, berekenen, herinneren, kijken, zoeken, enzovoort)? Was de taak gemakkelijk of veeleisend, simpel of complex, strikt of vergevend?";

      scale[1] = "Fysieke Eisen";
      left[1] = "Laag";
      right[1] = "Hoog";
      def[1] = "Hoeveel fysieke activiteit was er vereist (bv. duwen, trekken, draaien, controlleren, activeren, enzovoort)? Was de taak gemakkelijk of veeleisend, traag of vlug, licht of zwaar, rustgevend of arbeidsintensief?";

      scale[2] = "Tijdseisen";
      left[2] = "Laag";
      right[2] = "Hoog";
      def[2] = "Hoeveel tijdsdruk voelde je door de snelheid waarmee de taak of taken moesten gebeuren? Was het ritme traag en op het gemak, of snel en gehaast?";

      scale[3] = "Prestatie";
      left[3] = "Goed";
      right[3] = "Slecht";
      def[3] = "Hoe succesvol denk je dat je was in het bereiken van de doelen vooropgesteld door de proefleider (of door jezelf)? Hoe tevreden was je met je performantie in het bereiken van deze doelen?";

      scale[4] = "Inspanning";
      left[4] = "Laag";
      right[4] = "Hoog";
      def[4] = "Hoe hard moest je werken (mentaal en fysiek) om jouw niveau van performantie te bereiken?";

      scale[5] = "Frustratie";
      left[5] = "Laag";
      right[5] = "Hoog";
      def[5] = "Hoe onzeker, ontmoedigd, geirriteerd, gestrest en geergerd versus zeker, bevredigd, tevreden, ontspannen en zelfvoldaan voelde je tijdens de taak?";

      var scale_error = 'Een waarde moet geselecteerd worden voor elke schaal!';
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

          <p class="lead">Klik voor elke schaal op het punt dat jouw ervaring met de taak het beste aangeeft.</p>

          <p>Met andere woorden: denk terug aan wat er gemakkelijk was en wat er lastig was aan de taak. Hoe laag of hoog waren de mentale eisen, fysieke eisen, enzovoort... Sleep je cursor over de vraagtekens voor meer uitleg.</p>

          <p><strong>Er zijn geen juiste of foute antwoorden. Als er iets niet helemaal duidelijk is, volg dan je intuitie.</strong></p>

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

          <p class="lead">Voor elk van de volgende 15 schermen, klik op de factor die meer bijdraagt aan de werklast van de taak.</p>

          <p><strong>Met andere woorden: welke factor maakte het bijhouden van je dromen meer lastiger dan de andere?</strong></p>

          <br>
          <input class="next btn btn-primary pull-right" id="next" type="button" value="Continue &gt;&gt;" onclick="buttonPart2();">
        </div>

        <div id="div_part3" style="display:none">

          <p class="lead">Klik op de factor die meer bijdraagt aan de werklast van de taak.</p>

          <p><strong>Met andere woorden: welke factor maakte het bijhouden van je dromen meer lastiger dan de andere?</strong></p>

          <br>
          <table>
            <tbody><tr>
                <td><input class="pair" id="pair1" type="button" value="" onclick="buttonPair1();"> </td>
                <td class="def"><div id="pair1_def"></div></td>
              </tr>
              <tr>
                <td align="center"> of </td>
                <td></td>
              </tr>
              <tr>
                <td><input class="pair" id="pair2" type="button" value="" onclick="buttonPair2();"></td>
                <td class="def"><div id="pair2_def"></div></td>
              </tr>
            </tbody></table>
        </div>

        <div id="div_part4" style="display:none">
          <p class="lead">Resultaten</p>
          <div id="success" class="alert alert-success" style="display:none">
            <strong>Klaar!</strong> Resultaten verzonden. Bedankt voor je deelname.
          </div>
          <div id="error" class="alert alert-danger" style="display:none">
            <strong>Fout!</strong> Iets ging mis bij het verzenden van de resultaten:<br/>
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
