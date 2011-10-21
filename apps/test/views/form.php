<form id="testform">
   <div class="grid_7 form alpha omega form">
      <div class="grid_11 field">
         <h3>Test Form</h3>
      </div>

      <div class="grid_7 alpha omega">
         <hr />
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Required Field</label>
         </div>
         <div class="grid_4">
            <input validate="required" type="text" name="required" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>No Validator</label>
         </div>
         <div class="grid_4">
            <input type="text" name="novalidate" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Min Length 2</label>
         </div>
         <div class="grid_4">
            <input validate="minlen=2" type="text" name="minlen" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Max Length 10</label>
         </div>
         <div class="grid_4">
            <input validate="maxlen=10" type="text" name="maxlen" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Number</label>
         </div>
         <div class="grid_4">
            <input validate="number" type="text" name="number" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Integer</label>
         </div>
         <div class="grid_4">
            <input validate="int" type="text" name="int" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Email Format</label>
         </div>
         <div class="grid_4">
            <input validate="format=\S+@\S+\.\S+" type="text" name="format" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Multiple</label>
         </div>
         <div class="grid_4">
            <input validate="required,minlen=2,maxlen=5,number" type="text" name="multiple" value="" />
         </div>
         <div class="grid_4 error omega">
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_3 alpha">
            <label>Custom Error</label>
         </div>
         <div class="grid_4">
            <input validate="required" type="text" name="custom" value="" />
         </div>
         <div class="grid_4 error omega">
            <div class="required" style="display:none;">
               This is a custom error.
            </div>
         </div>
      </div>

      <div class="grid_11 alpha omega field">
         <div class="grid_1 prefix_2 suffix_1 alpha">
            <button class="test-validate">Validate</button>
            <script type="text/javascript">
            $(function() {
               $(".test-validate").click(function() {
                  zs.ui.verifyForm($("#testform"));
                  return false;
               });
            });
            </script>
         </div>

         <div class="grid_1 suffix_6 omega">
         </div>
      </div>

   </div>
</form>
