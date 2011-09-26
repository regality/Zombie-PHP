$(function() {
   $(".password-update").live('click', function() {
      var form = $(this).parents("div.form");
      if (!undead.ui.verifyForm(form)) {
         undead.ui.error("Some required fields are msising.");
         return;
      }
      if (form.find("input[name=new_password_a").val() !==
          form.find("input[name=new_password_b").val()) {
         undead.ui.error("Passwords do not match.");
         return;
      }
      var newPass = undead.crypt.hash(form.find("input[name=new_password_a]").val());
      var id = form.find("input[name=id]").val();
      $.ajax({"url":"app.php",
              "data":{"app":"users",
                      "id":id,
                      "new_password":newPass,
                      "action":"password_update"},
              "success":function(data) {
                  if (data.status === "success") {
                     undead.stack.pop("users");
                     undead.stack.refresh("users");
                  }
              }
      });
   });

});
