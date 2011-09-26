/**************************************************
 * Stack functions                                *
 **************************************************/

undead.stack = {};

undead.stack.defaultApp = "welcome";
undead.stack.defaultAction = "index";
undead.stack.ignoreHash = false;

// load the default application
undead.stack.loadDefault = function () {
   var re = window.location.hash.match(/([a-z_]+)\/?([a-z_]+)?/);
   if (re === null) {
      re = window.location.pathname.match(/([a-z_]+)\/?([a-z_]+)?/);
      if (typeof window.history.replaceState === "function") {
         window.history.replaceState({}, "", "/");
      }
   }
   if (re !== null) {
      if (typeof re[1] !== "undefined") {
         undead.stack.defaultApp = re[1];
      }
      if (typeof re[2] !== "undefined") {
         undead.stack.defaultAction = re[2];
      }
   }
   if (undead.stack.size(undead.stack.defaultApp) === 0 || 
       undead.stack.topAction(undead.stack.defaultApp) !== undead.stack.defaultAction) {
      undead.stack.push(undead.stack.defaultApp, undead.stack.defaultAction);
   } else {
      undead.stack.focus(undead.stack.defaultApp);
   }
};

// focus on a stack
undead.stack.focus = function (appStack) {
   var newHash;
   $(".app-stack:visible").hide().attr("active",null);
   $("#" + appStack + "-stack").show().attr("active","true");
   $("#" + appStack + "-stack").find(".app-content").hide();
   $("#" + appStack + "-stack").find(".app-content").last().show();
   $(".item").removeClass("active");
   $(".item[href^='/" + appStack + "']").addClass("active");
   newHash = "/" + appStack + "/" + undead.stack.topAction(appStack);
   if (newHash !== window.location.hash) {
      window.location.hash = newHash;
   }
};

// get the name of the active stack
undead.stack.activeName = function () {
   return $(".app-stack[active=true]").attr("app");
};

// pop the active stack
undead.stack.popActive = function () {
   undead.stack.pop(undead.stack.activeName());
};

// generic pop event
$(".pop-active").live('click', function (e) {
   e.preventDefault();
   undead.stack.popActive();
});

// get the size of a stack
undead.stack.size = function (appStack) {
   return $("#" + appStack + "-stack").find(".app-content").length;
};

// get the top (most recent) action of a stack
undead.stack.topAction = function (appStack) {
   var action = $("#" + appStack + "-stack").find(".app-content").last().attr("action");
   return action;
};

// handle the changing of the hash
undead.stack.handleHashChange = function (hash) {
   var app, action, re;
   if (undead.stack.ignoreHash === false) {
      re = hash.match(/([a-z_]+)\/([a-z_]+)/);
      if (typeof re !== "undefined") {
         app = re[1];
         action = re[2];
         if (undead.stack.size(app) > 1 && undead.stack.topAction(app) !== action) {
            undead.stack.pop(app);
         } else if (undead.stack.size(app) > 0) {
            undead.stack.focus(app);
         }
      } else {
         if (undead.stack.activeName() !== undead.stack.defaultApp) {
            undead.stack.focus(undead.stack.defaultApp);
         } else if (undead.stack.size(undead.stack.defaultApp) > 1) {
            undead.stack.pop(undead.stack.defaultApp);
         }
      }
   } else {
      undead.stack.ignoreHash = false;
   }
};

$(window).bind('hashchange', function () {
   undead.stack.handleHashChange(window.location.hash);
});

// refresh the top of a stack
undead.stack.refresh = function (appStack) {
   var topFrame, data;
   topFrame = $("#" + appStack + "-stack").find(".app-content").last();
   data = $.parseJSON(window.unescape(topFrame.attr("json")));
   $.ajax({"data" : data,
           "dataType" : "html",
           success : function (data) {
               topFrame.html(data);
               undead.stack.focus(appStack);
               undead.ui.logMessage("App Refreshed", "The app <i>" + 
                                 appStack + "." + topFrame.attr("action") + 
                                 "</i> was successfully refreshed");
           }
   });
};

// delete everything from a stack
undead.stack.empty = function (appStack) {
   $("#" + appStack + "-stack").find(".app-content").remove();
};

// pop a stack
undead.stack.pop = function (appStack) {
   $("#" + appStack + "-stack").find(".app-content").last().remove();
   undead.stack.focus(appStack);
};

// push onto a stack
undead.stack.push = function (appStack, appAction, data) {
   var stackDiv, jsonStr;
   if (typeof appAction === "undefined") {
      appAction = "index";
   }
   if (typeof data === "undefined" || typeof data !== "object") {
      data = {"app" : appStack,
              "action" : appAction};
   } else {
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
               undead.stack.ignoreHash = true;
               window.location.hash = "/" + appStack + "/" + appAction;
               div = '<div class="app-content" json="' + jsonStr + '" action="' + appAction + '">' + data + '</div>';
               stackDiv.append(div);
               stackDiv.show();
               undead.stack.focus(appStack);
               undead.ui.logMessage("App Loaded", "The app " + 
                                 appStack + "." + appAction + 
                                 " was successfully loaded");
           }
   });
};
