<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use App\Models\Permission as PermissionModel;
use App\Http\Requests\Permission\Create;
use App\Http\Requests\Permission\Update;
use App\Crud\Crud;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->crud = new Crud(Permission::class);
        //$this->middleware('permission:Permissions');
    }

    public function index()
    {
        $permissions = Permission::get();
        return view('components.permission.index', compact('permissions'));
    }


    public function findPermission($role_id)
    {
        try {
            $tree = PermissionModel::findPermission($role_id);
            return response()->json($tree);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function store(Create $request)
    {
        try {
            $this->crud->action('insert', $request->only('name', 'parent_id'));
            return back()->with('success', 'Permission created successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Update $request, $id)
    {
        $request->merge(["id" => $id]);
        try {
            $this->crud->action('update', $request->only('id', 'name', 'parent_id'));
            return back()->with('success', 'Permission updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->crud->action('delete', $id);
            return back()->with('success', 'Permission deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
