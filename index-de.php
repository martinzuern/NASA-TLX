<?php
session_start();
$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;
$_SESSION['token_time'] = time();

require_once('config.php');
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

      scale[0]  = "Geistige Anforderung";
      left[0]   = "Gering";
      right[0]  = "Hoch";
      def[0]    = "Wie viel geistige Anforderung war bei der Informationsaufnahme und bei der Informationsverarbeitung erforderlich (z.B. Denken, Entscheiden, Rechnen, Erinnern, Hinsehen, Suchen ...)? War die Aufgabe leicht oder anspruchsvoll, einfach oder komplex, erfordert sie hohe Genauigkeit oder ist sie fehlertolerant?";

      scale[1]  = "Körperliche Anforderung";
      left[1]   = "Gering";
      right[1]  = "Hoch";
      def[1]    = "Wie viel körperliche Aktivität war erforderlich (z.B. ziehen, drücken, drehen, steuern, aktivieren ...)? War die Aufgabe leicht oder schwer, einfach oder anstrengend, erholsam oder mühselig?";

      scale[2]  = "Zeitliche Anforderung";
      left[2]   = "Gering";
      right[2]  = "Hoch";
      def[2]    = "Wie viel Zeitdruck empfanden Sie hinsichtlich der Häufigkeit oder dem Takt mit dem die Aufgaben oder Aufgabenelemente auftraten? War die Aufgabe langsam und geruhsam oder schnell und hektisch?";

      scale[3]  = "Leistung";
      left[3]   = "Gut";
      right[3]  = "Schlecht";
      def[3]    = "Wie erfolgreich haben Sie Ihrer Meinung nach die vom Versuchsleiter (oder Ihnen selbst) gesetzten Ziele erreicht? Wie zufrieden waren Sie mit Ihrer Leistung bei der Verfolgung dieser Ziele?";

      scale[4]  = "Anstrengung";
      left[4]   = "Gering";
      right[4]  = "Hoch";
      def[4]    = "Wie hart mussten Sie arbeiten, um Ihren Grad an Aufgabenerfüllung zu erreichen?";

      scale[5]  = "Frustration";
      left[5]   = "Gering";
      right[5]  = "Hoch";
      def[5]    = "Wie unsicher, entmutigt, irritiert, gestresst und verörgert (versus sicher, bestätigt, zufrieden, entspannt und zufrieden mit sich selbst) fühlten Sie sich während der Aufgabe?";

      var scale_error = 'Für jede Skala muss ein Wert gewählt werden!';
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

          <p class="lead">Klicken Sie in jeder Skale auf den Punkt, der Ihre Erfahrung im Hinblick auf die Aufgabe am besten verdeutlicht.</p>

          <p>In anderen Worten: Erinnern Sie sich an die Aufgabe, und was daran einfach, schwer oder anstrengend war. Klicke auf das Fragezeichen, um zu jeder Frage weitere Informationen zu erhalten.</p>

          <p><strong>Es gibt keine richtigen oder falschen Antworten. Falls etwas nicht eindeutig für Sie erscheint, folgen Sie ihrer Intuition.</strong></p>

          <div id="scale0"></div>
          <div id="scale1"></div>
          <div id="scale2"></div>
          <div id="scale3"></div>
          <div id="scale4"></div>
          <div id="scale5"></div>

          <br>
          <input class="next btn btn-primary pull-right" id="next" type="button" value="Fortfahren &gt;&gt;" onclick="buttonPart1();">
        </div>

        <div id="div_part2" style="display:none">

          <p class="lead">Klicken Sie in jedem der 15 nachfolgenden Fenster auf die Beanspruchungsdimension, die für das Gesamtempfinden hinsichtlich der Aufgabe die jeweils bedeutsamere war.</p>

          <p><strong>In anderen Worten: Welche der beiden Dimensionen machte die Aufgabe fordernder?</strong></p>

          <br>
          <input class="next btn btn-primary pull-right" id="next" type="button" value="Fortfahren &gt;&gt;" onclick="buttonPart2();">
        </div>

        <div id="div_part3" style="display:none">

          <p class="lead">Klicken Sie auf die Dimension, die den jeweils wichtigeren Beitrag zur Arbeitsbelastung hinsichtlich der Aufgabe darstellt.</p>

          <p><strong>In anderen Worten: Welche der beiden Dimensionen machte die Aufgabe fordernder?</strong></p>

          <br>
          <table>
            <tbody><tr>
                <td><input class="pair" id="pair1" type="button" value="" onclick="buttonPair1();"> </td>
                <td class="def"><div id="pair1_def"></div></td>
              </tr>
              <tr>
                <td align="center"> oder </td>
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
            <strong>Alles erledigt!</strong> Ergebnisse übermittelt.
          </div>
          <div id="error" class="alert alert-danger" style="display:none">
            <strong>Fehler!</strong> Beim Übermitteln der Ergbenisse ist was falsch gelaufen:<br/>
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