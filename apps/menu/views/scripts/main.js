$(function () {
   $(".item").live('click', function () {
      if (!$(this).hasClass("active")) {
         $(".item").removeClass("active");
         $(this).addClass("active");
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
