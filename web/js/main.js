function addMessage(title, mesg) {
   html = "<div class=\"console-title\">" + title + "</div>" + mesg;
   consoleAdd(html);
}

function addError(level, mesg) {
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
   consoleAdd(html);
}

function consoleAdd(html) {
   html = "<div class=\"console-mesg\">" +
          "<div class=\"console-mesg-close\">X</div>" + html + "</div>";
   $("#console-messages").append(html);
   $(".console-mesg:first").css({"border-top-width":"1px"});
   $(".console-mesg:first").css({"padding":"0px 2px"});
}

function resetMenu() {
   $(".item").removeClass("last next prev");
   $(".item").last().addClass("last");
   $(".active").next().addClass("next");
   $(".active").prev().addClass("prev");
}

window.tokens = Array();

function addToken(token) {
   window.tokens.push(token);
   if (window.tokens.length > 10) {
      window.tokens.splice(0, window.tokens.length - 10);
   }
}

function getToken() {
   if (window.tokens.length == 0) {
      requestToken();
   }
   token = window.tokens[0];
   window.tokens.splice(0, 1);
   return token;
}

function requestToken() {
   $.ajax({"data":{"app":"csrf"},
           "async":false,
           "success":function(data) {
               addToken(data.token);
           }
   });
}

function loadApp(app, cache) {
   app_div = $("#app-" + app);
   if (app_div.length == 0 || cache == 0) {
      if (cache == 0 && app_div.length == 1) {
         app_div.remove();
      }
      $.ajax({"data":{"app":app},
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
                  addMessage("App Loaded", "The app <i>" + app + "</i> was successfully loaded");
              }
      });
   } else {
      $(".app-content:visible").fadeOut("fast", function() {
         app_div.fadeIn("fast");
      });
   }
}

function verify_form(form) {
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

$(document).ready(function() {
   setAjaxUrl();
   resetMenu();
   loadApp($(".active").attr("app"), false);

   $.ajaxSetup({
      "dataType":"json",
      "cache":"false",
      "error":function(xhr, status, error) {
         alert('An error occured:' + error + status);
      },
      "dataFilter":function(rawData, type) {
         //alert('returned:' + rawData);
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
         csrf = "csrf=" + getToken();
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
      //alert(options.data);
   });

   $("#console-clear").click(function() {
      $("#console-messages").html("");
   });

   $(".console-mesg-close").live('click', function() {
      $(this).parent().remove();
   });

   $(".app").live('click', function() {
      if (!$(this).hasClass("active")) {
         $(".item").removeClass("active");
         $(".item").removeClass("prev");
         $(".item").removeClass("next");
         $(this).addClass("active");
         $(this).next().addClass("next");
         $(this).prev().addClass("prev");
         loadApp($(this).attr("app"), $(this).attr("cache"));
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
                     loadApp("welcome", false);
                     $.ajax({"data":{"app":"menu"},
                             "dataType":"html",
                             "success":function(data) {
                                 $("#sidenav").html(data);
                                 resetMenu();
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
