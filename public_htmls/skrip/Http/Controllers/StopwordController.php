<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Stopword;
use App\Timeline;
use DB;

class StopwordController extends Controller
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
            'stop_word' => 'active',
            );
        $stopword = Stopword::all();

        return view('stopword.stopword',['active' => $active, 'stopword' => $stopword]);
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
            'stop_word' => 'active',
            );

        return view('stopword.create',['active' => $active, 'sub_judul' => 'Tambah stopword']);
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
            'kata' => 'required'
        ]);

        $stopword = Stopword::create([
            'kata' => $request->kata
        ]);

        return redirect('stopword')->with('berhasil',$stopword?"berhasil":"gagal");
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
            'stop_word' => 'active',
            );
        $stopword = Stopword::find($id);

        return view('stopword.show',[
            'active' => $active, 
            'stopword' => $stopword, 
            'sub_judul' => 'Detail stopword'
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
            'stop_word' => 'active',
            );
        $stopword = Stopword::find($id);

        return view('stopword.edit',[
            'active' => $active, 
            'stopword' => $stopword, 
            'sub_judul' => 'Edit stopword'
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
        $stopword = Stopword::find($id);
        $status = $stopword->update($request->all());
        
        return redirect("stopword")->with('berhasil',$status?"berhasil":"gagal");
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
        $status = DB::table('stoplist')->where('kata',$id)->delete();

        return redirect("stopword")->with('berhasil',$status?"berhasil":"gagal");
    }
}
