NASA-TLX
========

Web implementation of the NASA-TLX survey, based on [Keith Vertanen's one-page HTML/JS version](http://www.keithv.com/software/nasatlx/).

I should say it's a quick-and-dirty implementation. I just added Bootstrap and jQuery and wrote a PHP script to process the results in csv files. 


Set the results folder:
```
$results_folder_path = 'results'; // best kept outside www root
```

Particpiants can be preconfigured, and then selected by GET parameter (e.g. `index.php?id=laurens`):
```
$dynamic_participants = false;
$participants = array(
  'laurens', '...'
);
```

Particpiants can be set on the fly, and then selected by two form fields in the questionnaire:
```
$dynamic_participants = true;
```

Dutch version in `index-nl.php`.
German version in `index-de.php`.
