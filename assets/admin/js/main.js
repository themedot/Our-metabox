var frame;
;(function($){
    $(document).ready(function() {
        $(".omb_dp" ).datepicker({
          showOtherMonths: true,
          selectOtherMonths: true
        });
        $("#upload_image").on("click",function(){
          
          frame = wp.media({
            title: "Upload Image",
            button:{
              text:"Select Image"
            },
            multiple:false
          });
          frame.open();
          return false;
        });
      } );
})(jQuery);


