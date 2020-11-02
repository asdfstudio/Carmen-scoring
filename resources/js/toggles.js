toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

$(document).ready(function(){

  $(document).on("click",'.premium',function(){

    var org_id = $(this).data('id');
    var Url = $(this).data('href');

    $.ajax({
      type: "GET",
      url: Url,
      success: function (data) {

        Command: toastr["success"](data.message);
      }
    })
  });

  $(document).on("click",'.audience-vote',function(){

    var org_id = $(this).data('id');
    var Url = $(this).data('href');

    $.ajax({
      type: "GET",
      url: Url,
      success: function (data) {

        Command: toastr["success"](data.message);
      }
    })
  });
})