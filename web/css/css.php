<?php

require_once(__DIR__ . '/../../zombie-core/util/dir.php');
require_once(__DIR__ . '/../../zombie-core/util/compile/compile.php');

header("Content-type: text/css");
if (!isset($_GET['name'])) {
   header("Status: 404 Not Found");
   exit();
} else {
   $name = $_GET['name'];
}

$file_lists = getCssFileLists();
if (!isset($file_lists[$name])) {
   header("Status: 404 Not Found");
   exit();
} else {
   $output = compileCssList($file_lists[$name], isset($_REQUEST['min']));
   echo $output;
}

?>
