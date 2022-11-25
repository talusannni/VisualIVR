<x-layout>
    <div id="app">
        <div id="editModal" class="modal fade" role="dialog">
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
                                    <input type="text" class="form-control" id="pname" required>
                                </div>
                            </div>
                        </form>
                        <div class="deleteContent">
                            Are you Sure you want to delete <span class="pname"></span> ? <span
                                class="d-none pid"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn actionBtn" data-dismiss="modal">
                                <span id="footer_action_button" class='glyphicon'> </span>
                            </button>
                            <button type="button" class="btn btn-warning close" data-dismiss="modal">
                                <span class='glyphicon glyphicon-remove'></span> Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container py-4">
            <div><h1>Pages</h1></div>
            <div id="duploEditor"></div>
        </div>
    </div>

    <script>
        $(document).ready( function () {
            $('#table').DataTable();
        } );

        $(document).on('click', '.edit-modal', function() {
            $('#footer_action_button').text(" Update");
            $('.actionBtn').addClass('btn-success');
            $('.actionBtn').removeClass('btn-danger');
            $('.actionBtn').removeClass('delete');
            $('.actionBtn').addClass('edit');
            $('.modal-title').text('Edit Project');
            $('.deleteContent').hide();
            $('.form-horizontal').show();
            var stuff = $(this).data('info').split(',');
            $('.pid').text(stuff[0]);
            $('#pname').val(stuff[1]);
            $('#editModal').modal('show');
        });

        $(document).on('click', '.create-modal', function() {
            $('#footer_action_button').text(" Create");
            $('.actionBtn').addClass('btn-success');
            $('.actionBtn').removeClass('btn-danger');
            $('.actionBtn').removeClass('delete');
            $('.actionBtn').addClass('create');
            $('#pname').val('');
            $('.modal-title').text('Create New Project');
            $('.deleteContent').hide();
            $('.form-horizontal').show();
            $('#editModal').modal('show');
        });
        var bar = $( "#duploEditor" ).duploEditor({
            //listURL: @json(route('Sheet.list', ['project_id' => $(".pid").text()]))
        });
        //bar.duploEditor( "value", 50 );
        $('.modal-footer').on('click', '.edit', function() {
            const Url = @json(route('Project.update'));
            $.ajax({
                type: 'POST',
                url: Url,
                dataType: 'json',
                data: {
                    "id": $(".pid").text(),
                    "project_name": $('#pname').val()
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.errors){
                        $('#editModal').modal('show');
                        //add error
                    }else {
                        $('#editModal').modal('hide');
                    }
                }
            });
        });

        $('.modal-footer').on('click', '.delete', function() {
            const Url = @json(route('Project.delete'));
            $.ajax({
                type: 'POST',
                url: Url,
                data: {
                    'id': $('.pid').text()
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    $('#editModal').modal('hide');
                }
            });
        });

        $(document).on('click', '.delete-modal', function() {
            $('#footer_action_button').text(" Delete");
            $('.actionBtn').removeClass('btn-success');
            $('.actionBtn').addClass('btn-danger');
            $('.actionBtn').addClass('delete');
            $('.modal-title').text('Delete');
            var stuff = $(this).data('info').split(',');
            $('.pid').text(stuff[0]);
            $('.deleteContent').show();
            $('.form-horizontal').hide();
            $('.pname').html(stuff[1]);
            $('#editModal').modal('show');
        });

        $(document).on('click', '.close', function() {
            $('#editModal').modal('hide');
        });
    </script>
</x-layout>