@extends('admin.master')

@section('css')

@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        Edit Admin User
                    </div>
                    <div class="float-right">
                        <a href="{{ route('admin.admin.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.admin.update', $admin->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="username" class="col-md-3">Username <span class="text-danger">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="username" value="{{ $admin->username }}" id="username" required class="form-control">
                                @if ($errors->has('username'))
                                    <span class="text-danger">{{ $errors->first('username') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3">Full Name <span class="text-danger">*</span> </label>
                            <div class="col-md-9">
                                <input type="text" name="name" value="{{ $admin->name }}" id="name" required class="form-control">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-md-3">Phone Number</label>
                            <div class="col-md-9">
                                <input type="text" name="phone" value="{{ $admin->phone }}" id="phone" class="form-control">
                                @if ($errors->has('phone'))
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-3">E-Mail Address</label>
                            <div class="col-md-9">
                                <input type="email" name="email" value="{{ $admin->email }}" id="email" class="form-control">
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-3">Password</label>
                            <div class="col-md-9">
                                <input type="text" name="password" value="{{ old('password') }}" id="password" class="form-control">
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-3">Roles <span class="text-danger">*</span> </label>
                            <div class="col-md-9">
                                @foreach ($roles as $role)
                                    <div class="custom-control custom-checkbox d-inline mr-2">
                                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, $admin->roles->pluck('id')->toArray() ?? []) ? 'checked' : '' }} id="{{ $role->name }}" class="batch custom-control-input">
                                        <label class="custom-control-label" for="{{ $role->name }}">{{ ucwords(str_replace(['-', '_'], ' ', $role->name)) }}</label>
                                    </div>
                                @endforeach
                                @if ($errors->has('roles'))
                                    <div class="text-danger">{{ $errors->first('roles') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-3">Active Status <span class="text-danger">*</span> </label>
                            <div class="col-md-9">
                                <div class="custom-control custom-radio d-inline mr-2">
                                    <input type="radio" name="is_active" value="1" id="active_yes" {{ $admin->is_active ? 'checked' : '' }} class="batch custom-control-input">
                                    <label class="custom-control-label" for="active_yes">Yes</label>
                                </div>
                                <div class="custom-control custom-radio d-inline">
                                    <input type="radio" name="is_active" value="0" id="active_no" {{ !$admin->is_active ? 'checked' : '' }} class="batch custom-control-input">
                                    <label class="custom-control-label" for="active_no">No</label>
                                </div>
                                @if ($errors->has('is_active'))
                                    <div class="text-danger">{{ $errors->first('is_active') }}</div>
                                @endif
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

@endsection