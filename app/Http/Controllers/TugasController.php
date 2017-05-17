<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;

use App\Http\Requests;
use App\User;
use App\Tugas;
use App\Timeline;
use DB;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id_role!=1) {
            return redirect('404');
        }
        $active = array(
            'tugas' => 'active',
            'mahasiswa' => 'active',
            );
        $tugas = DB::table('tugas as m')
           ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
		   
		   ->leftjoin('mata_kuliah as p', 'm.id_makul', '=', 'p.id_makul')
           ->select("*","m.create_at as upload")
		   ->where('m.id_user',Auth::user()->id_user)
           ->get();
			
			
			
        return view('tugas.tugas',['active' => $active, 'tugas' => $tugas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->id_role!=1) {
            return redirect('404');
        }
         $active = array(
            'tugas' => 'active',
            'mahasiswa' => 'active',
            );
			$mata_kuliah = DB::table('mata_kuliah')->select('*')->get();
			$users = DB::table('users as a')
			->leftjoin('role as b', 'a.id_role' ,'=', 'b.id_role') 
			->select("*")
			->where('a.id_role',2)
            ->get();
        return view('tugas.create', compact('kode_makul', 'mata_kuliah', 'users', 'name'),['active' => $active, 'sub_judul' => 'Input Tugas']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->id_role!=1) {
            return redirect('404');
        }
        $this->validate($request, [
            'nama_tugas' => 'required|max:255',
            'id_makul' => 'required|max:255',
            'no_id' => 'required|max:255|',
            'file' => 'required|max:255',
        ]);

		$path = public_path('upload/file');
        $input = $request->except('file');
        $upload = new Tugas();
        if ($request->hasFile('file')) {
            $upload->nama_tugas = $request->nama_tugas;
			$upload->id_user = Auth::user()->id_user;
			$upload->id_makul = $request->id_makul;
            $upload->no_id = $request->no_id;
            $upload->tugas = $request->file('file')->getClientOriginalName();
			$upload->create_at = date('Y-m-d');
            $request->file('file')->move($path, $upload->tugas);
            if ($upload->save()) {
                return redirect('tugas')->with('berhasil',$upload?"berhasil":"gagal");
            };
        }

       /* Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan penambahan tugas baru',
            'href' => 'tugas/'.$tugas->id_tugas
        ]);
*/
  //      return redirect('tugas')->with('berhasil',$tugas?"berhasil":"gagal");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->id_role!=1) {
            return redirect('404');
        }
        $active = array(
            'pengguna' => 'active',
            'mahasiswa' => 'active',
            );
        $user = User::find($id);

        return view('mahasiswa.show',[
            'active' => $active, 
            'user' => $user, 
            'sub_judul' => 'Detail tugas'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->id_role!=1) {
            return redirect('404');
        }
        $active = array(
            'pengguna' => 'active',
            'mahasiswa' => 'active',
            );

        $user = User::find($id);

        return view('mahasiswa.edit',[
            'active' => $active, 
            'user' => $user, 
            'sub_judul' => 'Edit Tugas'
            ]);
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
        if (Auth::user()->id_role!=1) {
            return redirect('404');
        }
        
        $old = User::find($id);
        if ($request->no_id != $old->no_id) {
            $user = DB::table('users')
                ->where([
                    ['no_id',$request->no_id],
                    ['id_user','!=',$old->id_user]
                    ])
                ->first();
            if ($user) {
                return redirect("mahasiswa/".$id."/edit")->withErrors(['no_id' => 'Nomor pegawai sudah digunakan']);
            }
        }
        
        if(isset($request->password)){
            $this->validate($request, [
                'password' => 'required|min:6|confirmed'
            ]);
        }

        $user = User::find($id);
        $status = $user->update($request->all());
        
        if(isset($request->password)){
            $user->password = bcrypt($request->password);
            $user->save();
        }
        
        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan perubahan data mahasiswa',
            'href' => 'mahasiswa/'.$user->id_user
        ]);

        return redirect("mahasiswa")->with('berhasil',$status?"berhasil":"gagal");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->id_role!=1) {
            return redirect('404');
        }
        $status = DB::table('users')->where('id_user',$id)->delete();

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Menghapus data mahasiswa',
            'href' => ''
        ]);

        return redirect("mahasiswa")->with('berhasil',$status?"berhasil":"gagal");
    }
}
