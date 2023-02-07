@extends('layouts.admin')

@section('title')- Permissions List @endsection

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-2">Permissions List</h4>
<!-- Permission Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-permissions table border-top">
      <thead>
        <tr>
          <th></th>
          <th>Name</th>
          <th style="text-align: center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($permissions as $permission)
        <tr>
            <td style="width: 10%;">{{ $loop->iteration }}</td>
            <td style="width: 35%;">{{ $permission->name }}</td>
            <td style="width: 30%;text-align: center;">
                <button disabled onclick="editPermisson('{{ route('permissions.update', $permission->id) }}', '{{ $permission->name }}', '{{ $permission->parent_id }}' );" class="btn btn-sm btn-primary">Edit</button>
                <button disabled onclick="deleteData('{{ route('permissions.destroy', $permission->id) }}')" class="btn btn-sm btn-danger">Delete</button>
            </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<!--/ Permission Table -->


<!-- Modal -->
<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="text-center mb-4">
          <h3>Add New Permission</h3>
          <p>Permissions you may use and assign to roles.</p>
        </div>
        <form action="{{ route('permissions.store') }}" method="POST" class="row">
          @csrf
          <div class="col-12 mb-3">
            <label class="form-label" for="modalPermissionName">Permission Name</label>
            <input type="text" id="modalPermissionName" name="name" class="form-control" placeholder="Permission Name" autofocus />
          </div>

          <div class="col-12 mb-3">
            <label class="form-label" for="parent_id">Parent</label>
            <select class="form-select" name="parent_id" id="parent_id">
              <option value="">Select Parent</option>
              @foreach ($permissions as $permission)
                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Create Permission</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Discard</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add Permission Modal -->

<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="text-center mb-4">
          <h3>Edit Permission</h3>
          <p>Edit permission as per your requirements.</p>
        </div>
        <div class="alert alert-warning" role="alert">
          <h6 class="alert-heading mb-2">Warning</h6>
          <p class="mb-0">By editing the permission name, you might break the system permissions functionality. Please ensure you're absolutely certain before proceeding.</p>
        </div>
        <form id="editPermissionForm" class="row" method="POST">
          @method('PUT')
          @csrf
          <div class="col-12 mb-3">
            <label class="form-label" for="editPermissionName">Permission Name</label>
            <input type="text" id="editPermissionName" name="name" class="form-control" placeholder="Permission Name" />
          </div>

          <div class="col-12 mb-3">
            <label class="form-label" for="editPermissionParent">Parent</label>
            <select class="form-select" name="parent_id" id="editPermissionParent">
              <option value="">Select Parent</option>
              @foreach ($permissions as $permission)
                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Discard</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready( function () {
      const columns = [
        { "visible": true },
        { "visible": true },
        { "visible": true },
      ];
      initDataTable(
        '.datatables-permissions',
        columns,
        [0,1],
        20,
        false,
        'Add Permission',
        true, // modal or url for add button
        '#addPermissionModal');
    });
    function editPermisson(url, name, parent) {
        $('#editPermissionModal').modal('show');
        $('#editPermissionForm').attr('action', url);
        $('#editPermissionName').val(name);
        $('#editPermissionParent').val(parent);
    }

</script>

@endsection
