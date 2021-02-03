@extends('layouts.simple')

@php $include_round_navigation_bar = TRUE @endphp

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.round.show',$round->competition, $round) !!}
@endsection

@section('content-header')
	<h1>Audience Vote settings for {{ $round->name }} </h1>

	<ul class="actions-group">

		@can('showAll','App\Round')
            <li>{{ link_to_route('organizer.competition.round.show', 'Back to Round', [$round->competition,$round], ['class' => 'action']) }}</li>
		@endcan

	</ul>
@endsection

@section('content')
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="{{asset('dist/css/vendor/dropzone.css')}}">
  <!-- JS -->
  <script src="{{asset('dist/js/vendor/dropzone.js')}}" type="text/javascript"></script>
  <form method="POST" action="{{route('organizer.competition.round.audience.store',[$round->competition->id, $round->id])}}"
        accept-charset="UTF-8"
        id="organizer_form">
    {{ csrf_field() }}
    <input type="hidden" name="round_id" value="{{$round->id}}">
    <input type="hidden" name="competition_id" value="{{$round->competition->id}}">
    <div class="form-group">
      <label class="control-label required">Public Link</label>
      <div>
          <span id="preview_url">{{url('/home/'.$organization_slug)}}-{{$round->id}}/</span>
        <input
          class="form-control" required
          value="{{isset($audience)?$audience->alias_name:str_replace(' ','-', $round->name)}}"
          style="max-width: 150px;display: inline-block" name="alias_name" id="alias_name" type="text">

        <button type="button" class="ml-5 btn btn-primary" onclick="copyLink(1)">Copy Link</button>
      </div>

      <br>
      <div>
          {{url('/home/'.$organization_slug)}}-{{$round->id}}/<span
          style="min-width: 20px; width:auto;display: inline-block" readonly id="copy_alias_name"
          type="text">{{isset($audience)?$audience->alias_name:str_replace(' ','-', $round->name)}}</span>/results
        <button type="button" class="ml-5 btn btn-primary" onclick="copyLink(2)">Copy Link</button>
      </div>


    </div>

    <div class="form-group">
      <label class="control-label required">Select Theme</label>
      <div>
        <label class="radio-inline">
          <input type="radio" name="is_dark" value="1" class="form-check-input"
                 @if($audience) @if($audience->is_dark)checked @endif  @else checked @endif>Dark</label>
        <label class="radio-inline">
          <input type="radio" name="is_dark" value="0" class="form-check-input"
                 @if($audience) @if(!$audience->is_dark)checked @endif @endif>Bright</label>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label required">Select Vote Type</label>
      <div>
        <label class="radio-inline">
          <input type="radio" name="is_premium_vote" value="0" class="form-check-input"
                 @if($audience) @if(!$audience->is_premium_vote)checked @endif  @else checked @endif>Free Audience Vote</label>
        <label class="radio-inline">
          <input type="radio" name="is_premium_vote" value="1" class="form-check-input"
                 @if($audience) @if($audience->is_premium_vote)checked @endif @endif>1$ per Audience Vote</label>
      </div>
    </div>

    <div class="form-group" id="selectBanner">
      <label class="control-label required">Select Banner</label>
      <div class="radio">
        <label><input type="radio" name="banner_type" value="hide" class="form-check-input"
                      @if($audience) @if($audience->banner_type == 'hide')checked @endif  @else checked @endif>Hide
          banner</label>
      </div>
      <div class="radio">
        <label><input type="radio" name="banner_type" value="image_video" class="form-check-input"
                      @if($audience) @if($audience->banner_type == 'image_video')checked @endif @endif>Show Video or
          Images</label>
        <button type="button" class="ml-5 btn btn-danger btn-embed-file " style="display: none;" onclick="clearFile()">Clear</button>
        <div class='dropzone image_video banner_option' style="display: none">
          @if(isset($audience) && '' != $audience->banner_upload)
            <div id="banner-element" class="dz-preview dz-processing dz-success dz-complete dz-image-preview img-uploaded">
              <div class="dz-image">
                  @php
                    $banner_url = 'uploads/'.$audience->banner_upload;
                    if(env('VOTING_AWS_ACCESS_KEY_ID')) {
                      $banner_url = env('VOTING_AWS_URL').$audience->banner_upload;
                    }
                  @endphp

                  @if('mp4' === substr($audience->banner_upload, -3))
                    <video class="mx-auto" width="1200" height="657" controls>
                      <source src="{{$banner_url}}" type="video/mp4">
                      Your browser does not support the video tag.
                    </video>
                  @else
                    <img class="mx-auto img-responsive" src="{{$banner_url}}">
                  @endif
              </div>
            </div>

          @endif
        </div>
        <input type="hidden" name="banner_upload" id="banner_upload" class="form-check-input"
               value="@if($audience) {{$audience->banner_upload}} @endif">
      </div>
      <div class="radio">
        <label><input type="radio" name="banner_type"
                      value="embed_video"
                      class="form-check-input"
                      @if($audience) @if($audience->banner_type == 'embed_video')checked @endif @endif>Insert Embed
          video link (Youtube, Vimeo,...)</label>
        <input class="form-control embed_video banner_option" name="banner_embed" style="display:none;" type="text"
               value="@if($audience) {{$audience->banner_embed}} @endif">
      </div>
      <div class="form-group">
        <label for="limit_result">Limit results to the top...</label>
        <input type="number"
               class="form-control"
               id="limit_result"
               name="limit_result"
               value="@if($audience){{$audience->limit_result}}@else{{6}}@endif">
      </div>

      <div class="form-group">

        <span class="disable-vote-span">Disable Vote:&nbsp;&nbsp;&nbsp;</span>
        <label class="disable-vote-switch">
          <input type="checkbox" name="disable_vote" value="1"
                 @if(!$audience) checked @endif
                 @if($audience) @if($audience->disable_vote) checked @endif @endif>
          <span class="slider round"></span>
        </label>

      </div>
      &nbsp;&nbsp;&nbsp;
      <br><br><br>

      <button class="btn btn-primary" type="submit" name="submit">Save</button>

  </form>


