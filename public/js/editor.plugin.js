$.widget( "nmk.duploEditor", {
     // Default options.
     options: {
        listURL: 0,
        createURL: 0,
        jsonURL: 0,
        renameURL: 0,
        deleteURL: 0,
        value: 0,
        // Callbacks
        change: null,
        random: null
    },
    _create: function() {
        this.element.addClass('row');
        $( "<div>", { id: "Dmanager", "class": "col-sm-2 border" }).appendTo( this.element );
        $( "<div>", { id: "Dcontent", "class": "col-sm-10 border" }).appendTo( this.element );
        $("#Dcontent").html(`<div id="tabs">
            <ul>
                <li><a href="#tabs-1">Form Builder</a></li>
                <li><a href="#tabs-2">Preview</a></li>
            </ul>
            <div id="tabs-1">
                <div id='builder'></div>
            </div>
            <div id="tabs-2">
                <div id="formio">No Preview Available!</div>
            </div>
            <div id="tabs-3">
                <div id="json"></div>
            </div>
        </div>`);
        
        $( function() {
            $( "#tabs" ).tabs();
        } );
        this._getList();
    },

    _getList: function() {
        var self = this;
        //get list using listURL
        $.ajax({
            type: 'GET',
            dataType: "json",
            contentType: 'application/json',
            url: this.options.listURL,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                var html = '<button class="create-modal btn btn-success btn-sm">+ Add</button>';
                $.each(data, function (index, value) {
                    html += "<div class='row'><div class='col pageName' id='"+ value['id'] +"'>"+value['sheet_name']+"</div></div>";
                });
                $('#Dmanager').html(html);
                $( ".pageName" ).on( "click", function() {
                    $(".pageName").parent().removeClass("active");
                    $(this).parent().addClass("active");
                    self._getJSON(this);
                });
                self._contextMenu();
            }
        });
        //$("<link>", { rel: "stylesheet", type: "text/css", href: "../../css/formio.full.min.css" }).appendTo("head");
        $('<script>', { type : 'text/javascript', src : "../../js/override.js" }).appendTo('body');
    },

    _getJSON: function(e) {
        $.ajax({
            type: 'GET',
            dataType: "json",
            contentType: 'application/json',
            url: "../../sheet/json/" + e.id,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                formBuilder(data);
                Formio.createForm(document.getElementById('formio'), data);
            },
            error: function (textStatus, errorThrown) {
                Formio.createForm(document.getElementById('formio'), {});
                formBuilder();
            }
        });
    },

    _contextMenu: function(){
        var self = this;
        $('#Dmanager').append(`<div class='context-menu'>
          <ul>
             <li><span class='Rename'></span>Rename</li>
             <li><span class='Delete'></span>Delete</li>
             <li><span class='Save'></span>Save</li>
          </ul>
        </div>
        <input type='hidden' value='' id='txt_id'>
        <input type='hidden' value='' id='txt_name'>
        `);

        // disable right click and show custom context menu
        $(".pageName").on('contextmenu', function (e) {          
            var id = this.id;
            $("#txt_id").val(id);
            $("#txt_name").val($(this).html());
  
            var top = e.pageY+5;
            var left = e.pageX;

            // Show contextmenu
            $(".context-menu").css({
                top: top + "px",
                left: left + "px",
                display: 'block'
            });

            // disable default context menu
            return false;
        });

        $(document).on('contextmenu click',function(){
            $(".context-menu").hide();
        });
        // disable context-menu from custom menu
        $('.context-menu').on('contextmenu',function(){
            return false;
        });
        // Clicked context-menu item
        $('.context-menu li').on('click', function(){
            var className = $(this).find("span:nth-child(1)").attr("class");
            var titleid = $('#txt_id').val();
            $( "#"+ titleid ).css( "background-color", className );
            $(".context-menu").hide();
            if(className == "Rename"){
                $('#footer_action_button').text("Rename");
                $('.actionBtn').addClass('btn-success');
                $('.actionBtn').removeClass('btn-danger');
                $('.actionBtn').removeClass('delete');
                $('.actionBtn').addClass('rename');
                $('#sname').val($("#txt_name").val());
                $('.modal-title').text('Rename Page');
                $('.deleteContent').hide();
                $('.form-horizontal').show();
                $('#createModal').modal('show');
            }else if (className == 'Delete'){
                $('#footer_action_button').text("Delete");
                $('.actionBtn').removeClass('btn-success');
                $('.actionBtn').addClass('btn-danger');
                $('.actionBtn').addClass('delete');
                $('.actionBtn').removeClass('rename');
                $('.sname').html("'" + $("#txt_name").val() + "'");
                $('.modal-title').text('Delete Page');
                $('.deleteContent').show();
                $('.form-horizontal').hide();
                $('#createModal').modal('show');
            }else{
                var jsonElement = $('#json');
                const Url = config.routes.createSheet;
                $.ajax({
                    type: 'POST',
                    url: Url,
                    data: {
                      "form": jsonElement.html(),
                      "id": titleid,
                      "sheet_name": $("#txt_name").val()
                    },
                    headers: {accept: 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  success: function (response) {
                    
                  },
                  error: function (error) {
                  
                  }
                });
            }
        });
    },

    // Create a public method.
    value: function( value ) {
 
        // No value passed, act as a getter.
        if ( value === undefined ) {
 
            return this.options.value;
 
        // Value passed, act as a setter.
        } else {
 
            this.options.value = this._constrain( value );
            var progress = this.options.value + "%";
            this.element.text( progress );
 
        }
 
    },

    // Create a private method.
    _constrain: function( value ) {
 
        if ( value > 100 ) {
            value = 100;
        }
 
        if ( value < 0 ) {
            value = 0;
        }
 
        return value;
    },

    _setOption: function( key, value ) {
        this.options[ key ] = value;
        this._update();
    },

    _update: function() {
        var progress = this.options.value + "%";
        this.element.text( progress );
        if ( this.options.value == 100 ) {
            this._trigger( "complete", null, { value: 100 } );
        }
    }
 
});