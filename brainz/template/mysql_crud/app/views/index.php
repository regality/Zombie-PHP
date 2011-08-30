<a href="/<SLUG>/new" id="<SLUG>-new">New +</a>
<table>
   <tr>
<HTML_FIELDS_TH>
      <th></th>
      <th></th>
   </tr>
   <?php foreach ($<SLUG> as $row): ?>
   <tr>
<HTML_FIELDS_TD>
      <td><a href="/<SLUG>/edit?id=<?= $row['id'] ?>">edit</a></td>
      <td><a class="<SLUG>-delete" href="#" <SLUG>_id="<?= $row['id'] ?>">delete</a></td>
   </tr>
   <?php endforeach ?>
</table>
<script type="text/javascript">
$(document).ready(function() {
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

   $(".<SLUG>-create").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.ui.verifyForm($form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"<SLUG>",
<AJAX_COMMA_SEP_FIELDS>
                      "action":"create"},
              "success":function(data) {
                  undead.stack.push("<SLUG>");
                  undead.stack.refresh("<SLUG>");
              }
      });
   });

   $(".<SLUG>-update").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.ui.verifyForm($form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"<SLUG>",
<AJAX_COMMA_SEP_FIELDS_WID>
                      "action":"update"},
              "success":function(data) {
                  undead.stack.pop("<SLUG>");
                  undead.stack.refresh("<SLUG>");
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
