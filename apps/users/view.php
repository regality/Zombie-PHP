<style type="text/css">
#users-ajax {
position: absolute;
top: 100px;
left: 150px;
width: 300px;
min-height: 150px;
padding: 5px;
}
</style>
<div class="users">
   <div class="modal content-modal" id="users-modal">
      <div id="users-ajax" class="awesome"></div>
   </div>
   <table cellpadding='8'>
      <tr>
         <th>Username</th>
         <th>Firstname</th>
         <th>Lastname</th>
         <th>Groups</th>
         <th></th>
         <th></th>
      </tr>
      <?php foreach ($users as $user): ?>
         <tr>
            <td><?= $user['username'] ?></td>
            <td><?= $user['firstname'] ?></td>
            <td><?= $user['lastname'] ?></td>
            <td><?= implode(",", unserialize($user['groups'])) ?></td>
            <td><a href="#" class="user-edit" uid="<?= $user['id'] ?>">Edit</a></td>
            <td><a href="#">Delete</a></td>
         </tr>
      <?php endforeach ?>
   </table>
</div>
<script type="text/javascript">
$(document).ready(function() {
   $(".user-edit").click(function(e) {
      e.preventDefault();
      $("#users-modal").fadeIn();
      $.ajax({"url":"/app.php",
              "data":{"app":"users",
                      "action":"edit",
                      "id":$(this).attr("uid")},
              "dataType":"html",
              "success":function(data) {
                  $("#users-ajax").html(data);
              }
      });
   });

   $("#close-users").live('click', function() {
      $("#users-modal").fadeOut();
      $("#users-edit").fadeOut(function() {
         $("#users-edit").remove();
      });
   });
});
</script>
