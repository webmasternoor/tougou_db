@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-md-8">
        <div class="mt-5">
            <table class="table table-bordered mb-5">
                <thead>
                    <tr class="table-success">
                        <th scope="col">#</th>
                        <th scope="col">内容</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permission_types as $data)
                    <tr>
                        <td scope="row">{{ $loop->iteration }}</td>
                        <td scope="row">{{ $data->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $permission_types->links() !!}
        </div>
    </div>
</div>

@endsection

@section('scripts')
@endsection
