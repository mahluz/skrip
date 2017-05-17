<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Timeline;
use App\Kategori;
use DB;

class KategoriController extends Controller
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
            'master' => 'active',
            'kategori' => 'active',
            );
        
        $kategori = Kategori::all();

        return view('kategori.kategori',['active' => $active, 'kategori' => $kategori]);
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
            'master' => 'active',
            'kategori' => 'active',
            );

        return view('kategori.create',['active' => $active, 'sub_judul' => 'Tambah kategori']);
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
            'kategori' => 'required'
        ]);

        $kategori = Kategori::create([
            'kategori' => $request->kategori
        ]);

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan penambahan kategori baru',
            'href' => 'kategori/'.$kategori->id_kategori
        ]);

        return redirect('kategori')->with('berhasil',$kategori?"berhasil":"gagal");
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
            'master' => 'active',
            'kategori' => 'active',
            );

        $kategori = Kategori::find($id);

        return view('kategori.show',[
            'active' => $active, 
            'kategori' => $kategori, 
            'sub_judul' => 'Detail kategori'
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
            'master' => 'active',
            'kategori' => 'active',
            );

        $kategori = Kategori::find($id);

        return view('kategori.edit',[
            'active' => $active, 
            'kategori' => $kategori, 
            'sub_judul' => 'Edit kategori'
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
        $kategori = Kategori::find($id);
        $status = $kategori->update($request->all());
        
        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan perubahan data kategori',
            'href' => 'kategori/'.$kategori->id_kategori
        ]);

        return redirect("kategori")->with('berhasil',$status?"berhasil":"gagal");
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
        $status = DB::table('kategori')->where('id_kategori',$id)->delete();

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Menghapus data kategori',
            'href' => ''
        ]);

        return redirect("kategori")->with('berhasil',$status?"berhasil":"gagal");
    }
}
