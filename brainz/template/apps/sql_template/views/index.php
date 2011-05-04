<div class="modal content-modal" id="<SLUG>-modal">
   <div id="<SLUG>-ajax" class="awesome basic-ajax"></div>
</div>
<a href="#" id="<SLUG>-new">New +</a>
<table>
   <tr>
<HTML_FIELDS_TH>
      <th></th>
      <th></th>
   </tr>
   <?php foreach ($<SLUG> as $row): ?>
   <tr>
<HTML_FIELDS_TD>
      <td><a class="<SLUG>-edit" href="#" <SLUG>_id="<?= $row['id'] ?>">edit</a></td>
      <td><a class="<SLUG>-delete" href="#" <SLUG>_id="<?= $row['id'] ?>">delete</a></td>
   </tr>
   <?php endforeach ?>
</table>
<script type="text/javascript">
$(document).ready(function() {
   $(".<SLUG>-edit").click(function(e) {
      e.preventDefault();
      $("#<SLUG>-modal").fadeIn();
      $.ajax({"data":{"app":"<SLUG>",
                      "action":"edit",
                      "id":$(this).attr("<SLUG>_id")},
              "dataType":"html",
              "success":function(data) {
                  $("#<SLUG>-ajax").html(data);
              }
      });
   });

   $(".<SLUG>-delete").click(function(e) {
      e.preventDefault();
      $row = $(this).parents("tr");
      $.ajax({"data":{"app":"<SLUG>",
                      "action":"delete",
                      "id":$(this).attr("<SLUG>_id")},
              "success":function(data) {
                  $row.remove();
              }
      });
   });

   $("#<SLUG>-new").click(function(e) {
      e.preventDefault();
      $("#<SLUG>-modal").fadeIn();
      $.ajax({"data":{"app":"<SLUG>",
                      "action":"new"},
              "dataType":"html",
              "success":function(data) {
                  $("#<SLUG>-ajax").html(data);
              }
      });
   });

   $(".<SLUG>-create").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.verify_form($form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"<SLUG>",
<AJAX_COMMA_SEP_FIELDS>
                      "action":"create"},
              "success":function(data) {
                  $("#<SLUG>-modal").fadeOut();
                  undead.loadApp("<SLUG>", 0);
              }
      });
   });

   $(".<SLUG>-update").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.verify_form($form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"<SLUG>",
<AJAX_COMMA_SEP_FIELDS_WID>
                      "action":"update"},
              "success":function(data) {
                  $("#<SLUG>-modal").fadeOut();
                  undead.loadApp("<SLUG>", 0);
              }
      });
   });

   $("#close-<SLUG>").die('click').live('click', function() {
      $("#<SLUG>-modal").fadeOut();
      $("#<SLUG>-edit").fadeOut(function() {
         $("#<SLUG>-edit").remove();
      });
   });
});
</script>
