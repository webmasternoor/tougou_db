@extends('layouts.admin')

@section('title')- Roles List @endsection

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-2">{{trans('user_management.roles_list')}}</h4>
<!-- roles Table -->
<div class="card">
  <div class="card-datatable table-responsive">
    <table class="datatables-roles table border-top">
      <thead>
        <tr>
          <th></th>
          <th>{{trans('user_management.name')}}</th>
          <th style="text-align: center;">{{trans('user_management.actions')}}</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($roles as $role)
        <tr>
            <td style="width: 10%;">{{ $loop->iteration }}</td>
            <td style="width: 35%;">{{ $role->name }}</td>
            <td style="width: 30%;text-align: center;">
                <button {{ auth()->user()->cannot('read-role') ? 'disabled' : '' }} onclick="viewPermissions('{{ route('find.permission', $role->id) }}' );" class="btn btn-sm btn-primary">{{trans('user_management.permissions')}}</button>
                <button {{ auth()->user()->cannot('edit-role') || $role->organization_id == 0 ? 'disabled' : '' }} onclick="editRole('{{ route('roles.update', $role->id) }}', '{{ route('find.permission', $role->id) }}', '{{ $role->name }}' );" class="btn btn-sm btn-primary">{{trans('user_management.edit')}}</button>
                <button {{ auth()->user()->cannot('delete-role') || $role->organization_id == 0 ? 'disabled' : '' }} onclick="deleteData('{{ route('roles.destroy', $role->id) }}')" class="btn btn-sm btn-danger">{{trans('user_management.delete')}}</button>
            </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<!--/ roles Table -->


<!-- Modal -->
<!-- Add roles Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="text-center mb-4">
          <h3>{{trans("user_role.add_role")}}</h3>
          <p>{{trans("user_role.role_assign")}}</p>
        </div>
        <form action="{{ route('roles.store') }}" method="POST" id="roleAdd" class="row">
          @csrf
          <div class="col-12 mb-3">
            <label class="form-label" for="modalRoleName">{{trans("user_role.role_name")}}</label>
            <input type="text" id="modalRoleName" name="name" class="form-control" placeholder="{{trans("user_role.role_name")}}" autofocus />
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">{{trans("user_role.select_permissions")}}</label>
              <div id="jstree-checkbox"></div>
            </div>
          </div>

          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">{{trans("user_role.create_role")}}</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{trans("user_role.discard")}}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Add Role Modal -->

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="text-center mb-4">
          <h3>{{trans("user_role.edit_role")}}</h3>
          <p>{{trans("user_role.edit_role_requirements")}}</p>
        </div>
        <div class="alert alert-warning" role="alert">
          <h6 class="alert-heading mb-2">{{trans("user_role.warning")}}</h6>
          <p class="mb-0">{{trans("user_role.role_quote")}}</p>
        </div>
        <form id="editRoleForm" class="row" method="POST">
          @method('PUT')
          @csrf
          <div class="col-12 mb-3">
            <label class="form-label" for="editRoleName">{{trans("user_role.role_name")}}</label>
            <input type="text" id="editRoleName" name="name" class="form-control" placeholder="{{trans("user_role.role_name")}}" />
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">{{trans("user_role.select_permissions")}}</label>
              <div id="permission-checkbox"></div>
            </div>
          </div>

          <div class="col-12 text-center demo-vertical-spacing">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">{{trans("user_role.update")}}</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{trans("user_role.discard")}}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- view Permission Modal -->
<div class="modal fade" id="viewPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="text-center mb-4">
          <h3>{{trans("user_role.role_permissions")}}</h3>
        </div>
        <div class="col-12 mb-3">
          <label class="form-label">{{trans("user_role.permissions")}}</label>
            <div id="view-permission"></div>
          </div>
        </div>

        <div class="col-12 text-center demo-vertical-spacing">
          <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">{{trans("user_role.close")}}</button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/jstree/jstree.css') }}" />
@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/jstree/jstree.js') }}"></script>

<script>
    $(document).ready( function () {
      const columns = [
        { "visible": true },
        { "visible": true },
        { "visible": true },
      ];
      initDataTable(
        '.datatables-roles',
        columns,
        [0,1],
        20,
        false,
        '{{trans('user_management.add_role')}}',
        true, // modal or url for add button (true for modal, false for url)
        '#addRoleModal',
        @cannot('add-role') true @endcan
        );
    });
    function editRole(url, permissionURL, name) {
        $('#editRoleModal').modal('show');
        $('#editRoleForm').attr('action', url);
        $('#editRoleName').val(name);
        $("#permission-checkbox").jstree("destroy");
        $("#permission-checkbox").jstree({
          core: {
            'data': {
              'url': function(node) {
                  return permissionURL;
              }
            }
          },
          checkbox : {
            "three_state": false,
            "keep_selected_style" : true
          },
          plugins: ["types", "checkbox", "wholerow"],
        });

    }

    function viewPermissions(permissionURL) {
        $('#viewPermissionModal').modal('show');
        $("#view-permission").jstree("destroy");
        $("#view-permission").jstree({
          core: {
            'data': {
              'url': function(node) {
                  return permissionURL;
              }
            }
          },
          checkbox : {
            "three_state": false,
            "keep_selected_style" : true
          },
          plugins: ["types", "checkbox", "wholerow"],
        });

    }


    $(function() {
      $("#jstree-checkbox").jstree({
        core: {
          data: {!! json_encode($tree, JSON_UNESCAPED_UNICODE) !!}
        },
        checkbox : {
          "three_state": false,
          "keep_selected_style" : true
        },
        plugins: ["types", "checkbox", "wholerow"],
      });

      $("#roleAdd").on('submit', function(e){
        $('input[name="permissions[]"]').remove();
        e.preventDefault();
        var selectedElms = $('#jstree-checkbox').jstree("get_selected", true);
        $.each(selectedElms, function() {
          $('#roleAdd').append('<input type="hidden" name="permissions[]" value="'+this.id+'">');
        });
        $('#roleAdd')[0].submit();
      });

      $("#editRoleForm").on('submit', function(e){
        $('input[name="permissions[]"]').remove();
        e.preventDefault();
        var selectedElms = $('#permission-checkbox').jstree("get_selected", true);
        $.each(selectedElms, function() {
          $('#editRoleForm').append('<input type="hidden" name="permissions[]" value="'+this.id+'">');
        });
        $('#editRoleForm')[0].submit();
      });

    });

</script>

@endsection
