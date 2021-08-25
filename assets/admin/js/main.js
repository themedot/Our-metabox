var frame;
;(function($){
    $(document).ready(function() {
        $(".omb_dp" ).datepicker({
          showOtherMonths: true,
          selectOtherMonths: true
        });
        $("#upload_image").on("click",function(){


          if ( frame ) {
            frame.open();
            return false;
          }
          
          frame = wp.media({
            title: "Select Image",
            button:{
              text:"Insert Image"
            },
            multiple:false
          });

          frame.on('select',function(){
            attachment = frame.state().get('selection').first().toJSON();
            $("#omb_image_id").val(attachment.id);
            $("#omb_image_url").val(attachment.sizes.thumbnail.url);
            console.log(attachment);
            $("#image_container").html(`<img src="${attachment.sizes.thumbnail.url}" id='${attachment.id}' />`);
          });

          frame.open();
          return false;
        });
      } );
})(jQuery);


