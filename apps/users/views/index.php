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
undead.util.require("users/main");
</script>
