<?php
session_start();

require_once('config.php');

if (!isset($_GET['id']) OR strlen($_GET['id']) < 1)
  die(json_encode(array(
    'status' => 'error',
    'msg' => 'No ID submitted!'
  )));

if (count($config['valid_ids']) > 0 && !in_array($_GET['id'], $config['valid_ids']))
  die(json_encode(array(
    'status' => 'error',
    'msg' => 'Invalid participant!'
  )));

if (empty($_POST['token']) || $_POST['token']!==$_SESSION['token'])
  die(json_encode(array(
    'status' => 'error',
    'msg' => 'Invalid token!'
  )));

if (empty($_POST['data']))
  die(json_encode(array(
    'status' => 'error',
    'msg' => 'Missing or incorrect data!'
  )));

try
{
  $json = json_decode($_POST['data']);

  $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'no ip';
  $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'no user agent';

  $csv = array();
  $csv[] = array(date('c'), $_GET['id'], $ip, $useragent);
  for ($i=0;$i<=5;$i++)
    $csv[] = array($json->scale[$i], $json->results_rating[$i], $json->results_tally[$i], $json->results_weight[$i]);
  $csv[] = array($json->results_overall);

  $resultsfile = $config['results_folder_path'] . '/nasatlx_'.date('Y-m-d_H-i').'_'.$_GET['id'].'.csv';
  $fp = fopen($resultsfile, 'w+');
  if (!$fp)
    throw new Exception('Could not create file!');
  foreach ($csv as $line)
    fputcsv($fp, $line, "\t");
  fclose($fp);

  die(json_encode(array(
  'status' => 'success',
  'msg' => 'Results saved!'
  )));
}
catch (Exception $e) {
  die(json_encode(array(
    'status' => 'error',
    'msg' => 'Something went wrong when saving the results: ' . $e->getMessage()
  )));
}
