@extends('admin.master')

@section('css')

@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="float-left">
            <i class="fas fa-table"></i> Roles
        </div>

        @canAccess('role-create')
        <div class="float-right">
            <a href="{{ route('admin.role.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus-circle"></i> Add New
            </a>
        </div>
        @endcanAccess

    </div>
    <div class="card-body">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Permissions</th>

                @canAccess('role-edit|role-delete')
                <th>Actions</th>
                @endcanAccess

            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $i => $role)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ ucwords(str_replace(['-', '_'], ' ', $role->name)) }}</td>
                    <td>
                        <button data-id="{{ $role->id }}" class="btn btn-success btn-sm btn-permissions">
                            <span class="loader" style="display:none">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span> Permissions
                        </button>
                    </td>

                    @canAccess('role-edit|role-delete')
                    <td>
                        <div class="btn-group">

                            @canAccess('role-edit')
                            <a href="{{ route('admin.role.edit', $role->id) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endcanAccess

                            @canAccess('role-delete')
                            <form action="{{ route('admin.role.destroy', $role->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm ml-2">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            @endcanAccess
                            
                        </div>
                    </td>
                    @endcanAccess

                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    </div>
</div>

<!-- Permissions show modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Permissions</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div id="permissionsContent"></div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
    </div>
    </div>
</div>
</div>
@endsection

@section('js')
<script>
    $('.btn-permissions').click(function() {
        $(this).find('.loader').show();
        $(this).removeClass('btn-success').addClass('btn-danger');
        var roleId = $(this).data('id');
        if ('' != roleId) {
            var route = '{{ route('admin.role.permissions', ':id') }}';
            var url = route.replace(':id', roleId);
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    if (response) {
                        var permissions = response.permissions;
                        var output = '<div class="row">';
                        Object.keys(permissions).map(function(k) {
                            output += '<div class="col-md-6">';
                            output += '<h5>' + k + '</h5>';
                            output += '<ul>';
                            for (var i = 0; i < permissions[k].length; i++) {
                                var el = permissions[k][i];
                                output += '<li>' + el + '</li>';
                            }
                            output += ' </ul></div>';
                        });
                        output += '</div>';
                        $('#permissionsContent').html(output);
                        $(this).removeClass('btn-danger').addClass('btn-success');
                        $(this).find('.loader').hide();
                        $('#permissionsModal').modal('show');
                    }
                }.bind(this),
                error: function (e) {
                    var error = e.status + ' | ' + e.statusText;
                    var message = "<h4 class='text-center text-danger'>"+error+"</h4>";
                    $('#permissionsContent').html(message);
                    $(this).find('.loader').hide();
                    $(this).removeClass('btn-danger').addClass('btn-success');
                    $('#permissionsModal').modal('show');
                }.bind(this)
            })
        }
    })
</script>
@endsection