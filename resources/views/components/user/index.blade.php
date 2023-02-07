@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                {{-- popup import data start--}}
                <div class="import_data">

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <form method="post" action="/import" class="row" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row mx-2">
                                        <div class="col-12">
                                            <p>
                                                Please download the demo template from here and create it according to the format
                                                <a href="{{ asset('assets/demo/users.xlsx') }}" download class="text-info">Click here to download</a>
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <label for="attachment" class="form-label">Attachment</label>
                                            <input class="form-control" type="file" name="attachment" id="attachment">
                                        </div>
                                        <div class="col-12 pt-4">
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                {{-- popup import data end--}}
                <div class="card-header">{{ __('Users Data') }} <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Import
                </button>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="p-3 border rounded bg-light">
                                <div class="col-md-12">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Management Number</th>
                                            <th>Department Name</th>
                                            <th>Furigana</th>
                                            <th>Family Name</th>
                                            <th>First Name</th>
                                            <th>Region</th>
                                            <th>Date of Employment</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="col-md-12 overflow-auto mb-3" style="height:300px;">
                                    <table class="table table-hover">
                                        <tbody>
                                            @foreach ($users as $record)
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->mgt_no }}</td>
                                            <td>{{ $record->department }}</td>
                                            <td>{{ $record->furigana }}</td>
                                            <td>{{ $record->family_name }}</td>
                                            <td>{{ $record->first_name }}</td>
                                            <td>{{ $record->region }}</td>
                                            <td>{{ $record->official_registration_date }}</td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

{{--
                        <table class="table table-bordered data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Management Number</th>
                                    <th>Department Name</th>
                                    <th>Furigana</th>
                                    <th>Family Name</th>
                                    <th>First Name</th>
                                    <th>Region</th>
                                    <th>Date of Employment</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
    {{-- Datatables Script Start --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> --}}
    {{-- Datatables Script End --}}
{{-- <script type="text/javascript">
  $(function () {

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.index') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'mgt_no', name: 'mgt_no'},
            {data: 'department', name: 'department'},
            {data: 'furigana', name: 'furigana'},
            {data: 'family_name', name: 'family_name'},
            {data: 'first_name', name: 'first_name'},
            {data: 'region', name: 'region'},
            {data: 'date_of_employment', name: 'date_of_employment'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

  });
</script> --}}
