$(document).ready(function () {
   $("#console-clear").click(function () {
      $("#console-messages").html("");
   });

   $(".console-mesg-close").live('click', function () {
      $(this).parent().remove();
   });

   $(".item").live('click', function () {
      if (!$(this).hasClass("active")) {
         $(".item").removeClass("active");
         $(this).addClass("active");
      }
   });

   $("a").live('click', function (e) {
      var href, re, app, action, data, i, pairs, pair;
      href = $(this).attr("href");
      re = href.match(/^\/([a-z_]+)\/?([a-z_]+)?\??(.*)$/);
      if (re !== null) {
         e.preventDefault();
         app = re[1];
         if (typeof re[2] === "undefined") {
            if (undead.stack.size(app) === 0) {
               undead.stack.push(app);
            } else {
               undead.stack.focus(app);
            }
         } else {
            action = re[2];
            data = {};
            if (typeof re[3] !== "undefined") {
               pairs = re[3].split('&');
               for (i = 0; i < pairs.length; i += 1) {
                  pair = pairs[i].split('=');
                  if (typeof pair[1] === "undefined") {
                     data[pair[0]] = '';
                  } else {
                     data[pair[0]] = pair[1];
                  }
               }
            }
            undead.stack.push(app, action, data);
         }
      }
   });

   $("#logout").unbind('click').live('click',function (e) {
      e.preventDefault();
      $.ajax({"data" : {"app" : "auth",
                      "action" : "logout"},
              "success" : function (data) {
                  window.location = '/';
              }
      });
   });

   $("#login-form").live('submit', function () {
      $.ajax({"data" : {"app" : "auth",
                      "username" : $("#username").val(),
                      "password" : undead.crypt.hash($("#password").val())},
              "success" : function (data) {
                  if (data.status === "success") {
                     undead.stack.push(data.app);
                     $.ajax({"data" : {"app" : "menu"},
                             "dataType" : "html",
                             "success" : function (data) {
                                 $("#sidenav").html(data);
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
