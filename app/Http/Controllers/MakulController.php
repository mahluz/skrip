<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Timeline;
use App\Makul;

use DB;

class MakulController extends Controller
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
            'data' => 'active',
            'makul' => 'active',
            );
        
        $mata_kuliah = DB::table('mata_kuliah')
         
		  
           ->select("*")
            ->get();
			
			

        return view('makul.kategori',['active' => $active, 'mata_kuliah' => $mata_kuliah]);
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
            'data' => 'active',
            'makul' => 'active',
            );

        return view('makul.create',['active' => $active, 'sub_judul' => 'Tambah Makul']);
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
           'kode_makul' => 'required|max:255',
           'mata_kuliah'=> 'required|max:255',
            'sks'  => 'required|max:255|',
          
        ]);

        $mata_kuliah = Makul ::create([
            'kode_makul' => $request->kode_makul,
            'mata_kuliah' => $request->mata_kuliah,
			'sks' =>  $request->sks,
          
        ]);

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan penambahan kategori baru',
            'href' => 'makul/'.$mata_kuliah->id_makul
        ]);

        return redirect('makul')->with('berhasil',$mata_kuliah?"berhasil":"gagal");
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
            'data' => 'active',
            'makul' => 'active',
            );

        $mata_kuliah = Makul::find($id);

        return view('makul.edit',[
            'active' => $active, 
            'makul' => $mata_kuliah, 
            'sub_judul' => 'Edit Makul'
            ]);
    }
	
  public function update(Request $request, $id)
    {
        if (Auth::user()->id_role!=1) {
            return redirect('404');
        }
        $mata_kuliah = Makul::find($id);
        $status = $mata_kuliah->update($request->all());
        
        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan perubahan data mata kuliah',
            'href' => 'makul/'.$mata_kuliah->id_makul
        ]);

        return redirect("makul")->with('berhasil',$status?"berhasil":"gagal");
    }	

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

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
        $status = DB::table('mata_kuliah')->where('id_makul',$id)->delete();

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Menghapus data kategori',
            'href' => ''
        ]);

        return redirect("makul")->with('berhasil',$status?"berhasil":"gagal");
    }
}
