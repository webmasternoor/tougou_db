@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Tougo Users Data') }} <a style="float: right" class="btn btn-primary" href="{{ route('register') }}">{{ __('Add New') }}</button></a> </div>

                <div class="card-body">
                    <div class="container">
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    {{-- <th>No</th> --}}
                                    <th>Email</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Last Name Kana</th>
                                    <th>First Name Kana</th>
                                    <th>Birthdate</th>
                                    <th>Zip</th>
                                    <th>Address1</th>
                                    <th>Address2</th>
                                    <th>Line ID</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
    {{-- Datatables Script Start --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    {{-- Datatables Script End --}}
<script type="text/javascript">
  $(function () {

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('remoteusers.index') }}",
        columns: [
            // {data: 'id', name: 'id'},
            {data: 'email', name: 'email'},
            {data: 'lastname', name: 'lastname'},
            {data: 'firstname', name: 'firstname'},
            {data: 'lastname_kana', name: 'lastname_kana'},
            {data: 'firstname_kana', name: 'firstname_kana'},
            {data: 'birthdate', name: 'birthdate'},
            {data: 'zip', name: 'zip'},
            {data: 'address1', name: 'address1'},
            {data: 'address2', name: 'address2'},
            {data: 'line_id', name: 'line_id'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

  });
</script>
