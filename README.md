NASA-TLX
========

Web implementation of the NASA-TLX survey, based on [Keith Vertanen's one-page HTML/JS version](http://www.keithv.com/software/nasatlx/).

The implementation polished the UI with jQuery and Bootstrap, and writes the results into a CSV file. 
Multiple languages supported (English, German and Durch for now). PR are welcome to add more. 

Language and Condition can be submitted by URL, e.g. `http://foo.bar/index.php?lang=de&id=A`

Default, all submitted IDs are accepted. If you want to allow only specific values, add them to the `valid_ids` array.

Configuration options:
```
$config = array(
  // best kept outside www root
  'results_folder_path' => './results',

  // show a form for two ID variables instead of URL Parameter
  'ask_for_id' => false,

  // show a button to close the window at the end
  'showCloseButton' => true,

  // shall the results be visible for the participant?
  'showResults' => false,

  // default language. Currently, 'en'/'de'/'nl' are supported
  'defaultLang' => 'de',

  // array with valid IDs. Keep empty to allow all.
  'valid_ids' => array()
);
```
