<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\User;
use App\Timeline;
use DB;

class EditorController extends Controller
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
            'pengguna' => 'active',
            'editor' => 'active',
            );
        $users = DB::table('users')
            ->where('id_role',2)
            ->get();
        
        foreach($users as $user){
            $user->utama = DB::table('editor_makalah')
                ->where([
                    ['id_user',$user->id_user],
                    ['id_jenis_editor',1]
                ])
                ->count();

            $user->pengembang = DB::table('editor_makalah')
                ->where([
                    ['id_user',$user->id_user],
                    ['id_jenis_editor',2]
                ])
                ->count();
        }

        return view('editor.editor',['active' => $active, 'users' => $users]);
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
            'pengguna' => 'active',
            'editor' => 'active',
            );

        return view('editor.create',['active' => $active, 'sub_judul' => 'Tambah editor']);
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
            'name' => 'required|max:255',
            'no_id' => 'required|min:6|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'no_id' => $request->no_id,
            'email' => $request->email,
            'id_role' => 2,
            'password' => bcrypt($request->password),
            'image' => 'dist/img/noprofile.gif',
            'status' => 1
        ]);

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan penambahan editor baru',
            'href' => 'editor/'.$user->id_user
        ]);

        return redirect('editor')->with('berhasil',$user?"berhasil":"gagal");
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
            'editor' => 'active',
            );
        $user = User::find($id);

        $editor1 = DB::table('editor_makalah as em')
            ->leftjoin('makalah as m','em.id_makalah','=','m.id_makalah')
            ->leftjoin('users as u','m.id_user','=','u.id_user')
            ->where([
                ['em.id_user',$id],
                ['em.id_jenis_editor',1]
            ])
            ->get();

        $editor2 = DB::table('editor_makalah as em')
            ->leftjoin('makalah as m','em.id_makalah','=','m.id_makalah')
            ->leftjoin('users as u','m.id_user','=','u.id_user')
            ->where([
                ['em.id_user',$id],
                ['em.id_jenis_editor',2]
            ])
            ->get();

        return view('editor.show',[
            'active' => $active, 
            'user' => $user, 
            'editor1' => $editor1,
            'editor2' => $editor2,
            'sub_judul' => 'Detail editor'
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
            'editor' => 'active',
            );

        $user = User::find($id);

        return view('editor.edit',[
            'active' => $active, 
            'user' => $user, 
            'sub_judul' => 'Edit editor'
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
                return redirect("editor/".$id."/edit")->withErrors(['no_id' => 'Nomor pegawai sudah digunakan']);
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
            'aksi' => 'Melakukan perubahan data editor',
            'href' => 'editor/'.$user->id_user
        ]);
        
        return redirect("editor")->with('berhasil',$status?"berhasil":"gagal");
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
            'aksi' => 'Menghapus data editor',
            'href' => ''
        ]);

        return redirect("editor")->with('berhasil',$status?"berhasil":"gagal");
    }
}
