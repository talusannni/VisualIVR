<x-layout>
    <!--script src="{{ asset("js/formio-pretty-checkboxes.min.js")}}"></script-->
    <style>
        /* Context menu */
        .context-menu{
            display: none;
            position: absolute;
            border: 1px solid black;
            width: 100px;
            background: white;
        }

        .context-menu ul{
            list-style: none;
            padding: 2px;
            margin-block-end: 0px !important;
        }

        .context-menu ul li{
            padding: 3px 2px;
        }

        .context-menu ul li:hover{
            cursor: pointer;
            background-color: #eee;
        }

        /* Sidebar */
        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            overflow-x: hidden;
            transition: 0.5s;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                width: 100%;
            }
        }
    </style>
    <div id="createModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="name">Name:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="sname" required autofocus>
                            </div>
                        </div>
                    </form>
                    <div class="deleteContent">
                        Are you Sure you want to delete <span class="sname"></span> ? <span
                            class="d-none pid"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn actionBtn" data-dismiss="modal">
                            <span id="footer_action_button" class='glyphicon'></span>
                        </button>
                        <button type="button" class="btn btn-warning close" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/formio.full.min.css') }}">
    <script>
        $(document).ready( function () {
            var bar = $( "#duploEditor" ).duploEditor({
                listURL: @json(route('Sheet.list', ['project_id' => $project_id])),
                jsonURL: @json(route('Sheet.json', ['sheet' => 0]))
            });
        });
        $(document).on('click', '.create-modal', function() {
            $('#footer_action_button').text("Create");
            $('.actionBtn').addClass('btn-success');
            $('.actionBtn').removeClass('btn-danger');
            $('.actionBtn').removeClass('delete');
            $('.actionBtn').addClass('create');
            $('#sname').val('');
            $('.modal-title').text('Create New Page');
            $('.deleteContent').hide();
            $('.form-horizontal').show();
            $('#createModal').modal('show');
        });

        $(document).on('click', '.close', function() {
            $('#createModal').modal('hide');
        });

        $('.modal-footer').on('click', '.create', function() {
            const Url = @json(route('Sheet.create'));
            $.ajax({
                type: 'POST',
                url: Url,
                data: {
                    "name": $('#sname').val(),
                    "project_id": {{ $project_id }}
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.errors){
                        $('#createModal').modal('show');
                        //add error
                    }else {
                        $('#createModal').modal('hide');
                        $('#fileManager').append('<a href="#" class="list-group-item list-group-item-action py-2 ripple pageName" aria-current="true" id="'+ data.id +'">'+$('#sname').val()+'</a>');
                    }
                },
                error: function (error) {
                    alert(error.message);
                }
            });
        });

        $('.modal-footer').on('click', '.rename', function() {
            const Url = @json(route('Sheet.update'));
            $.ajax({
                type: 'POST',
                url: Url,
                data: {
                    "sheet_name": $('#sname').val(),
                    "id": $('#txt_id').val()
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.errors){
                        $('#createModal').modal('show');
                        //add error
                    }else {
                        $('#createModal').modal('hide');
                        $("#"+$('#txt_id').val()).html($('#sname').val());
                    }
                },
                error: function (error) {
                    alert(error.message);
                }
            });
        });

        $('.modal-footer').on('click', '.delete', function() {
            const Url = @json(route('Sheet.delete'));
            $.ajax({
                type: 'POST',
                url: Url,
                data: {
                    "id": $('#txt_id').val()
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.errors){
                        $('#createModal').modal('show');
                        //add error
                    }else {
                        $('#createModal').modal('hide');
                        $("#"+$('#txt_id').val()).remove();
                    }
                },
                error: function (request, status, error) {
                    alert(request.responseText);
                }
            });
        });
    </script>
    <div id="duploEditor"></div>
    <!--script src='{{ asset('js/override.js') }}'></script-->
</x-layout>