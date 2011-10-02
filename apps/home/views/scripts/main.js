$(function () {
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
            if (zs.stack.size(app) === 0) {
               zs.stack.push(app);
            } else if (zs.stack.size(app) === 1 &&
                       $(this).attr("refresh") != "no") {
               zs.stack.refresh(app);
            } else {
               zs.stack.focus(app);
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
            zs.stack.push(app, action, data);
         }
      }
   });

   $("#logout").live('click',function (e) {
      e.preventDefault();
      $.ajax({"data" : {"app" : "auth",
                      "action" : "logout"},
              "success" : function (data) {
                  window.location = '/';
              }
      });
   });

});
