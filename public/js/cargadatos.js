$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 


     $('body').on('submit', '#formImportLibro', function () {
        var formData = new FormData(this);
        $('#BtnImport').attr("disabled", true);
        $("#BtnImport").html("<div class='spinner-border spinner-border-sm' role='status'><span class='sr-only'>Loading...</span></div>");  
         $.ajax({
             url: '/ImportLibro',
             data: formData,
             type: 'POST',
             contentType: false,
             cache: false,
             processData: false,
             // dataType: "json",
             success: function (data) {
                window.location.reload();
             }
         });
 
         return false;
     });






    
}); 