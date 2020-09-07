@extends('admin.master')

@section('css')
<style>
.permissions input[type='checkbox'],
.permissions .custom-control-label { cursor: pointer }
</style>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        Edit Role
                    </div>
                    <div class="float-right">
                        <a href="{{ route('admin.role.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.role.update', $role->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-3">Role Name <span class="text-danger">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="name" value="{{ $role->name }}" id="name" required class="form-control">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-md-3">Permissions <span class="text-danger">*</span> </label>
                            <div class="col-md-9">
                                <div class="permissions">
                                    <div class="custom-control custom-checkbox mt-1">
                                        <input type="checkbox" id="select-all" class="custom-control-input">
                                        <label class="custom-control-label border px-3" for="select-all">Permission All</label>
                                    </div>

                                    @if ($errors->has('permissions'))
                                        <div class="text-danger mt-2">{{ $errors->first('permissions') }}</div>
                                    @endif

                                    <div class="row">
                                        @foreach ($permissions as $batch => $permission)
                                            <div class="p-batch col-6">
                                                <div class="custom-control custom-checkbox font-weight-bold mt-3 mb-1">
                                                    <input type="checkbox" id="{{ 'b' . $batch }}" class="batch custom-control-input">
                                                    <label class="custom-control-label" for="{{ 'b' . $batch }}">{{ $batch }}</label>
                                                </div>
                                                @foreach ($permission as $item)
                                                    <div class="custom-control custom-checkbox ml-4">
                                                        <input type="checkbox" name="permissions[]" value="{{ $item['id'] }}" {{ in_array($item['id'], $existsPermissions) ? 'checked' : '' }} id="{{ $item['name'] }}" class="custom-control-input">
                                                        <label class="custom-control-label" for="{{ $item['name'] }}">{{ $item['name'] }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9 ml-auto mt-2">
                                <input type="submit" value="Update" class="btn btn-info">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).on('change', '#select-all', function() {
        if ($(this).is(':checked')) {
            $('.permissions input[type=checkbox]').prop('checked', true);
        } else {
            $('.permissions input[type=checkbox]').prop('checked', false);
        }
    });

    $(document).on('change', '.batch', function() {
        var cb = 'input[type=checkbox]';
        if ($(this).is(':checked')) {
            $(this).parents('.p-batch').find(cb).prop('checked', true);
        } else {
            $(this).parents('.p-batch').find(cb).prop('checked', false);
        }
    });

    $('.p-batch input[type=checkbox]').each(function () {
        checkedBatchCheckbox(this);
    });

    $(document).on('change', '.p-batch input[type=checkbox]', function() {
        checkedBatchCheckbox(this);
    });

    function checkedBatchCheckbox(el) {
        if ($(el).is(':checked')) {
            $(el).parents('.p-batch').find('.batch').prop('checked', true);
        } else {
            var cb = 'input[type=checkbox]:checked:not(.batch)';
            var a = $(el).parents('.p-batch').find(cb);
            if (a.length < 1) {
                $(el).parents('.p-batch').find('.batch').prop('checked', false);
            }
        }
    }
</script>
@endsection