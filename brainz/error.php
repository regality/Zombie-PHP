<?php

function js_error($errno, $errstr, $errfile, $errline) {
   global $_ERRORS;
   if (!is_array($_ERRORS)) {
      $_ERRORS = array();
   }
   if (!(error_reporting() & $errno)) {
      return;
   }
   $e = array("errno" => $errno,
              "errstr" => $errstr,
              "errfile" => $errfile,
              "errline" => $errline);
   array_push($_ERRORS, $e);
}

function render_errors() {
   $errors = $GLOBALS['_ERRORS'];
   if (count($errors) > 0) {
      ?>
      <script type="text/javascript">
      $(document).ready(function() {
      <?php foreach ($errors as $error): ?>

         mesg = "<i><?= $error['errstr'] ?></i> in <?= $error['errfile'] ?>" +
                " on line <?= $error['errline'] ?>.";
         addError(<?= $error['errno'] ?>, mesg);
      <?php endforeach ?>
      });
      </script>
      <?php
   }
   $GLOBALS['_ERRORS'] = array();
}

set_error_handler("js_error");

?>
