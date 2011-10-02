/**************************************************
 * UI functions                                   *
 **************************************************/

zs.ui = {};

zs.ui.alertShowTime = 2000;

zs.ui.errorLevels = {
   100 : "Message",
   101 : "Warning",
   102 : "Error",
   2 : "PHP Warning",
   8 : "PHP Notice",
   512 : "PHP User Warning",
   1024 : "PHP User Notice",
   2048 : "PHP Strict"
};

// reports an error 
zs.ui.error = function (mesg, level) {
   var title;
   if (level != null) {
      title = zs.ui.errorLevels[level];
   } else {
      title = "error";
   }
   zs.ui.addAlert("error", title, mesg);
   zs.ui.logError(title, mesg);
};

// warning message
zs.ui.warn = function (mesg) {
   zs.ui.addAlert("warning", "warning", mesg);
   zs.ui.logError("Warning", mesg);
};

// you just wanted to say hi
zs.ui.message = function (mesg) {
   zs.ui.addAlert("message", "message", mesg);
   zs.ui.logMessage("message", mesg);
};

zs.ui.addAlert = function(type, title, mesg) {
   mesg = $("<div/>").text(mesg).html();
   mesg = mesg.replace(/\n/g,"<br />");
   var flash = $("<div style=\"display:none;\" class=\"" + type + "\"></div>");
   flash.html("<span class=\"title\">" + title + ":</span>" + mesg);
   $("#alerts").append(flash);
   flash.slideDown("slow", function() {
      $(this).animate({"left":1}, zs.ui.alertShowTime, function() {
         $(this).slideUp("slow");
      });
   });
   flash.click(function() {
      $(this).stop(true).css({"border-color":"black"}).unbind('click').click(function() {
         $(this).slideUp("slow");
      });
   });
}

// add a message to the console
zs.ui.logMessage = function (title, mesg) {
   var html = "<div class=\"console-title\">" + title + "</div>";
   zs.ui.consoleAdd(html, mesg);
};

// add an error to the console
zs.ui.logError = function (title, mesg) {
   var html;
   html = "<div class=\"console-error\">" + title + "</div>";
   // fix this ugly hack that doesn't work properly
   zs.ui.oldConsoleColor = $("a[href^='/console']").css("color");
   $("a[href^='/console']").css({"color" : "red", "font-weight" : "bold"}).click(function () {
      $(this).css({"color" : zs.oldConsoleColor, "font-weight" : "normal"});
   });
   zs.ui.consoleAdd(html, mesg);
};

// add html to the console
zs.ui.consoleAdd = function (html, text) {
   text = $("<div/>").text(text).html();
   text = text.replace(/\n/g,"<br />");
   var tr = $("<tr />");
   var td = $("<td />");
   td.html("<div class=\"console-mesg-close\">X</div>" + html + text);
   tr.append(td);
   $("#console-messages tr th").parent().after(tr);
};

// check for incomplete required fields
zs.ui.verifyForm = function (form) {
   var formDone = true;
   /*
   var radios = form.find("radio");
   if (radios.length > 0) {
      var radioNames = [];
      radios.each(function () {
      });
   }
   */
   form.find("input, textarea, select").each(function () {
      var validator, value, tmp, format, re;
      var formValue = $(this).val();
      var formName = $(this).attr("name");
      var validatorsStr = $(this).attr("validate");
      var fail = false;
      var errorStr = '';
      if (!validatorsStr) {
         return;
      }
      var validators = validatorsStr.split(",");
      for (var i = 0; i < validators.length; ++i) {
         validator = validators[i];
         if (validator.match("=")) {
            tmp = validator.split("=");
            validator = tmp[0];
            value = tmp[1];
         }
         if (validator == "required") {
            if (!formValue) {
               fail = true;
               errorStr = formName + " is required.";
            }
         } else if (validator == "maxlen") {
            if (formValue && formValue.length > value) {
               fail = true;
               errorStr = formName + " is too long (max length " + value + ".)";
            }
         } else if (validator == "minlen") {
            if (formValue.length > 0 && formValue.length < value) {
               fail = true;
               errorStr = formName + " is too short (min length " + value + ".)";
            }
         } else if (validator == "number") {
            if (formValue && isNaN(formValue)) {
               fail = true;
               errorStr = formName + " must be a number.";
            }
         } else if (validator == "int") {
            if (formValue && parseInt(formValue) != formValue) {
               fail = true;
               errorStr = formName + " must be a whole number.";
            }
         } else if (validator == "format") {
            format = value;
            re = new RegExp(format);
            if (formValue && !formValue.match(re)) {
               fail = true;
               errorStr = formName + " is in the wrong format.";
            }
         }
      }
      if (fail) {
         formDone = false;
         $(this).parent().parent().find(".error").hide().html(errorStr).fadeIn();
         $(this).css({"background" : "#fdd"});
      } else {
         $(this).parent().parent().find(".error").hide().html('');
         $(this).css({"background" : "#fff"});
      }
   });
   return formDone;
};

zs.ui.tinymceOptions = {
   script_url : zs.settings.baseUrl + '/js/tiny_mce/tiny_mce.js',
   theme : "simple"
};

zs.ui.wysiwyg = function (textarea) {
   zs.util.importJs('/js/tiny_mce/jquery.tinymce.js');
   $(textarea).tinymce(zs.ui.tinymceOptions);
};
