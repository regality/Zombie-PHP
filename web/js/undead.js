var undead = {};

undead.debug = false;
undead.defaultApp = "welcome";
undead.defaultAction = "index";
undead.token = "";
undead.firstState = true;
undead.ignoreHash = false;

undead.error = function(mesg) {
   alert(mesg);
}

undead.warn = function(mesg) {
   alert(mesg);
}

undead.message = function(mesg) {
   alert(mesg);
}

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

undead.setToken = function(token) {
   undead.token = token;
}

undead.getToken = function() {
   if (undead.token == "") {
      undead.requestToken();
   }
   return undead.token;
}

undead.requestToken = function() {
   $.ajax({"data":{"app":"csrf"},
           "async":false,
           "success":function(data) {
               undead.setToken(data.token);
           }
   });
}

undead.loadDefaultApp = function() {
   var re = window.location.hash.match(/([a-z_]+)\/?([a-z_]+)?/);
   if (re != null) {
      if (re[1] != null) {
         undead.defaultApp = re[1];
      }
      if (re[2] != null) {
         undead.defaultAtion = re[2];
      }
   }
   undead.pushStack(undead.defaultApp, undead.defaultAction);
}

undead.focusApp = function(appStack) {
   $(".app-stack:visible").hide().attr("active",null);
   $("#" + appStack + "-stack").show().attr("active","true");
   $("#" + appStack + "-stack").find(".app-content").hide();
   $("#" + appStack + "-stack").find(".app-content").last().show();
   $(".item").removeClass("active");
   $(".item[href^='#/" + appStack + "']").addClass("active");
   newHash = "/" + appStack + "/" + undead.topAction(appStack);
   if (newHash != window.location.hash) {
      window.location.hash = newHash;
   }
}

undead.activeStackName = function() {
   return $(".app-stack[active=true]").attr("app");
}

undead.popActiveStack = function() {
   undead.popStack(undead.activeStackName());
}

$(".pop-active").live('click', function(e) {
   undead.popActiveStack();
});

undead.popStack = function(appStack) {
   $("#" + appStack + "-stack").find(".app-content").last().remove();
   undead.focusApp(appStack);
}

undead.stackSize = function(appStack) {
   return $("#" + appStack + "-stack").find(".app-content").length;
}

undead.topAction = function(appStack) {
   return $("#" + appStack + "-stack").find(".app-content").last().attr("action");
}

undead.handleHashChange = function(hash) {
   if (undead.ignoreHash == false) {
      var re = hash.match(/([a-z_]+)\/([a-z_]+)/);
      if (re != null) {
         app = re[1];
         action = re[2];
         if (undead.stackSize(app) > 1 && undead.topAction(app) != action) {
            undead.popStack(app);
         } else if (undead.stackSize(app) > 0) {
            undead.focusApp(app);
         }
      } else {
         if (undead.activeStackName() != undead.defaultApp) {
            undead.focusApp(undead.defaultApp);
         } else if (undead.stackSize(undead.defaultApp) > 1) {
            undead.popStack(undead.defaultApp);
         }
      }
   } else {
      undead.ignoreHash = false;
   }
}

$(window).bind('hashchange', function() {
   undead.handleHashChange(window.location.hash);
});

undead.refreshStack = function(appStack) {
   topFrame = $("#" + appStack + "-stack").find(".app-content").last();
   data = JSON.parse(unescape(topFrame.attr("json")));
   $.ajax({"data":data,
           "dataType":"html",
           success:function(data) {
               topFrame.html(data);
               undead.focusApp(appStack);
               undead.addMessage("App Refreshed", "The app <i>" + 
                                 appStack + "." + topFrame.attr("action") + 
                                 "</i> was successfully refreshed");
           }
   });
}

undead.emptyStack = function(appStack) {
   $("#" + appStack + "-stack").find(".app-content").remove();
}

undead.pushStack = function(appStack, action, data) {
   if (action == null) {
      action = "index";
   }
   if (data == null || typeof data != "object") {
      data = {"app":appStack,
              "action":action};
   } else {
      data.app = appStack;
      data.action = action;
   }
   stackDiv = $("#" + appStack + "-stack");
   if (stackDiv.length == 0) {
      $("#content").append('<div app="' + appStack + '" class="app-stack" id="' + appStack + '-stack"></div>');
      stackDiv = $("#" + appStack + "-stack");
   }
   jsonStr = escape(JSON.stringify(data));
   $.ajax({"data":data,
           "dataType":"html",
           success:function(data) {
               undead.ignoreHash = true;
               window.location.hash = "/" + appStack + "/" + action;
               div = '<div class="app-content" json="' + jsonStr + '" action="' + action + '">' + data + '</div>';
               stackDiv.append(div);
               stackDiv.show();
               undead.focusApp(appStack);
               undead.addMessage("App Loaded", "The app <i>" + 
                                 appStack + "." + action + 
                                 "</i> was successfully loaded");
           }
   });
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
         undead.warn('An error occured:' + error + status);
      },
      "dataFilter":function(rawData, type) {
         if (undead.debug) {
            undead.warn('raw data:' + rawData);
         }
         if (type == "json") {
            try {
               data = $.parseJSON(rawData);
               if (data.status == "logged out") {
                  window.location.reload();
               }
               if (data.query != null) {
                  undead.warn(data.query);
               }
               if (typeof data.errors == "object") {
                  for (i = 0; i < data.errors.length; ++i) {
                     mesg = "<i>" + data.errors[i].errstr + "</i> in " +
                            data.errors[i].errfile + " on line " +
                            data.errors[i].errline + ".";
                     undead.addError(data.errors[i].errno, mesg);
                  }
               }
               if (typeof data.alert == "object") {
                  for (i = 0; i < data.alert.length; ++i) {
                     undead.warn(data.alert[i]);
                  }
               } else if (data.alert != null) {
                  undead.warn(data.alert);
               }
            } catch (e) {
               undead.warn('error parsing json:' + rawData);
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
         undead.warn("ajax data:" + options.data);
      }
   });
}
