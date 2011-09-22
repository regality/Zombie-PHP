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
$(document).ready(function() {
   $(".groups-delete").click(function(e) {
      e.preventDefault();
      var row = $(this).parents("tr");
      $.ajax({"data":{"app":"groups",
                      "action":"delete",
                      "id":$(this).attr("groups_id")},
              "success":function(data) {
                  if (data.status == "success") {
                     row.remove();
                  } else {
                     undead.ui.error("Could not delete.");
                  }
              }
      });
   });

   $(".groups-create").die('click').live('click', function() {
      var form = $(this).parents("div.form");
      if (!undead.ui.verifyForm(form)) {
         undead.ui.error("Some required fields are msising.");
         return false;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"groups",
                      "name":form.find("input[name=name]").val(),
                      "action":"create"},
              "success":function(data) {
                  if (data.status == "success") {
                     undead.stack.pop("groups");
                     undead.stack.refresh("groups");
                  } else {
                     undead.ui.error("Could not create.");
                  }
              }
      });
      return false;
   });

   $(".groups-update").die('click').live('click', function() {
      var form = $(this).parents("div.form");
      if (!undead.ui.verifyForm(form)) {
         undead.ui.error("Some required fields are msising.");
         return false;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"groups",
                      "id":form.find("input[name=id]").val(),
                      "name":form.find("input[name=name]").val(),
                      "action":"update"},
              "success":function(data) {
                  if (data.status == "success") {
                     undead.stack.pop("groups");
                     undead.stack.refresh("groups");
                  } else {
                     undead.ui.error("Could not update.");
                  }
              }
      });
      return false;
   });
});
</script>
