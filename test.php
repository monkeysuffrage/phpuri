<?php

require('../rel2abs.php');
require('../url_to_absolute.php');
require('phpuri.php');

$tests=array(
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => 'g:h', 'result' => 'g:h'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => 'g',   'result' => 'http://a/b/c/g'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => './g', 'result' => 'http://a/b/c/g'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => 'g/',  'result' => 'http://a/b/c/g/'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => '/g',  'result' => 'http://a/g'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => '//g', 'result' => 'http://g'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => 'g?y', 'result' => 'http://a/b/c/g?y'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => '#s', 'result' => 'http://a/b/c/d;p?q#s'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => 'g#s', 'result' => 'http://a/b/c/g#s'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => 'g?y#s', 'result' => 'http://a/b/c/g?y#s'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => ';x', 'result' => 'http://a/b/c/;x'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => 'g;x', 'result' => 'http://a/b/c/g;x'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => 'g;x?y#s', 'result' => 'http://a/b/c/g;x?y#s'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => '.', 'result' => 'http://a/b/c/'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => './', 'result' => 'http://a/b/c/'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => '..', 'result' => 'http://a/b/'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => '../', 'result' => 'http://a/b/'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => '../g', 'result' => 'http://a/b/g'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => '../..', 'result' => 'http://a/'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => '../../', 'result' => 'http://a/'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'../../g','result' =>'http://a/g'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g.','result' =>'http://a/b/c/g.'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'.g','result' =>'http://a/b/c/.g'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g..','result' =>'http://a/b/c/g..'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'..g','result' =>'http://a/b/c/..g'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'./../g','result' =>'http://a/b/g'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'./g/.','result' =>'http://a/b/c/g/'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g/./h','result' =>'http://a/b/c/g/h'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g/../h','result' =>'http://a/b/c/h'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g;x=1/./y','result' =>'http://a/b/c/g;x=1/y'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g;x=1/../y','result' =>'http://a/b/c/y'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g?y/./x','result' =>'http://a/b/c/g?y/./x'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g?y/../x','result' =>'http://a/b/c/g?y/../x'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g#s/./x','result' =>'http://a/b/c/g#s/./x'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' =>'g#s/../x','result' =>'http://a/b/c/g#s/../x'),
  array( 'base' => 'http://a/b/c/d;p?q', 'rel' => 'ө/',  'result' => 'http://a/b/c/ө/'),
  array( 'base' => 'http://a/b/c/.', 'rel' => 'g/',  'result' => 'http://a/b/c/g/'),
  array( 'base' => 'http://a/b/c/..', 'rel' => 'g/',  'result' => 'http://a/b/g/'),
);

# rel2abs
$start = microtime();
list($successes, $failures) = array(0,0);
foreach($tests as $test){
  if(($r = rel2abs($test['rel'], $test['base'])) == $test['result']){
    $successes++;
  } else {
    $failures++;
  }
}

$elapsed = microtime() - $start;
echo "rel2abs:         successes -> $successes, failures => $failures, elapsed time: $elapsed\n";

# url_to_absolute
$start = microtime();
list($successes, $failures) = array(0,0);
foreach($tests as $test){
  if(($r = url_to_absolute($test['base'], $test['rel'])) == $test['result']){
    $successes++;
  } else {
    $failures++;
  }
}

$elapsed = microtime() - $start;
echo "url_to_absolute: successes -> $successes, failures => $failures, elapsed time: $elapsed\n";

# phpuri
$start = microtime();
list($successes, $failures) = array(0,0);
foreach($tests as $test){
  $base = phpUri::parse($test['base']);
  if(($r = $base->join($test['rel'])) == $test['result']){
    $successes++;
  } else {
    $failures++;
    echo "failure: $r instead of " . $test['result'] . " \n";
  }
}
$elapsed = microtime() - $start;
echo "phpuri:          successes -> $successes, failures => $failures, elapsed time: $elapsed\n";
?>