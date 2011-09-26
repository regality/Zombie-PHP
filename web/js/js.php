<?php

header("Content-type: text/javascript");
if (isset($_GET['app']) && isset($_GET['js'])) {
   $app = preg_replace('/[^0-9a-zA-Z_]/', '', $_GET['app']);
   $js = preg_replace('/[^0-9a-zA-Z_\/]/', '', $_GET['js']);
   $js_file = __DIR__ . "/../../apps/" . $app . "/views/scripts/" . $js . ".js";
   if (file_exists($js_file)) {
      include($js_file);
   } else {
      header("HTTP/1.1 404 Not Found");
   }
} else {
   header("HTTP/1.1 404 Not Found");
}

?>
