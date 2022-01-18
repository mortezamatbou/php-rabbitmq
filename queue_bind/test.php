<?php

$n = 'aaa';
$b = 'آa';

//echo ;
echo  mb_strlen($n, 'utf-8') . ' | ' . strlen($n) . "\n";
echo  mb_strlen($b, 'utf-8') != strlen($b) . "\n";