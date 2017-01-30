<?php

$config = array(
  // best kept outside www root
  'results_folder_path' => './results',

  // show a form for two ID variables instead of URL Parameter
  'ask_for_id' => false,

  // show a button to close the window at the end
  'showCloseButton' => true,

  // shall the results be visible for the participant?
  'showResults' => false,

  // default language. Currently, 'en_US'/'de_DE'/'nl_NL' are supported
  'defaultLang' => 'de_DE',

  // array with valid IDs. Keep empty to allow all.
  'valid_ids' => array()
);

?>
