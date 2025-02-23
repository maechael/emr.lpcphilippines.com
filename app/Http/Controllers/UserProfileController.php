<?php

namespace App\Http\Controllers;


use App\Models\Metadata;
use App\Models\User;
use App\Models\UserProfile;
use App\Policies\UserProfilePolicy;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {

        $accounts = User::all();
        $selectedAccountId = session('selectedAccountId');
        $usedAccounts = UserProfile::pluck('user_id')->toArray();

        $arrSearchArrAccounts = array_search($selectedAccountId, $usedAccounts);
        if ($arrSearchArrAccounts !== false) {
            unset($usedAccounts[$arrSearchArrAccounts]);
        }
        if ($request->ajax()) {
            $userProfiles = UserProfile::orderBy('id', 'desc')->get();
            return DataTables::of($userProfiles)
                ->addColumn('full_name', function ($userProfile) {
                    return '<a href="#" data-toggle="modal" id="fullnameLink" name="fullNameLinkData" data-target="#leadsDataModal" data-id="' . $userProfile->id . '" data-name="' . $userProfile->company_name . '">' . $userProfile->firstname . ' ' . $userProfile->lastname . '</a>';
                })
                ->addColumn('position_name', function ($userProfile) {
                    return $userProfile->position;
                })
                ->addColumn('user_name', function ($userProfile) {
                    return $userProfile->user->name;
                })
                ->addColumn('created_at_formatted', function ($userProfile) {
                    return Carbon::parse($userProfile->created_at)->format('Y-m-d H:i:s');
                })
                ->addColumn('updated_at_formatted', function ($userProfile) {
                    return Carbon::parse($userProfile->updated_at)->format('Y-m-d H:i:s');
                })

                ->addColumn('action', content: function ($userProfile) {
                    $accounts = UserProfile::find($userProfile->id);

                    $editButton = '';
                    $deleteButton = '';
                    if ($accounts) {
                        $editButton = '<button class="edit btn btn-primary btn-sm" id="' . $userProfile->id . '" name="edit"  type="button"><i class="ri-edit-box-line"></i></button>';
                    }
                    if ($accounts) {
                        $deleteButton = '<button class="delete btn btn-danger btn-sm" id="' . $userProfile->id . '" name="delete"  type="button"><i class="ri-delete-bin-line"></i></button>';
                    }
                    $contactInformationButton = '<button class="contact btn btn-info btn-sm" id="' . $userProfile->id . '" name="contact"  type="button"><i class="mdi mdi-contacts"></i> </button>';
                    return $editButton . ' ' . $deleteButton . ' ' . $contactInformationButton;
                })
                ->rawColumns(['full_name', 'action'])
                ->make(true);
            // ->searchable(['full_name', 'american_name', 'id_num', 'position_name', 'is_active', 'department_name', 'user_name']);
        }
        return view('admin.user-profile.index', compact('accounts', 'usedAccounts'));
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'first_name' => 'required|unique:user_profiles,firstname|regex:/^[A-Z][a-z]*/',
                'last_name' => 'required|unique:user_profiles,lastname|regex:/^[A-Z][a-z]*/',

                'id_num' => 'required|unique:user_profiles,id_num',
                'media' => 'required|file|max:2048|mimes:jpeg,png,jpg,gif,svg',
                'account_id' => 'required|unique:user_profiles,user_id'
            ]);

            //code for unique tokenn to prevent double submission
            $token = $request->input('token');
            if (Cache::has($token)) {
                return response()->json([
                    'message' => 'Duplicate submission, please try again'
                ], 422);
            }
            $token = Str::random(10);
            Cache::put($token, true, 10);

            DB::beginTransaction();

            $isComplianceOfficer = $request->input('isComplianceOfficer') == 'on' ? 1 : 0;
            $isSpanish = $request->input('isSpanish') == 'on' ? 1 : 0;

            $file = $request->file('media');
            $basename = $file->getClientOriginalName();
            $directoryPath =  public_path('backend/assets/images/users');
            $type = $file->getClientMimeType();
            $size = $file->getSize();
            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true, true);
            }
            $file->move($directoryPath, $basename);
            $filepath = 'backend/assets/images/users/' . $basename;

            $metadata = new Metadata;
            $metadata->basename = $basename;
            $metadata->filename = $basename;
            $metadata->filepath = $filepath;
            $metadata->type = $type;
            $metadata->size = $size;
            $metadata->save();

            $userProfile = new UserProfile;
            $userProfile->firstname = ucfirst($request->input('first_name'));
            $userProfile->lastname = ucfirst($request->input('last_name'));
            $userProfile->id_num = $request->input('id_num');
            $userProfile->position = $request->input('position');
            $userProfile->is_active = $request->input('is_active');
            $userProfile->user_id = $request->input('account_id');
            $userProfile->media()->associate($metadata->id);
            $userProfile->save();
            DB::commit();

            Cache::forget($token);
        } catch (ValidationException $e) {
            Log::info("Error for creating", [$e->getMessage()]);
            return response()->json([
                'errors' => $e->validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }
    }

    public function edit($id)
    {

        // $usedAccounts = UserProfile::pluck('user_id')->toArray();
        // $userProfile = UserProfile::find($id);

        if (request()->ajax()) {
            $accounts = User::all();
            $accountsSelected = User::find($id);
            $usedAccounts = UserProfile::pluck('user_id')->toArray();
            $data = UserProfile::findorFail($id);
            return response()->json([
                'result' => $data,
                'usedAccounts' => $usedAccounts,
                'accountsSelected' => $accountsSelected,
                'accounts' =>  $accounts,
                'media' => $data->media
            ]);
            return redirect()->route('index')->with('selectedAccountId', $accountsSelected);
        }
    }

    public function update(Request $request)
    {
        //
        $isComplianceOfficer = $request->input('isComplianceOfficer') == 'on' ? 1 : 0;
        $isSpanish = $request->input('isSpanish') == 'on' ? 1 : 0;

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $basename = $file->getClientOriginalName();
            $directoryPath = public_path('backend/assets/images/users');
            $type = $file->getClientMimeType();
            $size = $file->getSize();
            // Create directory if it doesn't exist
            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true, true);
            }

            // Moving the file to your directory
            $file->move($directoryPath, $basename);

            // Complete filepath
            $filepath = 'backend/assets/images/users/' . $basename;

            $metadata = new Metadata;
            $metadata->basename = $basename;
            $metadata->filename = $basename;
            $metadata->filepath = $filepath;
            $metadata->type = $type;
            $metadata->size = $size;
            $metadata->save();

            // Check if the uploaded file is an image
            $imageInfo = getimagesize($filepath);
            if ($imageInfo === false) {

                // File is not an image, so delete the saved file and return an error response
                File::delete($filepath);
                $metadata->delete();
                return response()->json(['error' => 'Uploaded file is not an image.']);
            }

            $userProfile = UserProfile::find($request->hidden_id);
            $userProfile->firstname = ucfirst($request->input('first_name'));
            $userProfile->lastname = ucfirst($request->input('last_name'));
            $userProfile->id_num = $request->input('id_num');
            $userProfile->position = $request->input('position');
            $userProfile->is_active = $request->input('is_active');
            $userProfile->media_id = $metadata->id;
            $userProfile->user_id = $request->input('account_id');
            $userProfile->media()->associate($metadata);
            $userProfile->save();
            return response()->json(['success' => 'Data is successfully updated']);
        } else {
            $userProfile = UserProfile::find($request->hidden_id);
            $userProfile->firstname = ucfirst($request->input('first_name'));
            $userProfile->lastname = ucfirst($request->input('last_name'));
            $userProfile->id_num = $request->input('id_num');
            $userProfile->position = $request->input('position');
            $userProfile->is_active = $request->input('is_active');
            $userProfile->user_id = $request->input('account_id');

            $userProfile->save();
        }
    }

    public function changeStatus() {}

    public function destroy(UserProfile $userProfile)
    {

        $userProfile->delete();
    }
}
