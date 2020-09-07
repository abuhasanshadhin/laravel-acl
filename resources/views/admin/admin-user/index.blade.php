@extends('admin.master')

@section('css')
<style>
.badge {
    font-size: 90%;
    font-weight: 600;
}
</style>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="float-left">
            <i class="fas fa-table"></i> Admin Users
        </div>

        @canAccess('admin-create')
        <div class="float-right">
            <a href="{{ route('admin.admin.create') }}" class="btn btn-primary btn-sm">
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
                <th>Username</th>
                <th>Name</th>
                <th>Phone</th>
                <th>E-Mail</th>
                <th>Active</th>
                <th>Roles</th>

                @canAccess('admin-edit|admin-delete')
                <th>Actions</th>
                @endcanAccess

            </tr>
        </thead>
        <tbody>
            @foreach ($adminUsers as $i => $adminUser)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $adminUser->username }}</td>
                    <td>{{ ucwords($adminUser->name) }}</td>
                    <td>{{ $adminUser->phone }}</td>
                    <td>{{ $adminUser->email }}</td>
                    <td>
                        @if ($adminUser->is_active)
                            <div class="badge badge-success">Yes</div>
                        @else
                            <div class="badge badge-danger">No</div>
                        @endif
                    </td>
                    <td>
                        @foreach ($adminUser->roles as $role)
                            <div class="badge badge-primary">
                                {{ ucwords(str_replace(['-', '_'], ' ', $role->name)) }}
                            </div>
                        @endforeach
                    </td>

                    @canAccess('admin-edit|admin-delete')
                    <td>
                        <div class="btn-group">

                            @canAccess('admin-edit')
                            <a href="{{ route('admin.admin.edit', $adminUser->id) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endcanAccess

                            @canAccess('admin-delete')
                            <form action="{{ route('admin.admin.destroy', $adminUser->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm ml-1">
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

@endsection

@section('js')

@endsection