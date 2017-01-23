/**
 * NASA-TLX functions
 */

// Pairs of factors in order in the original instructions, numbers
// refer to the index in the scale, left, right, def arrays.
var pair = new Array();
pair[0] = "4 3";
pair[1] = "2 5";
pair[2] = "2 4";
pair[3] = "1 5";
pair[4] = "3 5";
pair[5] = "1 2";
pair[6] = "1 3";
pair[7] = "2 0";
pair[8] = "5 4";
pair[9] = "3 0";
pair[10] = "3 2";
pair[11] = "0 4";
pair[12] = "0 1";
pair[13] = "4 1";
pair[14] = "5 0";

// Variable where the results end up
var results_rating = new Array();
var results_tally = new Array();
var results_weight = new Array();
var results_overall;

var pair_num = 0;
for (var i = 0; i < NUM_SCALES; i++)
  results_tally[i] = 0;

// Used to randomize the pairings presented to the user
function randOrd()
{
  return (Math.round(Math.random()) - 0.5);
}

// Make sure things are good and mixed
for (i = 0; i < 100; i++)
{
  pair.sort(randOrd);
}

// They click on a scale entry
function scaleClick(index, val)
{
  results_rating[index] = val;

  // Turn background color to white for all cells
  for (i = 5; i <= 100; i += 5)
  {
    var top = "t_" + index + "_" + i;
    var bottom = "b_" + index + "_" + i;
    document.getElementById(top).bgColor = '#FFFFFF';
    document.getElementById(bottom).bgColor = '#FFFFFF';
  }

  var top = "t_" + index + "_" + val;
  var bottom = "b_" + index + "_" + val;
  document.getElementById(top).bgColor = '#AAAAAA';
  document.getElementById(bottom).bgColor = '#AAAAAA';
}

// Return the HTML that produces the table for a given scale
function getScaleHTML(index)
{
  var result = "";

  // Outer table with a column for scale, column for definition
  result += '<table class="center-table"><tr><td>';

  // Table that generates the scale
  result += '<table class="scale">';

  // Row 1, just the name of the scale
  result += '<tr><td colspan="20" class="heading">' + scale[index] + '</td></tr>';

  // Row 2, the top half of the scale increments, 20 total columns
  result += '<tr>';
  var num = 1;
  for (var i = 5; i <= 100; i += 5)
  {
    result += '<td id="t_' + index + '_' + i + '"   class="top' + num + '" onMouseUp="scaleClick(' + index + ', ' + i + ');"></td>';
    num++;
    if (num > 2)
      num = 1;
  }
  result += '</tr>';

  // Row 3, bottom half of the scale increments
  result += '<tr>';
  for (var i = 5; i <= 100; i += 5)
  {
    result += '<td id="b_' + index + '_' + i + '"   class="bottom" onMouseUp="scaleClick(' + index + ', ' + i + ');"></td>';
  }
  result += '</tr>';

  // Row 4, left and right of range labels
  result += '<tr>';
  result += '<td colspan="10" class="left">' + left[index] + '</td><td colspan="10" class="right">' + right[index] + '</td>';
  result += '</tr></table></td>';


  // Now for the definition of the scale
  result += '<td class="def">';
  result += generateDefinition(index);
  result += '</td></tr></table>';

  return result;
}

function generateDefinition(index) {
  var d = '<a tabindex="0" class="btn" role="button" data-toggle="popover" data-placement="top" data-trigger="focus" ';
  d += 'title="' + scale[index] + '" ';
  d += 'data-content="' + def[index] + '">';
  d += '<i class="icon-question-sign icon-2x"></i></a>';
  return d;
}

function onLoad()
{
  // Get all the scales ready
  for (var i = 0; i < NUM_SCALES; i++)
  {
    document.getElementById("scale" + i).innerHTML = getScaleHTML(i);
  }
  $("[data-toggle=popover]").popover();
}

