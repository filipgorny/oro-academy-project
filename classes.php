<?php

require "./vendor/autoload.php";

foreach (get_declared_classes() as $class) {
echo $class."\n";
}