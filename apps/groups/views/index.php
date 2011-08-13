<div class="modal content-modal" id="groups-modal">
   <div id="groups-ajax" class="awesome basic-ajax"></div>
</div>
<a href="#" id="groups-new">New +</a>
<table>
   <tr>
      <th>Name</th>
      <th></th>
      <th></th>
   </tr>
   <?php foreach ($groups as $row): ?>
   <tr>
      <td><?= $row['name'] ?></td>
      <td><a class="groups-edit" href="#" groups_id="<?= $row['id'] ?>">edit</a></td>
      <td><a class="groups-delete" href="#" groups_id="<?= $row['id'] ?>">delete</a></td>
   </tr>
   <?php endforeach ?>
</table>
<script type="text/javascript">
$(document).ready(function() {
   $(".groups-edit").click(function(e) {
      e.preventDefault();
      undead.pushStack("groups", "edit", {"id":$(this).attr("groups_id")});
   });

   $(".groups-delete").click(function(e) {
      e.preventDefault();
      $row = $(this).parents("tr");
      $.ajax({"data":{"app":"groups",
                      "action":"delete",
                      "id":$(this).attr("groups_id")},
              "success":function(data) {
                  $row.remove();
              }
      });
   });

   $("#groups-new").click(function(e) {
      e.preventDefault();
      undead.pushStack("groups","new");
   });

   $(".groups-create").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.verify_form($form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"groups",
                      "name":$form.find("input[name=name]").val(),
                      "action":"create"},
              "success":function(data) {
                  undead.popStack("groups");
                  undead.refreshStack("groups");
              }
      });
   });

   $(".groups-update").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.verify_form($form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"groups",
                      "id":$form.find("input[name=id]").val(),
                      "name":$form.find("input[name=name]").val(),
                      "action":"update"},
              "success":function(data) {
                  undead.popStack("groups");
                  undead.refreshStack("groups");
              }
      });
   });

   $("#close-groups").die('click').live('click', function() {
      $("#groups-modal").fadeOut();
      $("#groups-edit").fadeOut(function() {
         $("#groups-edit").remove();
      });
   });
});
</script>
