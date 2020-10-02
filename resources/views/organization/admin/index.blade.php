@extends('layouts.simple')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

@endsection

@section('content-header')
<h1>Organizations</h1>

{{ link_to_route('admin.organization.create', 'Add an organization', [] ,['class' => 'action']) }}
@endsection


@section('content')

@include('organization.admin.list')

@endsection

@section('body-footer')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		$(document).on("click",'.round',function(){

			var org_id = $(this).data('id');
			var Url = '{{ route('admin.organization.premium-status', ['param']) }}';
			var Url = Url.replace('param', org_id);

			$.ajax({
				type: "GET",
				url: Url,
				success: function (data) {
					// alert();
					Command: toastr["success"](data.message);

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
				}
			})
		});
	})

</script>

@endsection
