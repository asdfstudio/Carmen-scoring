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

      var getCard = function(type, data) {
        var prototypeCard = $('.card-prototype[data-resource-type="'+type+'"]');
        // clone form
        var card = prototypeCard.clone(true, true);
        card.removeClass('card-prototype');
        card.show();
        card.wrap('<div>');
        return Mustache.render(card.parent().html(), data);
      };

      var renderCard = function(type, id, html) {

        var existingCard = $('.card[data-resource-type="'+type+'"][data-resource-id="'+id+'"]');

        if(existingCard.length == 1)
        {
          return existingCard.replaceWith(html);
        }
        else {
          return $('ul.cards[data-resource-type="'+type+'"]').append(html);
        }

      };

      var getAndRenderCard = function(type, data) {
        var html = this.getCard(type,data);
        return this.renderCard(type, data.id, html);
      };

      return {
        getCard: getCard,
        renderCard: renderCard,
        getAndRenderCard: getAndRenderCard
      };

      /*var prototype = $('#choir-card-prototype');
      var newCard = false;
      var cardType = false;
      var list = $('#choir-list');

      var init = function(cardData){
        this.newCard = prototype.clone(true, true);
        this.newCard.removeClass('card-prototype');
        this.newCard.show();
        console.log(this.newCard);
      };

      var setCardType = function(cardType){
        this.cardType = cardType;
      };

      var setId = function(id){
        this.newCard.attr('id', this.buildCardId(id));
        this.newCard.attr('data-choir-id', id);
      };

      var buildCardId = function(id){
        return this.cardType + "-" + id;
      };

      var setTitle = function(title){
        this.newCard.find('.title').html(title);
      };

      var renderCard = function(){
        return list.find('ul').append(this.newCard);
      };

      var appendToList = function(){
        return list.find('ul').append(this.newCard);
      };

      var returnCard = function(){
        return this.newCard;
      };

      var removeCard = function(id) {
        cardId = this.buildCardId(id);
        return list.find('ul').find('#'+cardId).remove();
      };

      var refreshCard = function(id) {
        cardId = this.buildCardId(id);
        return list.find('ul').find('#'+cardId).replaceWith(this.newCard);
      };

      return {
        init: init,
        setId: setId,
        setCardType: setCardType,
        setTitle: setTitle,
        renderCard: renderCard,
        appendToList: appendToList,
        returnCard: returnCard,
        removeCard: removeCard,
        buildCardId: buildCardId,
        refreshCard: refreshCard
      };*/

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

    /*var listClass = (function() {

      var itemClass = false;

      var init = function(itemClass, items) {
        this.itemClass = itemClass;
        this.itemClass.init(items);
      };

      var add = function(item) {

        this.itemClass.addItem(item);

        var card = Card;
        card.init();
        card.setCardType('choir');
        card.setId(item.id);
        card.setTitle(item.name);
        card.appendToList();
      };

      var update = function(item_id, item) {
        this.itemClass.updateItem(item_id, item);

        var card = cardClass;
        card.init();
        card.setCardType('choir');
        card.setId(item.id);
        card.setTitle(item.name);
        card.refreshCard(item.id);
      };

      var remove = function(item_id) {
        this.itemClass.removeItem(item_id);

        var card = cardClass;
        card.init();
        card.setCardType('choir');
        cardClass.removeCard(item_id);
      };

      return {
        init: init,
        add: add,
        update: update,
        remove: remove
      };
    })();*/


    //
    // itemClass
    /*var itemClass = (function() {

      var items = [];

      var init = function(items){
        this.items = items;
      };

      var addItem = function(item){
        this.items.push(item);
        this.populateItemCount();
      };

      var updateItem = function(item_id, item){
        this.removeItem(item_id);
        this.addItem(item);
      };

      var removeItem = function(item_id){
        itemIndex = this.items.findIndex(function(element) { return element.id == item_id });

        if(itemIndex !== -1)
        {
          this.items.splice(itemIndex, 1);
          this.populateItemCount();
        }
      };

      var countItems = function(){
        return this.items.length;
      };

      var getItems = function(){
        return this.items;
      };

      var populateItemCount = function(){
        count = this.countItems();
        $('.item-count').html(count);
      }

      return {
        init: init,
        addItem: addItem,
        updateItem: updateItem,
        removeItem: removeItem,
        countItems: countItems,
        getItems: getItems,
        populateItemCount: populateItemCount
      };

    })();*/


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

        var type = $(link).data('resource-type');
        var id = $(link).data('resource-id');

        var request = {
          data: false,
          dataType: 'json',
          method: 'POST',
          url: $(link).attr('href'),
          complete: function(xhr, status) {
            Resource.handleSaveComplete(status);
          },
          success: function(data, status) {
            Resource.handleRemoveSuccess(resourceType, data, status);
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
        Card.getAndRenderCard(type, data);
        List.updateCounter(type);
      };

      var handleRemoveSuccess = function(type, data, status) {
        console.log(type);
        console.log(data);
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


    //var choirItems = itemClass;

    //var choirListClass = listClass;
    //choirListClass.init(itemClass, @php echo json_encode($choirs);@endphp);

    //console.log(choirListClass.itemClass.countItems());

    //
    //

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
    //
    //



    /*$('.add-choir').on('click', function(e) {
      e.preventDefault();
      //choirListClass.add({'id': 4, 'name': 'Queens'});
      //console.log(choirListClass.itemClass.getItems());
      var modalForm = modalFormClass;
      modalForm.init(choirListClass,'choir', 'add');
      modalForm.open();
    });

    $('.remove-choir').on('click', function(e) {
      e.preventDefault();
      var choirId = $(this).parents('.card').data('choir-id');
      choirListClass.remove(choirId);
      console.log(choirListClass.itemClass.getItems());
    });

    $('.edit-choir').on('click', function(e) {
      e.preventDefault();
      var choirId = $(this).parents('.card').data('choir-id');
      choirListClass.update(choirId, {'id': choirId, 'name': 'Kings Edited'});
      console.log(choirListClass.itemClass.getItems());
    });


    $('#modal-cover').on('click', function(e) {
      e.preventDefault();
      var modalForm = modalFormClass;
      modalForm.init(choirListClass, 'choir', 'add');
      modalForm.close();
    });

    $('form').on('submit', function(e) {
      e.preventDefault();
      console.log(e);
      //var modalForm = modalFormClass;
      //modalForm.init(choirListClass, 'choir', 'add');
      //modalForm.save();
    });*/
  });

  </script>
</body>
</html>
