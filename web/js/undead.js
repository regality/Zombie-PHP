var undead = {};

undead.debug = false;
undead.short = false;

undead.addMessage = function(title, mesg) {
   html = "<div class=\"console-title\">" + title + "</div>" + mesg;
   undead.consoleAdd(html);
}

undead.addError = function(level, mesg) {
   var levels = {0:"Javascript Error",
                 2:"PHP Warning",
                 8:"PHP Notice",
                 512:"PHP User Warning",
                 1024:"PHP User Notice",
                 2048:"PHP Strict"};
   title = levels[level];
   html = "<div class=\"console-error\">" + title + "</div>" + mesg;
   $("div[app=console]").css({"color":"red","font-weight":"bold"}).click(function() {
      $(this).css({"color":"black","font-weight":"normal"});
   });
   undead.consoleAdd(html);
}

undead.consoleAdd = function(html) {
   html = "<div class=\"console-mesg\">" +
          "<div class=\"console-mesg-close\">X</div>" + html + "</div>";
   $("#console-messages").append(html);
   $(".console-mesg:first").css({"border-top-width":"1px"});
   $(".console-mesg:first").css({"padding":"0px 2px"});
}

undead.resetMenu = function() {
   $(".item").removeClass("last next prev");
   $(".item").last().addClass("last");
   $(".active").next().addClass("next");
   $(".active").prev().addClass("prev");
}

undead.tokens = Array();

undead.addToken = function(token) {
   undead.tokens.push(token);
   if (undead.tokens.length > 10) {
      undead.tokens.splice(0, undead.tokens.length - 10);
   }
}

undead.getToken = function() {
   if (undead.tokens.length == 0) {
      undead.requestToken();
   }
   token = undead.tokens[0];
   undead.tokens.splice(0, 1);
   return token;
}

undead.requestToken = function() {
   $.ajax({"data":{"app":"csrf"},
           "async":false,
           "success":function(data) {
               undead.addToken(data.token);
           }
   });
}

undead.loadApp = function(app, cache, target, data) {
   if (target == null || target.length == 0) {
      target = "#content";
   } else {
      target = "#" + target;
   }
   if (data == null || typeof data != "object") {
      data = {"app":app};
   } else {
      data.app = app;
   }
   app_div = $("#app-" + app);
   if (app_div.length == 0 || cache == 0) {
      if (cache == 0 && app_div.length == 1) {
         app_div.remove();
      }
      $.ajax({"data":data,
              "dataType":"html",
              success:function(data) {
                  while($("#app-" + app).length > 0) {
                     $("#app-" + app).remove();
                  }
                  div = "<div id=\"app-" + app + "\">" + data + "</div>";
                  $("#content").append(div);
                  $("#app-" + app).addClass("app-content").hide();
                  current = $(".app-content:visible");
                  if (current.length == 0) {
                     $("#app-" + app).show();
                  } else {
                     current.fadeOut("fast", function() {
                        $("#app-" + app).fadeIn("fast");
                     });
                  }
                  undead.addMessage("App Loaded", "The app <i>" + app + "</i> was successfully loaded");
              }
      });
   } else {
      $(".app-content:visible").fadeOut("fast", function() {
         app_div.fadeIn("fast");
      });
   }
}

undead.verify_form = function(form) {
   form_done = true;
   form.find("input.required, textarea.required, select.required").each(function() {
      if ($(this).val() == "") {
         form_done = false;
         $(this).css({"background":"#fdd"});
      } else {
         $(this).css({"background":"#fff"});
      }
   });
   return form_done;
}

undead.setupAjax = function() {
   $.ajaxSetup({
      "dataType":"json",
      "cache":"false",
      "error":function(xhr, status, error) {
         alert('An error occured:' + error + status);
      },
      "dataFilter":function(rawData, type) {
         if (undead.debug) {
            alert('raw data:' + rawData);
         }
         if (type == "json") {
            try {
               data = $.parseJSON(rawData);
               if (data.status == "logged out") {
                  window.location.reload();
               }
               if (data.query != null) {
                  alert(data.query);
               }
            } catch (e) {
               alert('error parsing json:' + rawData);
            }
         } else {
            if (rawData == "logged out") {
               window.location.reload();
            }
         }
         return rawData;
      },
      "timeout":10000,
      "type":"get"
   });

   $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
      if (options.data != null &&
          options.data.search("app=csrf") == -1 &&
          options.data.search("csrf=") == -1) {
         csrf = "csrf=" + undead.getToken();
         if (options.data.length > 0) {
            csrf = "&" + csrf;
         }
         options.data += csrf;
      }
      if (options.data != null) {
         options.data += "&format=" + options.dataType;
      } else {
         options.data = "format=" + options.dataType;
      }
      if (undead.debug) {
         alert("ajax data:" + options.data);
      }
   });
}

if (undead.short == true) {
   u = undead;
}
