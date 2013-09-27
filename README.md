NASA-TLX
========

Web implementation of the NASA-TLX survey, based on [Keith Vertanen's one-page HTML/JS version](http://www.keithv.com/software/nasatlx/).

I should say it's a quick-and-dirty implementation. I just added Bootstrap and jQuery and wrote a PHP script to process the results in csv files. Participant id's and the path of the results folder can be configured in config.php. Id's should be supplied in URL, eg: index.php?id=participant. Make sure the results folder is writable.

Dutch version in index-nl.php.
