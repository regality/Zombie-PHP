$(function () {
   $("#console-clear").click(function () {
      $("#console-messages").find("td").each(function() {
         $(this).parent().remove();
      });
   });

   $(".console-mesg-close").live('click', function () {
      $(this).parent().remove();
   });

   zs.util.require("zombiescript/defaultvalue");
   $("#console-run").defaultvalue("Console...", "#999999", "#000000");

   $("#console-run").keydown(function(e) {
      var ENTER = 13;
      var UP = 38;
      var DOWN = 40;
      if (e.keyCode == ENTER) {
         var ret = eval($(this).val());
         zs.ui.logMessage($(this).val(), String(ret));
         $(this).val("");
      }
   });

   $("a.cookie").live("click", function(e) {
      e.preventDefault();
      $("pre.cookie").slideToggle();
   });

   $("a.session").live("click", function(e) {
      e.preventDefault();
      $("pre.session").slideToggle();
   });

   $("a.server").live("click", function(e) {
      e.preventDefault();
      $("pre.server").slideToggle();
   });
});
