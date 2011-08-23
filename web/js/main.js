$(document).ready(function() {
   setAjaxUrl();
   undead.setupAjax();
   undead.loadDefaultApp();

   $("#console-clear").click(function() {
      $("#console-messages").html("");
   });

   $(".console-mesg-close").live('click', function() {
      $(this).parent().remove();
   });

   $(".item").live('click', function() {
      if (!$(this).hasClass("active")) {
         $(".item").removeClass("active");
         $(this).addClass("active");
      }
   });

   $("a").live('click', function(e) {
      var href = $(this).attr("href");
      var re = href.match(/^\/?#\/([a-z_]+)\/?([a-z_]+)?$/);
      if (re != null) {
         e.preventDefault();
         var app = re[1];
         if (re[2] == null) {
            if (undead.stackSize(app) == 0) {
               undead.pushStack(app);
            } else {
               undead.focusApp(app);
            }
         } else {
            var action = re[2];
            undead.pushStack(app, action);
         }
      }
   });

   $("#logout").unbind('click').live('click',function() {
      $.ajax({"data":{"app":"auth",
                      "logout":""},
              "success":function(data) {
                  window.location.reload();
              }
      });
   });

   $("#login-form").live('submit', function(e) {
      $.ajax({"data":{"app":"auth",
                      "username":$("#username").val(),
                      "password":hex_sha1($("#password").val())},
              "success":function(data) {
                  if (data.status == "success") {
                     undead.pushStack(data.app);
                     $.ajax({"data":{"app":"menu"},
                             "dataType":"html",
                             "success":function(data) {
                                 $("#sidenav").html(data);
                                 undead.resetMenu();
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
