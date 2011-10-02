$(function () {
   $("#login-form").live('submit', function () {
      $.ajax({"data" : {"app" : "auth",
                        "username" : $("#username").val(),
                        "password" : zs.crypt.hash($("#password").val())},
              "success" : function (data) {
                  if (data.status === "success") {
                     if (typeof data.app === "undefined") {
                        data.app = "welcome";
                     }
                     zs.stack.push(data.app);
                     $.ajax({"data" : {"app" : "menu"},
                             "dataType" : "html",
                             "success" : function (data) {
                                 $("#sidenav").html(data);
                                 $(".item[href^='/" + zs.stack.activeName() + "']").addClass("active");
                             }
                     });
                  } else {
                     $("#login-error").hide()
                        .html("Error: wrong username or password").fadeIn();
                  }
              }
      });
      return false;
   });
});
