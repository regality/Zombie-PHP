/**************************************************
 * Stack functions                                *
 **************************************************/

zs.stack = {};

zs.stack.defaultApp = "welcome";
zs.stack.defaultAction = "index";
zs.stack.ignoreHash = false;

// load the default application
zs.stack.loadDefault = function () {
   var re = window.location.hash.match(/([a-z_]+)\/?([a-z_]+)?\??(.*)?$/);
   if (re === null) {
      re = window.location.pathname.match(/([a-z_]+)\/?([a-z_]+)?/);
      if (typeof window.history.replaceState === "function") {
         window.history.replaceState({}, "", "/");
      }
   }
   var data = {};
   if (re !== null) {
      if (typeof re[1] !== "undefined") {
         zs.stack.defaultApp = re[1];
      }
      if (typeof re[2] !== "undefined") {
         zs.stack.defaultAction = re[2];
      }
      if (typeof re[3] !== "undefined") {
         var pairs = re[3].split('&');
         for (i = 0; i < pairs.length; i += 1) {
            pair = pairs[i].split('=');
            if (typeof pair[1] === "undefined") {
               data[pair[0]] = '';
            } else {
               data[pair[0]] = pair[1];
            }
         }
      }
   }
   if (zs.stack.size(zs.stack.defaultApp) === 0 || 
      zs.stack.topAction(zs.stack.defaultApp) !== zs.stack.defaultAction) {
      zs.stack.push(zs.stack.defaultApp, zs.stack.defaultAction, data);
   } else {
      zs.stack.focus(zs.stack.defaultApp);
   }
};

// focus on a stack
zs.stack.focus = function (appStack) {
   var newHash;
   if (zs.stack.size(appStack) == 0) {
      zs.stack.push(appStack);
   } else {
      $(".app-stack:visible").hide().attr("active",null);
      $("#" + appStack + "-stack").show().attr("active","true");
      $("#" + appStack + "-stack").find(".app-content").hide();
      $("#" + appStack + "-stack").find(".app-content").last().show();
      $(".item").removeClass("active");
      $(".item[href^='/" + appStack + "']").addClass("active");
      newHash = "/" + appStack + "/" + zs.stack.topAction(appStack);
      //if (newHash !== window.location.hash) {
      if (!window.location.hash.match(newHash)) {
         window.location.hash = newHash;
      }
   }
};

// get the name of the active stack
zs.stack.activeName = function () {
   return $(".app-stack[active=true]").attr("app");
};

// pop the active stack
zs.stack.popActive = function () {
   zs.stack.pop(zs.stack.activeName());
};

// generic pop event
$(".pop-active").live('click', function (e) {
   e.preventDefault();
   zs.stack.popActive();
});

// get the size of a stack
zs.stack.size = function (appStack) {
   return $("#" + appStack + "-stack").find(".app-content").length;
};

// get the top (most recent) action of a stack
zs.stack.topAction = function (appStack) {
   var action = $("#" + appStack + "-stack").find(".app-content").last().attr("action");
   return action;
};

// handle the changing of the hash
zs.stack.handleHashChange = function (hash) {
   var app, action, re;
   if (zs.stack.ignoreHash === false) {
      re = hash.match(/([a-z_]+)\/([a-z_]+)/);
      if (typeof re !== "undefined") {
         app = re[1];
         action = re[2];
         if (zs.stack.size(app) > 1 && zs.stack.topAction(app) !== action) {
            zs.stack.pop(app);
         } else if (zs.stack.size(app) > 0) {
            zs.stack.focus(app);
         }
      } else {
         if (zs.stack.activeName() !== zs.stack.defaultApp) {
            zs.stack.focus(zs.stack.defaultApp);
         } else if (zs.stack.size(zs.stack.defaultApp) > 1) {
            zs.stack.pop(zs.stack.defaultApp);
         }
      }
   } else {
      zs.stack.ignoreHash = false;
   }
};

$(window).bind('hashchange', function () {
   zs.stack.handleHashChange(window.location.hash);
});

// refresh the top of a stack
zs.stack.refresh = function (appStack) {
   var topFrame, data;
   if (zs.stack.size(appStack) == 0) {
      zs.stack.push(appStack);
   } else {
      topFrame = $("#" + appStack + "-stack").find(".app-content").last();
      if (topFrame.attr("json")) {
         data = $.parseJSON(window.unescape(topFrame.attr("json")));
      } else {
         data = {"app" : appStack,
                 "action" : zs.stack.topAction(appStack)};
      }
      $.ajax({"data" : data,
              "dataType" : "html",
              success : function (data) {
                  topFrame.html(data);
                  zs.stack.focus(appStack);
                  zs.ui.logMessage("App Refreshed", "The app <i>" + 
                                    appStack + "." + topFrame.attr("action") + 
                                    "</i> was successfully refreshed");
              }
      });
   }
};

// delete everything from a stack
zs.stack.empty = function (appStack) {
   $("#" + appStack + "-stack").find(".app-content").remove();
};

// pop a stack
zs.stack.pop = function (appStack, allowEmpty) {
   $("#" + appStack + "-stack").find(".app-content").last().remove();
   zs.stack.focus(appStack);
};

// push onto a stack
zs.stack.push = function (appStack, appAction, data) {
   var stackDiv, jsonStr, getParams, tmp;
   getParams = "";
   if (typeof appAction === "undefined") {
      appAction = "index";
   }
   if (typeof data === "undefined" || typeof data !== "object") {
      data = {"app" : appStack,
              "action" : appAction};
   } else {
      tmp = [];
      for (key in data) {
         tmp.push(key + "=" + data[key]);
      }
      if (tmp.length > 0) {
         getParams = "?" + tmp.join("&");
      }
      data.app = appStack;
      data.action = appAction;
   }
   stackDiv = $("#" + appStack + "-stack");
   if (stackDiv.length === 0) {
      stackDiv = $('<div app="' + appStack + '" class="app-stack" id="' + appStack + '-stack"></div>');
      $("#content").append(stackDiv);
   }
   jsonStr = window.escape(JSON.stringify(data));
   $.ajax({"data" : data,
           "dataType" : "html",
           "success" : function callback (data) {
               var div;
               zs.stack.ignoreHash = true;
               window.location.hash = "/" + appStack + "/" + appAction + getParams;
               div = '<div class="app-content" json="' + jsonStr + '" action="' + appAction + '">' + data + '</div>';
               stackDiv.append(div);
               stackDiv.show();
               zs.stack.focus(appStack);
               zs.ui.logMessage("App Loaded", "The app " + 
                                 appStack + "." + appAction + 
                                 " was successfully loaded");
           }
   });
};
