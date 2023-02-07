@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-3 text-justify ml-auto mr-3" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import User</h5>
                    </div>
                    <form action="/import" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control float-left col-md-9" required>
                        <button class="btn btn-primary float-right ml-1">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <table class="table table-bordered mb-5">
                <thead>
                    <tr class="table-success">
                        <th scope="col">#</th>
                        <th scope="col">管理NO</th>
                        <th scope="col">部署</th>
                        <th scope="col">ふりがな</th>
                        <th scope="col">姓</th>
                        <th scope="col">名</th>
                        <th scope="col">地域</th>
                        <th scope="col">アクション</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $data)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <th scope="row">{{ $data->mgt_no }}</th>
                        <th scope="row">{{ $data->department }}</th>
                        <th scope="row">{{ $data->furigana }}</th>
                        <th scope="row">{{ $data->family_name }}</th>
                        <th scope="row">{{ $data->first_name }}</th>
                        <th scope="row">{{ $data->region }}</th>
                        <th scope="row">
                            <button type="button" class="btn btn-primary" data-toggle="modal" onclick="$('#modal-{{$data->id}}').modal('show')">
                                編集
                            </button>
                        </th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {!! $users->links() !!}
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($users as $user)
            <div class="modal" tabindex="-1" role="dialog" id="modal-{{$user->id}}">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">ユーザーステータスの更新 &nbsp;( {{$user->family_name}} &nbsp; {{$user->first_name}} )</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm" action="/permission" class="row" method="POST">
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                @csrf
                                <div class="col-6 mb-3">
                                    <label class="form-label">Management No</label>
                                    <p>{{$user->mgt_no}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Department Name</label>
                                    <p>{{$user->department}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Furigana</label>
                                    <p>{{$user->furigana}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Family Name</label>
                                    <p>{{$user->family_name}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">First Name</label>
                                    <p>{{$user->first_name}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Region</label>
                                    <p>{{$user->region}}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <fieldset>
                                        <legend>ユーザーステータス</legend>
                                        @foreach ($roles as $role)
                                        <div>
                                                <input type="radio" name="role_id[]" value="{{$role->id}}">
                                                <label>{{$role->name}}</label><br>
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
