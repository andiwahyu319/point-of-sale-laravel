@extends('layouts.hasLogin')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>Account</h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">My Account</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Name</b> <span class="float-right">{{Auth::user()->name}}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <span class="float-right">{{Auth::user()->email}}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-center">
                        <button id="editAccount" class="btn btn-info m-2">Edit Account</button>
                        <button id="editPw" class="btn btn-info m-2">Edit Password</button>
                        @if(Auth::user()->id != 1)
                        <form action="{{ url('account') . '/' . Auth::user()->id }}" method="post">
                            @method("delete")
                            @csrf
                            <input type="submit" class="btn btn-danger m-2" value="Delete Account"
                                onclick="return confirm('Are You Sure ?')">
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::user()->id == 1)
        <div class="col-md-5">
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Account List</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-3">
                        @foreach($users as $key => $user)
                        <li class="list-group-item">{{$user->name}}</li>
                        @endforeach()
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Register New Account</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        @csrf
                        <div class="input-group mb-3">
                            <input placeholder="Full Name" id="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" name="name"
                                value="{{ old('name') }}" required autocomplete="name" autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input placeholder="Email" id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input placeholder="Password" id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="input-group mb-3">
                            <input placeholder="Retype Password" id="password-confirm" type="password"
                                class="form-control" name="password_confirmation" required autocomplete="new-password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8"></div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Add Account</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="modal fade" id="modalAccount" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Account</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" action="{{ url('account') . '/' . Auth::user()->id}}">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="type" value="account">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{Auth::user()->name}}"
                                placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="{{Auth::user()->email}}" placeholder="Enter Email" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save">
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modalPw" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Password</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" action="{{ url('account') . '/' . Auth::user()->id}}">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="type" value="password">
                        <div class="form-group">
                            <label>Old Password</label>
                            <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" id="old_password"
                                placeholder="Enter Old Password" required>
                            @error('old_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="password" class="form-control @error('old_password') is-invalid @enderror" id="password"
                                placeholder="Enter New Password" required>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="password_confirmation" placeholder="Confirm New Password" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save">
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
@endsection

@section('js')
<script>
    $("#editAccount").click(function () {
        $("#modalAccount").modal();
    });
    $("#editPw").click(function () {
        $("#modalPw").modal();
    });

</script>
@endsection
