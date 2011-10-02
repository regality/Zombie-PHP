$(function() {
   $(".password-update").live('click', function() {
      var form = $(this).parents("div.form");
      if (!zs.ui.verifyForm(form)) {
         return false;
      }
      if (form.find("input[name=new_password_a]").val() !==
          form.find("input[name=new_password_b]").val()) {
         zs.ui.error("Passwords do not match.");
         return false;
      }
      var newPass = zs.crypt.hash(form.find("input[name=new_password_a]").val());
      var id = form.find("input[name=id]").val();
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":id,
                      "new_password":newPass,
                      "action":"password_update"},
              "success":function(data) {
                  if (data.status === "success") {
                     zs.stack.pop("users");
                     zs.stack.refresh("users");
                  }
              }
      });
      return false;
   });

});
