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
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="desc">Description:</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="pdesc"></textarea>
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
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                Projects
                <button class="create-modal btn btn-success btn-sm">+ Add</button>
            </div>
            <div class="card-body">
                <table class="table" id="table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Project Name</th>
                            <th class="text-center">Created At</th>
                            <th class="text-center">Updated At</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                        <tr class="item{{$item->id}}">
                            <td>{{$item->id}}</td>
                            <td>{{$item->project_name}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->updated_at}}</td>
                            <td><button onclick="location.href='{{ url('/project/design/'.$item->id) }}'" class="btn btn-success btn-sm"
                                data-info="{{$item->id}}">
                                <i class="fa fa-magic" aria-hidden="true"></i> Design
                            </button>
                            <button class="edit-modal btn btn-info btn-sm"
                                data-info="{{$item->id}},{{$item->project_name}},{{$item->description}}">
                                <i class="fa fa-pencil" aria-hidden="true"></i> Edit
                            </button>
                            <button class="delete-modal btn btn-danger btn-sm"
                                data-info="{{$item->id}},{{$item->project_name}}">
                                <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
                            </button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
            $('#pdesc').val(stuff[2]);
            $('#editModal').modal('show');
        });

        $(document).on('click', '.create-modal', function() {
            $('#footer_action_button').text(" Create");
            $('.actionBtn').addClass('btn-success');
            $('.actionBtn').removeClass('btn-danger');
            $('.actionBtn').removeClass('delete');
            $('.actionBtn').addClass('create');
            $('#pname').val('');
            $('#pdesc').val('');
            $('.modal-title').text('Create New Project');
            $('.deleteContent').hide();
            $('.form-horizontal').show();
            $('#editModal').modal('show');
        });

        $('.modal-footer').on('click', '.create', function() {
            const Url = @json(route('Project.create'));
            $.ajax({
                type: 'POST',
                url: Url,
                dataType: 'json',
                data: {
                    "project_name": $('#pname').val(),
                    "description": $('#pdesc').val()
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.errors){
                        $('#editModal').modal('show');
                        //add error
                    }else {
                        $('#editModal').modal('hide');
                        window.location = @json(route('Project.list'));
                    }
                }
            });
        });

        $('.modal-footer').on('click', '.edit', function() {
            const Url = @json(route('Project.update'));
            $.ajax({
                type: 'POST',
                url: Url,
                dataType: 'json',
                data: {
                    "id": $(".pid").text(),
                    "project_name": $('#pname').val(),
                    "description": $('#pdesc').val()
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.errors){
                        $('#editModal').modal('show');
                        //add error
                    }else {
                        $('#editModal').modal('hide');
                        window.location = @json(route('Project.list'));
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
                    window.location = @json(route('Project.list'));
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