<a href="/<SLUG>/new" id="<SLUG>-new">New +</a>
<table>
   <tr>
<HTML_FIELDS_TH>
      <th></th>
      <th></th>
   </tr>
   <?php foreach ($<TABLE_NAME> as $row): ?>
   <tr>
<HTML_FIELDS_TD>
      <td><a href="/<SLUG>/edit?id=<?= $row['id'] ?>">edit</a></td>
      <td><a class="<SLUG>-delete" href="#" <TABLE_NAME>_id="<?= $row['id'] ?>">delete</a></td>
   </tr>
   <?php endforeach ?>
</table>
<script type="text/javascript">
undead.util.require("<SLUG>/main");
</script>
