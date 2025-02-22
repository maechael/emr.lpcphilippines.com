<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Policies\PermissionPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view', Permission::find(1));
        if ($request->ajax()) {
            $data = Permission::select('id', 'name', 'created_at', 'updated_at')->get();
            $data->map(function ($item) {
                $item->created_at_formatted = Carbon::parse($item->created_at)->format('Y-m-d H:i:s');
                $item->updated_at_formatted = Carbon::parse($item->updated_at)->format('Y-m-d H:i:s');
                return $item;
            });

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $permission = Permission::find($data->id);
                    $policy = resolve(PermissionPolicy::class);
                    $editButton = '';
                    $deleteButton = '';
                    if ($policy->update(auth()->user(), $permission)) {
                        $editButton = '<button class="edit btn btn-primary btn-sm" id="' . $data->id . '" name="edit"  type="button"><i class="ri-edit-box-line"></i></button>';
                    }
                    if ($policy->delete(auth()->user(), $permission)) {
                        $deleteButton = '<button class="delete btn btn-danger btn-sm" id="' . $data->id . '" name="delete"  type="button"><i class="ri-delete-bin-line"></i></button>';
                    }
                    return $editButton . ' ' . $deleteButton;
                })
                ->make(true);
        }

        return view('admin.permission.index');
    }

    public function store(Request $request)
    {
        $permission = Permission::create($request->validate([
            'name' => ['required', 'min:3'],
        ]));
    }


    public function edit($id)
    {
        if (Request()->ajax()) {
            $data = Permission::findorFail($id);
            return response()->json(['result' => $data]);
        }
    }


    public function destroy(Permission $permission)
    {
        $permission->delete();
    }


    public function update(Request $request)
    {
        // Log::info('store() called');
        $validation = array(
            'name' => 'required',
        );
        $error = Validator::make($request->all(), $validation);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name' => $request->name
        );

        Permission::whereId($request->hidden_id)->update($form_data);
    }

    public function ajaxUpdate(Request $request, Permission $permission)
    {
        Log::info('ajaxUpdate() called');
    }
}