// Users want to proceed after doing the scales
function buttonPart1()
{
  $('input[type=button]').attr('disabled', 'disabled');
  setTimeout('$("input[type=button]").removeAttr("disabled")', 1200);

  // Check to be sure they click on every scale
  for (var i = 0; i < NUM_SCALES; i++)
  {
    if (!results_rating[i])
    {
      alert(scale_error);
      return false;
    }
  }

  // Bye bye part 1, hello part 2
  document.getElementById('div_part1').style.display = 'none';
  document.getElementById('div_part2').style.display = '';

  return true;
}

// User done reading the part 2 instructions
function buttonPart2()
{
  $('input[type=button]').attr('disabled', 'disabled');
  setTimeout('$("input[type=button]").removeAttr("disabled")', 1200);

  // Bye bye part 2, hello part 3
  document.getElementById('div_part2').style.display = 'none';
  document.getElementById('div_part3').style.display = '';

  // Set the labels for the buttons
  setPairLabels();
  return true;
}

// Set the button labels for the pairwise comparison stage
function setPairLabels()
{
  var indexes = new Array();
  indexes = pair[pair_num].split(" ");

  var pair1 = scale[indexes[0]];
  var pair2 = scale[indexes[1]];

  document.getElementById('pair1').value = pair1;
  document.getElementById('pair2').value = pair2;

  document.getElementById('pair1_def').innerHTML = generateDefinition(indexes[0])
  document.getElementById('pair2_def').innerHTML = generateDefinition(indexes[1]);
  $("[data-toggle=popover]").popover();
}

// They clicked the top pair button
function buttonPair1()
{
  $('input[type=button]').attr('disabled', 'disabled');
  setTimeout('$("input[type=button]").removeAttr("disabled")', 1200);

  var indexes = new Array();
  indexes = pair[pair_num].split(" ");
  results_tally[indexes[0]]++;

  nextPair();
  return true;
}

// They clicked the bottom pair button
function buttonPair2()
{
  $('input[type=button]').attr('disabled', 'disabled');
  setTimeout('$("input[type=button]").removeAttr("disabled")', 1200);

  var indexes = new Array();
  indexes = pair[pair_num].split(" ");
  results_tally[indexes[1]]++;
  nextPair();
  return true;
}

// Compute the weights and the final score
function calcResults()
{
  results_overall = 0.0;

  for (var i = 0; i < NUM_SCALES; i++)
  {
    results_weight[i] = results_tally[i] / 15.0;
    results_overall += results_weight[i] * results_rating[i];
  }
}

// Output the table of results
function getResultsHTML()
{
  var result = "";

  result += "<table><tr><td></td><td>Rating</td><td>Tally</td><td>Weight</td></tr>";
  for (var i = 0; i < NUM_SCALES; i++)
  {
    result += "<tr>";

    result += "<td>";
    result += scale[i];
    result += "</td>";

    result += "<td>";
    result += results_rating[i];
    result += "</td>";

    result += "<td>";
    result += results_tally[i];
    result += "</td>";

    result += "<td>";
    result += results_weight[i];
    result += "</td>";

    result += "</tr>";
  }

  result += "</table>";
  result += "<br/>";
  result += "Overall = ";
  result += results_overall;
  result += "<br/>";

  return result;
}

// Move to the next pair
function nextPair()
{
  pair_num++;
  if (pair_num >= 15)
  {
    document.getElementById('div_part3').style.display = 'none';
    document.getElementById('div_part4').style.display = '';

    calcResults();

    var data = {
      scale: scale,
      results_rating: results_rating,
      results_tally: results_tally,
      results_weight: results_weight,
      results_overall: results_overall
    };

    $.ajax({
      type: 'POST',
      url: 'process.php?id=' + participant_id,
      data: {'data':JSON.stringify(data), 'token':token},
      dataType: 'json',
      success: dataSubmitted,
      error: dataError
    });

    document.getElementById('results').innerHTML = getResultsHTML();
  }
  else
  {
    setPairLabels();
  }
}

function dataSubmitted(data, status, xhr)
{
  var status = typeof data['status'] !== 'undefined' ? data['status'] : status;
  var msg = typeof data['msg'] !== 'undefined' ? data['msg'] : 'Error!';

  if (status == 'success')
    $('#success').show();
  else
    dataError(xhr, status, msg);
}

function dataError(xhr, status, error)
{
  $('#error_msg').text(error);
  $('#error').show();
}
