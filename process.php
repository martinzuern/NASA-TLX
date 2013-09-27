<?php
session_start();

require_once('config.php');

if (!empty($_GET['id']) && in_array($_GET['id'], $participants) 
  && !empty($_POST['token']) && $_POST['token']===$_SESSION['token'] && !empty($_POST['data']))
{
  $json = json_decode($_POST['data']);
  
  $csv = array();
  $csv[] = array(date('c'), $_GET['id'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_X_FORWARDED_FOR'], $_SERVER['HTTP_USER_AGENT']);
  for ($i=0;$i<=5;$i++)
  {
    $csv[] = array($json->scale[$i], $json->results_rating[$i], $json->results_tally[$i], $json->results_weight[$i]);
  }
  $csv[] = array($json->results_overall);
  
  $resultsfile = $results_folder_path . '/nasatlx_'.date('Y-m-d_H-i').'_'.$_GET['id'].'.csv';
  $fp = fopen($resultsfile, 'w+');
  foreach ($csv as $line)
    fputcsv($fp, $line, "\t");
  fclose($fp);
  
  echo json_encode(array(
    'status' => 'success',
    'msg' => 'Results saved!'
  ));
}
else {
  echo json_encode(array(
    'status' => 'error',
    'msg' => 'Missing or incorrect data!'
  ));
}