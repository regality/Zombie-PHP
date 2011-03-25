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
      $("#groups-modal").fadeIn();
      $.ajax({"data":{"app":"groups",
                      "action":"edit",
                      "id":$(this).attr("groups_id")},
              "dataType":"html",
              "success":function(data) {
                  $("#groups-ajax").html(data);
              }
      });
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
      $("#groups-modal").fadeIn();
      $.ajax({"data":{"app":"groups",
                      "action":"new"},
              "dataType":"html",
              "success":function(data) {
                  $("#groups-ajax").html(data);
              }
      });
   });

   $(".groups-create").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!verify_form($form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"groups",
                      "id":$form.find("input[name=id]").val(),
                      "name":$form.find("input[name=name]").val(),

                      "action":"create"},
              "success":function(data) {
                  $("#groups-modal").fadeOut();
                  loadApp("groups", 0);
              }
      });
   });

   $(".groups-update").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!verify_form($form)) {
         alert("Some required fields are msising.");
         return;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"groups",
                      "id":$form.find("input[name=id]").val(),
                      "name":$form.find("input[name=name]").val(),

                      "action":"update"},
              "success":function(data) {
                  $("#groups-modal").fadeOut();
                  loadApp("groups", 0);
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
