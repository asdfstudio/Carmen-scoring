@extends('layouts.simple')


@section('content-header')
  <h1>
    {{ $soloDivision->name }} Audience Vote
  </h1>

  <ul class="actions-group">
    <li>{{ link_to_route('organizer.competition.solo-division.show','Back to Solo Division',[$competition, $soloDivision],['class' => 'action']) }}</li>
  </ul>
@endsection

@section('content')
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="{{asset('dropzone/dist/min/dropzone.min.css')}}">
  <!-- JS -->
  <script src="{{asset('dropzone/dist/min/dropzone.min.js')}}" type="text/javascript"></script>
  <form method="POST"
        action="{{route('organizer.competition.solo-division.audience-vote',[$competition->id,$soloDivision->id])}}"
        accept-charset="UTF-8"
        id="organizer_form">
    {{ csrf_field() }}
    <input type="hidden" name="division_id" value="{{$soloDivision->id}}">
    <input type="hidden" name="competition_id" value="{{$competition->id}}">
    <div class="form-group">
      <label class="control-label required">Public Link</label>
      <div>
        <span id="preview_url">{{url('/solo-division/'.$organization_slug)}}-{{$soloDivision->id}}/</span>
        <input
          class="form-control" required
          value="{{isset($audience)?$audience->alias_name:str_replace(' ','-', $soloDivision->name)}}"
          style="max-width: 150px;display: inline-block" name="alias_name" id="alias_name" type="text">

        <button type="button" class="ml-5 btn btn-primary" onclick="copyLink(1)">Copy Link</button>
      </div>
      <br>
      <div>
        {{url('/solo-division/'.$organization_slug)}}-{{$soloDivision->id}}/<span
          style="min-width: 20px; width:auto;display: inline-block" readonly id="copy_alias_name"
          type="text">{{isset($audience)?$audience->alias_name:str_replace(' ','-', $soloDivision->name)}}</span>/results
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
        <div class='dropzone image_video banner_option' style="display: none">
          @if(isset($audience) && '' != $audience->banner_upload)
            <div class="dz-preview dz-processing dz-success dz-complete dz-image-preview img-uploaded">
              <div class="dz-image">
                @php
                  $banner_url = 'uploads/'.$audience->batnner_upload;
                  if(env('AWS_ACCESS_KEY_ID')) {
                    $banner_url = env('AWS_URL').$audience->banner_upload;
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
        <label for="limit_result">Limit results to the <top class="."></top></label>
        <input type="number"
               class="form-control"
               id="limit_result"
               name="limit_result"
               value="@if($audience){{$audience->limit_result}}@else{{6}}@endif">
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="facebook_url" class="control-label">Facebook</label>
            <input id="facebook_url" type="text"
                   name="social[facebook]"
                   value="{{isset($audience)?$audience->social['facebook']:''}}"
                   class="form-control"
                   placeholder="Facebook URL">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="twitter_url" class="control-label">Twitter </label>
            <input id="twitter_url"
                   type="text"
                   name="social[twitter]"
                   value="{{isset($audience)?$audience->social['twitter']:''}}"
                   class="form-control"
                   placeholder="Twitter URL">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="instagram_url" class="control-label">Instagram </label>
            <input id="instagram_url"
                   type="text"
                   name="social[instagram]"
                   value="{{isset($audience)?$audience->social['instagram']:''}}"
                   class="form-control"
                   placeholder="Instagram URL">
          </div>
        </div>
      </div>

      <div class="form-group">

        <label class="checkbox-inline">
          <input type="checkbox" name="is_required_login" value="1"
                 @if($audience) @if($audience->is_required_login)checked @endif @endif> Require login for
          election</label>
        <label class="checkbox-inline">
          <input type="checkbox" name="disable_vote" value="1"
                 @if(!$audience) checked @endif
                 @if($audience) @if($audience->disable_vote)checked @endif @endif> Disable Vote</label>

      </div>
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
      $('.' + banner_type).show();
      $('input[name="banner_type"]').closest('label').click(function () {
        $('.banner_option').hide();
        banner_type = $(this).find('input').val();
        $('.' + banner_type).show();
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
          url: '{{route('organizer.competition.solo-division.audience-vote',[$competition->id,$soloDivision->id])}}',
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

  </script>
@endsection
