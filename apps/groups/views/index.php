<a href="/groups/new" id="groups-new">New +</a>
<table>
   <tr>
      <th>Name</th>
      <th></th>
      <th></th>
   </tr>
   <?php foreach ($groups as $row): ?>
   <tr>
      <td><?= $row['name'] ?></td>
      <td><a href="/groups/edit?id=<?=$row['id']?>">edit</a></td>
      <td><a class="groups-delete" href="#" groups_id="<?= $row['id'] ?>">delete</a></td>
   </tr>
   <?php endforeach ?>
</table>
<script type="text/javascript">
zs.util.require('groups/main');
</script>
