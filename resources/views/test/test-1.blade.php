<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Test page</title>

  <style>
  .board-list {
    width: 200px;
    background: #ddd;
    padding: 10px;
    overflow: hidden;
    float: left;
    margin-right: 20px;
  }

  ul.cards {
    margin: 10px 0;
    padding: 0;
    overflow: hidden;
    list-style: none;
  }

  .card {
    padding: 5px;
    background: #fff;
    margin-bottom: 3px;
  }

  #modal-cover {
    background: #444;
    opacity: .5;
    z-index: 99;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
  }
  #modal {
    z-index: 100;
    background: #fff;
    padding: 20px;
    position: fixed;
    margin: auto;
    top: 20px;
    left: 50px;
    right: 50px;
    width: 400px;
  }
  </style>
</head>
<body class="division-dashboard">

  @php
  $choirs = [
    [
      'id' => 1,
      'name' => 'Swingers'
    ],
    [
      'id' => 2,
      'name' => 'Beebops'
    ],
    [
      'id' => 3,
      'name' => 'Kings'
    ]
  ];

  $judges = [];

  @endphp


  <h1>Test page</h1>

  <div class="division-board" id="division-13-board">
    <h2>Division Name</h2>
    <!--<a href="edit-division">Edit Division</a>-->

    <div class="board-list choirs" id="choir-list" data-min-cards="0" data-max-cards="8">
      <div class="list-header">
        <h3>Choirs</h3>
        <span class="card-count" data-resource-type="choir">{{ count($choirs) }}</span>
      </div>

      <form method="post" action="/organizer/competition/34/division/137/choir" class="add-resource-form-prototype add-resource-form" data-resource-type="choir" style="display:none;">
        {{ Form::token() }}
        <label>Choir ID</label>
        <input type="text" name="id" placeholder="Enter choir id" value="@{{id}}" />

        <label>Choir Name</label>
        <input type="text" name="name" placeholder="Enter choir name" value="@{{name}}" />

        <button type="submit">Save Choir</button>
        <input type="submit" value="Save Choir" />

      </form>


      <ul class="choirs cards" data-resource-type="choir">

        <li class="card-prototype card choir" data-resource-type="choir" style="display:none;">
          <div class="title">@{{name}}</div>
          <div class="subtitle">@{{id}}</div>
          <div class="actions">
            <a class="edit-choir" href="#">Edit choir</a>
            <a class="remove-choir" href="#">Remove choir</a>
          </div>
        </li>

        @foreach($choirs as $choir)
          <li class="choir card" data-resource-type="choir" data-resource-id="{{ $choir['id'] }}">
            <div class="title">{{ $choir['name'] }}</div>
            <div class="subtitle">City, State</div>
            <div class="actions">
              <a class="edit-resource" data-resource-type="choir" data-resource-id="{{ $choir['id'] }}" href="#" data-resource="{{ json_encode($choir) }}">Edit</a>
              <a class="remove-resource" data-resource-type="choir" data-resource-id="{{ $choir['id'] }}" href="/organizer/competition/34/division/137/choir">Remove choir</a>
            </div>
          </li>
        @endforeach
      </ul>
      <a class="add-resource" data-resource-type="choir" href="#">Add a choir resource...</a>
    </div>

  </div>



  <div class="board-list judges" id="judge-list" data-min-cards="0" data-max-cards="8">
    <div class="list-header">
      <h3>Judges</h3>
      <span class="item-count judges">{{ count($judges) }}</span>
    </div>

    <div class="add-resource-form-prototype" data-resource-type="judge" style="display:none;">
      <form>
        <label>Judge Name</label>
        <input type="name" placeholder="Enter judge name" />

        <button type="submit">Save Judge</button>
        <input type="submit" value="Save Judge" />

      </form>
    </div>


    <ul class="judges cards"></ul>

    <a class="add-resource" data-resource-type="judge" href="#">Add a judge resource...</a>
  </div>

