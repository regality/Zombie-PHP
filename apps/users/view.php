<div class="modal content-modal" id="users-modal">
   <div id="users-ajax" class="awesome basic-ajax"></div>
</div>
<a href="#" id="users-new">New +</a>
<table>
   <tr>
      <th>Username</th>
      <th>Firstname</th>
      <th>Lastname</th>
      <th>Groups</th>
      <th></th>
      <th></th>
   </tr>
   <?php foreach ($users as $row): ?>
   <tr>
      <td><?= $row['username'] ?></td>
      <td><?= $row['firstname'] ?></td>
      <td><?= $row['lastname'] ?></td>
      <td><?= implode(',', unserialize($row['groups'])); ?></td>
      <td><a class="users-edit" href="#" users_id="<?= $row['id'] ?>">edit</a></td>
      <td><a class="users-delete" href="#" users_id="<?= $row['id'] ?>">delete</a></td>
   </tr>
   <?php endforeach ?>
</table>
<script type="text/javascript">

$(document).ready(function() {
   $(".users-edit").click(function(e) {
      e.preventDefault();
      $("#users-modal").fadeIn();
      $.ajax({"data":{"app":"users",
                      "action":"edit",
                      "id":$(this).attr("users_id")},
              "dataType":"html",
              "success":function(data) {
                  $("#users-ajax").html(data);
              }
      });
   });

   $(".users-delete").click(function(e) {
      e.preventDefault();
      $row = $(this).parents("tr");
      $.ajax({"data":{"app":"users",
                      "action":"delete",
                      "id":$(this).attr("users_id")},
              "success":function(data) {
                  $row.remove();
              }
      });
   });

   $("#users-new").click(function(e) {
      e.preventDefault();
      $("#users-modal").fadeIn();
      $.ajax({"data":{"app":"users",
                      "action":"new"},
              "dataType":"html",
              "success":function(data) {
                  $("#users-ajax").html(data);
              }
      });
   });

   $(".users-create").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!verify_form($form)) {
         alert("Some required fields are msising.");
         return;
      }
      groups = new Array();
      $.each($form.find("input[name='groups[]']:checked"), function() {
         groups.push($(this).val());
      });
      if (groups.length == 0) {
         alert("You must select at least one group.");
         return
      }
      pw1 = $form.find("input[name=password1]").val();
      pw2 = $form.find("input[name=password2]").val();
      if (pw1 != pw2) {
         alert('Passwords do not match');
         retun;
      }
      hex_pass = hex_sha1(pw1);
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":$form.find("input[name=id]").val(),
                      "username":$form.find("input[name=username]").val(),
                      "firstname":$form.find("input[name=firstname]").val(),
                      "lastname":$form.find("input[name=lastname]").val(),
                      "password":hex_pass,
                      "groups":groups,
                      "action":"create"},
              "success":function(data) {
                  $("#users-modal").fadeOut();
                  loadApp("users", 0);
              }
      });
   });

   $(".users-update").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!verify_form($form)) {
         alert("Some required fields are msising.");
         return;
      }
      groups = new Array();
      $.each($form.find("input[name='groups[]']:checked"), function() {
         groups.push($(this).val());
      });
      if (groups.length == 0) {
         alert("You must select at least one group.");
         return
      }
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":$form.find("input[name=id]").val(),
                      "username":$form.find("input[name=username]").val(),
                      "firstname":$form.find("input[name=firstname]").val(),
                      "lastname":$form.find("input[name=lastname]").val(),
                      "groups":groups,
                      "action":"update"},
              "success":function(data) {
                  $("#users-modal").fadeOut();
                  loadApp("users", 0);
              }
      });
   });

   $("#close-users").die('click').live('click', function() {
      $("#users-modal").fadeOut();
      $("#users-edit").fadeOut(function() {
         $("#users-edit").remove();
      });
   });
});
</script>
