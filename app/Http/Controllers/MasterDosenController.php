<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class MasterDosenController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read_dosen')->only('index', 'show');
        $this->middleware('permission:create_dosen')->only('create', 'store');
        $this->middleware('permission:update_dosen')->only('edit', 'update');
        $this->middleware('permission:delete_dosen')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dosen = Dosen::all();
        return view('dosen.index', compact('dosen'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('dosen.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email:rfc|unique:dosen',
            'role' => 'nullable',
            'verified' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            toastr()->error('Perngguna gagal ditambah </br> Periksa kembali data anda');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        };
        try {
            $data = Dosen::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'email_verified_at' => !blank($request->verified) ? now() : null
                ]
            );
            $data->assignRole(!blank($request->role) ? $request->role : array());
            toastr()->success('Pengguna baru berhasil disimpan');
            return redirect()->route('dosen-master.index');
        } catch (\Throwable $th) {
            toastr()->warning('Terdapat masalah diserver');
            return redirect()->route('dosen-master.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::all();
        $dosen = Dosen::findorfail($id);
        return view('dosen.edit', compact('dosen', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email:rfc',
            'role' => 'nullable',
            'verified' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            toastr()->error('Perngguna gagal ditambah </br> Periksa kembali data anda');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        };

        try {
            $dosen = Dosen::findorfail($id);

            $update_data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => !blank($request->verified) ? now() : null
            ];
            if(empty($request->password)){
                unset($update_data['password']);
            }
            $dosen->update($update_data);

            $dosen->syncRoles(!blank($request->role) ? $request->role : array());
            toastr()->success('Pengguna berhasil diperbarui');
            return redirect()->route('dosen-master.index');
        } catch (\Throwable $th) {
            toastr()->warning('Terdapat masalah diserver');
            return redirect()->route('dosen-master.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
