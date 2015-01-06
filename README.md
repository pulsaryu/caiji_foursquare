caiji_foursquare
================

采集 foursquare 的位置数据。

#Usage

##Config
Copy or rename config.php.simple to config.php and edit;

##run
```
$ php caiji.php > out.csv
```

##Custom output
Edit caiji.php
```
$main->fire(function($venue) {
    echo $venue['name'];
});
```
