/**
 * @param      String
 * @return     Array
 */
(function($) {
   $.fn.defaultvalue = function() {
      
      // Scope
      var elements = this;
      var args = arguments;
      var c = 0;
      
      return(
         elements.each(function() {          
            
            // Default values within scope
            var el = $(this);
            var def = args[c++];
            var defcolor = args[c++];
            var newcolor = args[c++];

            if (defcolor != null) {
               el.css("color",defcolor);
            }
            el.val(def).focus(function() {
               if(el.val() == def) {
                  el.val("");
                  if (newcolor != null) {
                     el.css("color",newcolor);
                  }
               }
               el.blur(function() {
                  if(el.val() == "") {
                     el.val(def);
                     if (defcolor != null) {
                        el.css("color",defcolor);
                     }
                  }
               });
            });
            
         })
      );
   };
}(jQuery));
