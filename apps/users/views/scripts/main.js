$(function() {
   $(".users-delete").live('click', function(e) {
      e.preventDefault();
      var row = $(this).parents("tr");
      $.ajax({"data":{"app":"users",
                      "action":"delete",
                      "id":$(this).attr("users_id")},
              "success":function(data) {
                 if (data.status == "success") {
                    row.remove();
                 } else {
                    zs.ui.error("Could not delete user.");
                 }
              }
      });
   });

   $(".users-create").live('click', function() {
      var form = $(this).parents("div.form");
      if (!zs.ui.verifyForm(form)) {
         return false;
      }
      var groups = [];
      $.each(form.find("input[name='groups[]']:checked"), function() {
         groups.push($(this).val());
      });
      var fg = form.find("input[name='groups[]']:first");
      if (groups.length == 0) {
         zs.ui.showFieldError(fg, "count", "You must select at least one group.");
         return false;
      } else {
         zs.ui.hideFieldErrors(fg);
      }
      var pwf1 = form.find("input[name=password1]");
      var pw1 = pwf1.val();
      var pw2 = form.find("input[name=password2]").val();
      if (pw1 != pw2) {
         zs.ui.showFieldError(pwf1, "match", "Passwords do not match.");
         return false;
      }
      var hex_pass = zs.crypt.hash(pw1);
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":form.find("input[name=id]").val(),
                      "username":form.find("input[name=username]").val(),
                      "firstname":form.find("input[name=firstname]").val(),
                      "lastname":form.find("input[name=lastname]").val(),
                      "password":hex_pass,
                      "groups":groups,
                      "action":"create"},
              "success":function(data) {
                 if (data.status == "success") {
                    zs.stack.pop("users");
                    zs.stack.refresh("users");
                 } else if (data.reason == "username taken") {
                    zs.ui.showFieldError(form.find("input[name=username]"), "exists", "Username already exists");
                 } else {
                    zs.ui.error("Could not create user.");
                 }
              }
      });
      return false;
   });

   $(".users-update").live('click', function() {
      var form = $(this).parents("div.form");
      if (!zs.ui.verifyForm(form)) {
         return false;
      }
      var groups = [];
      $.each(form.find("input[name='groups[]']:checked"), function() {
         groups.push($(this).val());
      });
      if (groups.length == 0) {
         zs.ui.error("You must select at least one group.");
         return false;
      }
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":form.find("input[name=id]").val(),
                      "username":form.find("input[name=username]").val(),
                      "firstname":form.find("input[name=firstname]").val(),
                      "lastname":form.find("input[name=lastname]").val(),
                      "groups":groups,
                      "action":"update"},
              "success":function(data) {
                 if (data.status == "success") {
                    zs.stack.pop("users");
                    zs.stack.refresh("users");
                 } else {
                    zs.ui.error("Could not update user.");
                 }
              }
      });
      return false;
   });

});
