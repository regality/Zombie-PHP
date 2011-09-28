<?php

if (isset($_GET['app']) && isset($_GET['image']) && isset($_GET['type'])) {
   $app = preg_replace('/[^0-9a-zA-Z_]/', '', $_GET['app']);
   $image = preg_replace('/[^0-9a-zA-Z_-]/', '', $_GET['image']);
   $type = preg_replace('/[^a-z]/', '', $_GET['type']);

   $image_file = __DIR__ . "/../../apps/$app/views/images/$image.$type";
   if (file_exists($image_file)) {
      header("Content-type: image/$type");
      echo file_get_contents($image_file);
   } else {
      header("HTTP/1.1 404 Not Found");
      include(__DIR__ . "/../../apps/home/views/404.php");
   }
} else {
   header("HTTP/1.1 404 Not Found");
   include(__DIR__ . "/../../apps/home/views/404.php");
}

?>
