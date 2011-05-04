<?php

global $_ERRORS;
$_ERRORS = array();

function error_store($errno, $errstr, $errfile, $errline) {
   if (!(error_reporting() & $errno)) {
      return;
   }
   $e = array("errno" => $errno,
              "errstr" => $errstr,
              "errfile" => $errfile,
              "errline" => $errline);
   array_push($GLOBALS['_ERRORS'], $e);
}

function get_error_array() {
   return $GLOBALS['_ERRORS'];
}

function clear_errors() {
   $GLOBALS['_ERRORS'] = array();
}

function render_errors_js() {
   $errors = $GLOBALS['_ERRORS'];
   if (count($errors) > 0) {
      ?>
      <script type="text/javascript">
      $(document).ready(function() {
      <?php foreach ($errors as $error): ?>

         mesg = "<i><?= $error['errstr'] ?></i> in <?= $error['errfile'] ?>" +
                " on line <?= $error['errline'] ?>.";
         undead.addError(<?= $error['errno'] ?>, mesg);
      <?php endforeach ?>
      });
      </script>
      <?php
   }
   $GLOBALS['_ERRORS'] = array();
}

set_error_handler("error_store");

?>