</div>


  <div id="modal-cover" style="display:none;"></div>
  <div id="modal" style="display:none;"></div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>

  <script src="/js/mustache.min.js"></script>

  <script type="text/javascript">
  $(document).ready(function() {


    //
    // modalFormClass
    var Modal = (function() {

      var modal = $('#modal');
      var modalCover = $('#modal-cover');

      var open = function(formHtml) {
        // Populate form in modal
        modal.html(formHtml);
        // Show page cover
        modalCover.show();
        // Show modal
        modal.show();
      };

      var close = function() {
        // Show page cover
        modalCover.hide();
        // Show modal
        modal.hide();
      };

      return {
        open: open,
        close: close
      };

    })();

    //
    // Card
    var Card = (function() {

      var createCard = function(type, data) {
        var prototypeCard = $('.card-prototype[data-resource-type="'+type+'"]');
        // clone form
        var card = prototypeCard.clone(true, true);
        card.removeClass('card-prototype');
        card.show();
        card.wrap('<div>');
        return Mustache.render(card.parent().html(), data);
      };

      var renderCard = function(type, id, html) {

        var existingCard = this.getExistingCard(type, id);

        if(existingCard.length)
        {
          return existingCard.replaceWith(html);
        }
        else {
          return $('ul.cards[data-resource-type="'+type+'"]').append(html);
        }

      };

      var createAndRenderCard = function(type, data) {
        var html = this.createCard(type,data);
        return this.renderCard(type, data.id, html);
      };

      var removeCard = function(type, id) {
        var existingCard = this.getExistingCard(type, id);

        if(existingCard)
        {
          return existingCard.remove();
        }
      };

      var getExistingCard = function(type, id) {
        var existingCard = $('.card[data-resource-type="'+type+'"][data-resource-id="'+id+'"]');

        if(existingCard.length > 0)
        {
          return existingCard;
        }

        return false;
      };

      return {
        createCard: createCard,
        renderCard: renderCard,
        createAndRenderCard: createAndRenderCard,
        getExistingCard: getExistingCard,
        removeCard: removeCard
      };

    })();


    //
    // listClass
    var List = (function() {

      var updateCounter = function(type) {
        var newCount = $('.card[data-resource-type="'+type+'"]:not(.card-prototype)').length;
        $('.card-count[data-resource-type="'+type+'"]').html(newCount);
      };

      return {
        updateCounter: updateCounter
      };

    })();

    //
    // Form
    var Form = (function() {

      var getForm = function(type, id, resource) {
        var prototypeForm = $('.add-resource-form-prototype[data-resource-type="'+type+'"]');

        var form = prototypeForm.clone();

        // tweak form action
        if(id)
        {
          var action = $(form).attr('action');
          $(form).attr('action', action + '/' + id);
        }

        $(form).show();
        form.wrap('<div>');
        return Mustache.render(form.parent().html(), resource);
      };

      var buildRequest = function(form) {

        var resourceType = form.data('resource-type');

        var request = {
          data: form.serialize(),
          dataType: 'json',
          method: form.attr('method'),
          url: form.attr('action'),
          complete: function(xhr, status) {
            Resource.handleSaveComplete(status);
          },
          success: function(data, status) {
            Resource.handleSaveSuccess(resourceType, data, status);
          },
          error: function(xhr, status) {
            Resource.handleSaveError(status);
          }
        };

        return request;
      };


      var buildRemoveRequest = function(link) {

        var resourceType = $(link).data('resource-type');
        var id = $(link).data('resource-id');
        var data = {'_token': "{{ csrf_token() }}" };

        var request = {
          data: data,
          dataType: 'json',
          method: 'POST',
          url: $(link).attr('href'),
          complete: function(xhr, status) {
            Resource.handleSaveComplete(status);
          },
          success: function(data, status) {
            Resource.handleRemoveSuccess(resourceType, id, data, status);
          },
          error: function(xhr, status) {
            Resource.handleSaveError(status);
          }
        };

        return request;
      };

      return {
        getForm: getForm,
        buildRequest: buildRequest,
        buildRemoveRequest: buildRemoveRequest
      };
    })();


    //
    // Resource
    var Resource = (function() {

      var add = function(type) {
        var html = Form.getForm(type, false, {});
        Modal.open(html);
      };

      var edit = function(type, id, resource) {
        var html = Form.getForm(type, id, resource);
        Modal.open(html);
      };

      var remove = function(link) {
        var request = Form.buildRemoveRequest(link);
        $.ajax(request);
      };

      var save = function(form) {
        var request = Form.buildRequest(form);
        $.ajax(request);
      };

      var handleSaveSuccess = function(type, data, status) {
        Card.createAndRenderCard(type, data);
        List.updateCounter(type);
      };

      var handleRemoveSuccess = function(type, id, data, status) {
        Card.removeCard(type, id);
        List.updateCounter(type);
      };

      var handleSaveError = function(status) {
        console.log(status);
      };

      var handleSaveComplete = function(status) {
        Modal.close();
      };

      return {
        add: add,
        edit: edit,
        remove: remove,
        save: save,
        handleSaveSuccess: handleSaveSuccess,
        handleSaveError: handleSaveError,
        handleSaveComplete, handleSaveComplete,
        handleRemoveSuccess: handleRemoveSuccess,
      };
    })();


    $('.add-resource').on('click', function(e) {
      e.preventDefault();
      var type = $(this).data('resource-type');
      Resource.add(type);
    });

    $('.edit-resource').on('click', function(e) {
      e.preventDefault();
      var type = $(this).data('resource-type');
      var id = $(this).data('resource-id');
      var resource = $(this).data('resource');
      Resource.edit(type, id, resource);
    });

    $('.remove-resource').on('click', function(e) {
      e.preventDefault();
      Resource.remove(this);
    });


    $('#modal').on('submit', 'form', function(e) {
      e.preventDefault();
      var form = $(this);
      Resource.save(form);
    });

    $('#modal-cover').on('click', function(e) {
      e.preventDefault();
      Modal.close();
    });

  });

  </script>
</body>
</html>
