var frame, gframe;
;(function($){
    $(document).ready(function() {

      var imageUrl = $("#omb_image_url").val();
      if(imageUrl){
        $("#image_container").html(`<img src="${imageUrl}"/>`);
      }

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
           var attachment = frame.state().get('selection').first().toJSON();
            $("#omb_image_id").val(attachment.id);
            $("#omb_image_url").val(attachment.sizes.thumbnail.url);
            // console.log(attachment);
            $("#image_container").html(`<img src="${attachment.sizes.thumbnail.url}"/>`);
          });

          frame.open();
          return false;
        });

// this section for gallery
        $("#upload_images").on("click",function(){

          if ( gframe ) {
            gframe.open();
            return false;
          }
          
          gframe = wp.media({
            title: "Select Images",
            button:{
              text:"Insert Images"
            },
            multiple:true
          });

          gframe.on('select',function(){
           var attachments = gframe.state().get('selection').toJSON();
           var image_ids = [];
           var image_urls = [];
            for(i in attachments){
              var attachment = attachments[i];
              // console.log(attachment);
              image_ids.push(attachment.id);
              image_urls.push(attachment.sizes.thumbnail);
              $("#images_container").append(`<img src="${attachment.sizes.thumbnail.url}"/>`);
            }
            console.log(image_ids,image_urls);
             $("#omb_images_id").val(image_ids.join(";"));
             $("#omb_images_url").val(image_urls.join(";"));
             


            // $("#omb_images_id").val(attachment.id);
            // $("#omb_images_url").val(attachment.sizes.thumbnail.url);
            // console.log(attachment);
            // $("#images_container").html(`<img src="${attachment.sizes.thumbnail.url}"/>`);
          });

          gframe.open();
          return false;
        });
      } );
})(jQuery);


