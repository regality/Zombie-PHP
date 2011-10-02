$(function() {
   $(".groups-delete").live('click', function(e) {
      e.preventDefault();
      var row = $(this).parents("tr");
      $.ajax({"data":{"app":"groups",
                      "action":"delete",
                      "id":$(this).attr("groups_id")},
              "success":function(data) {
                  if (data.status == "success") {
                     row.remove();
                  } else {
                     zs.ui.error("Could not delete.");
                  }
              }
      });
   });

   $(".groups-create").live('click', function() {
      var form = $(this).parents("form");
      if (!zs.ui.verifyForm(form)) {
         return false;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"groups",
                      "name":form.find("input[name=name]").val(),
                      "action":"create"},
              "success":function(data) {
                  if (data.status == "success") {
                     zs.stack.pop("groups");
                     zs.stack.refresh("groups");
                  } else {
                     zs.ui.error("Could not create.");
                  }
              }
      });
      return false;
   });

   $(".groups-update").live('click', function() {
      var form = $(this).parents("form");
      if (!zs.ui.verifyForm(form)) {
         return false;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"groups",
                      "id":form.find("input[name=id]").val(),
                      "name":form.find("input[name=name]").val(),
                      "action":"update"},
              "success":function(data) {
                  if (data.status == "success") {
                     zs.stack.pop("groups");
                     zs.stack.refresh("groups");
                  } else {
                     zs.ui.error("Could not update.");
                  }
              }
      });
      return false;
   });
});
