@extends('layouts.simple')

@section('content-header')
  <h1>{{ $choir->full_name }}</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('admin.choir.index','Back to All Choirs', [], ['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

  <h2>Add a Director</h2>
  
  <style>
    .search-group {
      position: relative;
    }
    
    #person-search-suggestions {
      list-style: none;
      padding: 0;
      position: absolute;
      background: #ffffff;
      width: calc(100% - 8px);
      border: 1px #666 solid;
      border-top: 0;
      left: 4px;
      box-shadow: 0 10px 15px rgba(0,0,0,.5);
      border-bottom-left-radius: 4px;
      border-bottom-right-radius: 4px;
      overflow: scroll;
      max-height: 300px;
      z-index: 1;
    }
    
    #person-search-suggestions li {
      padding: 10px;
      border-bottom: 1px #ccc solid;
      color: #555555;
      cursor: pointer;
    }
    
    #person-search-suggestions li:hover,
    #person-search-suggestions li.selected {
      background: #b3d7ff;
    }
    
    .add-new {
      margin: 20px 0;
      display: inline-block;
    }
    
    .form-group {
      display: none;
    }
    
  </style>
  {!! form($form) !!}

@endsection

@section('body-footer')
  <script>
    jQuery(document).ready(function($){
      
      $('#person-search').on('blur', function(e){
        $('#person-search-suggestions').remove();
      });
      
      $('#person-search').on('input', function(e){
        
        if($(this).val().length){
          
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
          });
          
          $.ajax({
            url: "{{ url('/admin/person/search') }}",
            method: 'post',
            data: {
              person_search: $(this).val()
            },
            dataType: 'json',
            success: function(result){
              personSearchSuggestions(result);
            }
          });
          
        } else {
          
          $('#person-search-suggestions').remove();
          
        }
        
      });
      
      function personSearchSuggestions(suggestions){
        
        $('#person-search-suggestions').remove();
        
        if(suggestions.length){
          
          $('#person-search').after('<ul id="person-search-suggestions" />');
          
          $(suggestions).each(function(){
            $('#person-search-suggestions').append('<li data-person-id="' + this.id + '">' + this.first_name + ' ' + this.last_name + ' (' + this.email + ')' + '</li>');
          });
          
          $('#person-search-suggestions').on('mouseenter', function(e){
            $('#person-search').off('blur');
          });
          
          $('#person-search-suggestions').on('mouseleave', function(e){
            $('#person-search').on('blur', function(e){
              $('#person-search-suggestions').remove();
            });
          });
          
          $('#person-search-suggestions li').on('click', function(e){
            $('#person-search').val($(e.target).text());
            $('#person-id').val($(e.target).attr('data-person-id'));
            $('#person-search-suggestions').remove();
            $('.form-group').hide();
          });
          
        }
        
      }
      
      $('.add-new').on('click', function(){
        $('.form-group').toggle();
      });
      
    });
  </script>
@endsection