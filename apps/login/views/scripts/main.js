$(function () {
   $("#login-form").live('submit', function () {
      $.ajax({"data" : {"app" : "auth",
                        "username" : $("#username").val(),
                        "password" : undead.crypt.hash($("#password").val())},
              "success" : function (data) {
                  if (data.status === "success") {
                     if (typeof data.app === "undefined") {
                        data.app = "welcome";
                     }
                     undead.stack.push(data.app);
                     $.ajax({"data" : {"app" : "menu"},
                             "dataType" : "html",
                             "success" : function (data) {
                                 $("#sidenav").html(data);
                                 $(".item[href^='/" + undead.stack.activeName() + "']").addClass("active");
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
