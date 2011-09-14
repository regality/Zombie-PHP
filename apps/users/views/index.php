<div class="modal content-modal" id="users-modal">
   <div id="users-ajax" class="awesome basic-ajax"></div>
</div>
<a href="/users/new" id="users-new">New +</a>
<table>
   <tr>
      <th>Username</th>
      <th>Firstname</th>
      <th>Lastname</th>
      <th></th>
      <th></th>
      <th></th>
   </tr>
   <?php foreach ($users as $row): ?>
   <tr>
      <td><?= $row['username'] ?></td>
      <td><?= $row['firstname'] ?></td>
      <td><?= $row['lastname'] ?></td>
      <td><a href="/users/edit?id=<?= $row['id'] ?>">edit</a></td>
      <td><a href="/users/password?id=<?= $row['id'] ?>">change password</a></td>
      <td><a class="users-delete" href="#" users_id="<?= $row['id'] ?>">delete</a></td>
   </tr>
   <?php endforeach ?>
</table>
<script type="text/javascript">

$(document).ready(function() {
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

   $(".users-create").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.ui.verifyForm($form)) {
         undead.ui.error("Some required fields are msising.");
         return;
      }
      groups = new Array();
      $.each($form.find("input[name='groups[]']:checked"), function() {
         groups.push($(this).val());
      });
      if (groups.length == 0) {
         undead.ui.error("You must select at least one group.");
         return
      }
      pw1 = $form.find("input[name=password1]").val();
      pw2 = $form.find("input[name=password2]").val();
      if (pw1 != pw2) {
         undead.ui.error('Passwords do not match');
         retun;
      }
      hex_pass = undead.crypt.hash(pw1);
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
                  undead.stack.pop("users");
                  undead.stack.refresh("users");
              }
      });
   });

   $(".users-update").die('click').live('click', function() {
      $form = $(this).parents("div.form");
      if (!undead.ui.verifyForm($form)) {
         undead.ui.error("Some required fields are msising.");
         return;
      }
      groups = new Array();
      $.each($form.find("input[name='groups[]']:checked"), function() {
         groups.push($(this).val());
      });
      if (groups.length == 0) {
         undead.ui.error("You must select at least one group.");
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
                  undead.stack.pop("users");
                  undead.stack.refresh("users");
              }
      });
   });

});
</script>
