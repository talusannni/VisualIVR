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
        $( "<div>", { id: "Dcontent"}).appendTo( this.element );
        $( `<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white border-end border-dark">
            <div class="position-sticky">
                <div class="list-group list-group-flush mx-3 mt-4" id="fileManager"></div>
            </div>
        </nav>` ).insertAfter( $( "header" ) );
        $('#root').css('margin-left', '200px');
        $('.navbar').css('margin-left', '200px');
        $('footer').css('margin-left', '200px');
        $( "<div>", { id: "Dcontent", "class": "col-sm-10" }).appendTo( this.element );
        $("#Dcontent").html(`<div id="tabs">
            <!--ul>
                <li><a href="#tabs-1">Form Builder</a></li>
                <li><a href="#tabs-2">Preview</a></li>
            </ul-->
            <div id="tabs-1">
                <div id='builder'></div>
            </div>
            <!--div id="tabs-2">
                <div id="formio">No Preview Available!</div>
            </div-->
            <div id="tabs-3">
                <div id="json" style="display:none"></div>
            </div>
        </div>`);
        
        $( function() {
            $( "#tabs" ).tabs();
        } );
        this._getTemplate();
        this._getList();
    },

    _getTemplate: function() {
        var self = this;
        //get list using listURL
        $.ajax({
            type: 'GET',
            dataType: "json",
            contentType: 'application/json',
            url: "../../project/template/" + $("#pid").val(),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                var stylesheet = $("<link>", {
                    rel: "stylesheet",
                    type: "text/css",
                    href: "../../templates/"+data[0].template_id+".css"
                });
                stylesheet.appendTo("head");
            }
        });
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
                    html += '<a href="#" class="list-group-item list-group-item-action py-2 ripple pageName" aria-current="true" id="'+ value['id'] +'">';
                    if(value['root']){
                        html += "<span class='fa fa-arrow-circle-right'></span>";
                    }
                    html += '<span>'+value['sheet_name']+'</span></a>';
                });
                $('#fileManager').html(html);
                $( ".pageName" ).on( "click", function() {
                    $(".pageName").removeClass("active");
                    $(this).addClass("active");
                    self._getJSON(this);
                });
                self._contextMenu();
            }
        });
        //$("<link>", { rel: "stylesheet", type: "text/css", href: "../../css/formio.full.min.css" }).appendTo("head");
        $('<script>', { type : 'text/javascript', src : "../../js/override.js" }).appendTo('body');
    },

    _getJSON: function(e) {
        if(localStorage.getItem($(".pageName.active").attr("id")) !== null){
            builder.setForm(JSON.parse(localStorage.getItem($(".pageName.active").attr("id"))));
        }else{
            var self = this;
            $.ajax({
                type: 'GET',
                dataType: "json",
                contentType: 'application/json',
                url: "../../sheet/json/" + e.id,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    builder.setForm(JSON.parse(data.json));
                },
                error: function (textStatus, errorThrown) {
                    builder.setForm({components: []});
                }
            });
        }
    },

    _contextMenu: function(){
        var self = this;
        $('#fileManager').append(`<div class='context-menu'>
          <ul>
             <li><span class='Begin'></span>Begin</li>
             <li><span class='Rename'></span>Rename</li>
             <li><span class='Delete'></span>Delete</li>
             <li><span class='Save'></span>Save</li>
          </ul>
        </div>
        <input type='hidden' value='' id='txt_id'>
        <input type='hidden' value='' id='txt_name'>`);

        // disable right click and show custom context menu
        $(".pageName").on('contextmenu', function (e) {          
            var id = this.id;
            $("#txt_id").val(id);
            $("#txt_name").val($(this).text());
  
            var windowHeight = $(window).height()/2;
            var windowWidth = $(window).width()/2;
            if(e.clientY > windowHeight && e.clientX <= windowWidth) {
              var left = e.clientX;
              var top = $(window).height()-e.clientY;
            } else if(e.clientY > windowHeight && e.clientX > windowWidth) {
                var left = $(window).width()-e.clientX;
                var top = $(window).height()-e.clientY;
            } else if(e.clientY <= windowHeight && e.clientX <= windowWidth) {
                var left = e.clientX;
                var top = e.clientY;
            } else {
                var left = $(window).width()-e.clientX;
                var top = e.clientY;
            }

            // Show contextmenu
            $(".context-menu").css({
                top: top + "px",
                left: left + "px",
                display: 'block',
                zIndex: 111
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
            if(className == "Begin"){
                $.ajax({
                    type: 'POST',
                    url: "/sheet/root",
                    data: {
                        "id": $('#txt_id').val(),
                        "project_id": $('#pid').val(),
                        "root": 1
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        if (data.errors){
                            //add error
                        }else {
                            $(".pageName > .fa.fa-arrow-circle-right").remove();
                            $( "#"+ titleid ).prepend( "<span class='fa fa-arrow-circle-right'></span>" );
                        }
                    },
                    error: function (error) {
                        alert(error.message);
                    }
                });
            }else if(className == "Rename"){
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
                      "next": $("#nextPage").val(),
                      "sheet_name": $("#txt_name").val()
                    },
                    headers: {accept: 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  success: function (response) {
                    localStorage.removeItem(titleid);
                  },
                  error: function (error) {
                  
                  }
                });
            }
        });
    } 
});