@endsection
@section('body-footer')
  <!-- Script -->
  <script>
    var CSRF_TOKEN = '{{ csrf_token() }}';

    (function ($) {
      'use strict';
      const bannerInput = $('input[name="banner_type"]:checked');
      let banner_type = bannerInput.val();
      resetView(banner_type)

      $('input[name="banner_type"]').closest('label').click(function () {
        $('.banner_option').hide();
        banner_type = $(this).find('input').val();
        resetView(banner_type)
      });

    })(jQuery)

    $('#alias_name').keyup(function () {
      $('#copy_alias_name').text($(this).val());
    })

    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone(".dropzone", {
      maxFiles: 1,
      maxFilesize: 30,  // 3 mb
      url: "{{route('audience.fileupload')}}",
      acceptedFiles: ".jpeg,.jpg,.png,.pdf,.mp4",
      init: function () {
        this.on("maxfilesexceeded", function (file) {
          this.removeAllFiles();
          this.addFile(file);
        });
      }
    });
    myDropzone.on("sending", function (file, xhr, formData) {
      formData.append("_token", CSRF_TOKEN);
    }).on("complete", function (file) {
      if (file.xhr) {
        var obj = jQuery.parseJSON(file.xhr.response)
        $('#banner_upload').val(obj.file_name);
        $('.img-uploaded').remove();
      }

    });

    function copyToClipboard(text) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val(text).select();
      document.execCommand("copy");
      $temp.remove();
    }

    function copyLink(flg) {
      if (flg == 1) {
        var text = $('#preview_url').text() + $('#alias_name').val();
        copyToClipboard(text);
      } else {
        var text = $('#preview_url').text() + $('#alias_name').val() + '/' + 'results';
        copyToClipboard(text);
      }
    }

    (function ($) {
      'use strict';
      //Create
      $(window).load(function () {
        const data = $('#organizer_form').serialize();
        $.ajax({
            url: '{{route('organizer.competition.round.audience.store',[$round->competition->id,$round->id])}}',
          type: 'POST',
          data: data,
          error: function (data) {
            console.log(data.responseJSON.message)
          },
          success: function () {
            console.log('success')
          }
        });
      });
    })(jQuery)

    function clearFile(){
      Dropzone.forElement('.dropzone').removeAllFiles(true)

      $("#banner-element").remove();
      $("#banner_upload").val("");
    }

    function resetView(banner_type){
      $('.' + banner_type).show();

      if(banner_type == 'image_video'){
        $(".btn-embed-file").show();
      }else{
        $(".btn-embed-file").hide();
      }
    }
  </script>
@endsection
