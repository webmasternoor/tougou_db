@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="container mt-5">
            <table class="table table-bordered mb-5">
                <thead>
                    <tr class="table-success">
                        <th scope="col">#</th>
                        <th scope="col">レーベル名</th>
                        <th scope="col">アクション</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $data)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <th scope="row">{{ $data->name }}</th>
                        <th scope="row">
                            <button type="button" class="btn btn-primary" data-toggle="modal" onclick="$('#modal-{{$data->id}}').modal('show')">
                                パーミッション
                            </button>
                        </th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $roles->links() !!}
        </div>
    </div>
    <div class="row">
        @foreach($roles as $role)
            <div class="modal" tabindex="-1" role="dialog" id="modal-{{$role->id}}">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Permissions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm" action="/policy" class="row" method="POST">
                                <input type="hidden" name="role_id" value="{{$role->id}}">
                                @csrf
                                <div class="col-6 mb-3">
                                <fieldset>
                                    @foreach ($permission_types as $permission_type)
                                    <div>
                                            <input type="checkbox" name="type_id[]" value="{{$permission_type->id}}">
                                            <label>{{$permission_type->name}}</label><br>
                                    </div>
                                    @endforeach
                                </fieldset>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-secondary">更新</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">キャンセル</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
@endsection
