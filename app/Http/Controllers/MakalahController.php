<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Requests;
use App\Makalah;
use App\Kategori;
use App\Timeline;
use App\Libraries\Plagiarisme;
use HTML2PDF;
use DB;
use PDO;
use DateTime;

class MakalahController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            'semua' => 'active',
            );
        $makalah = null;
        if(Auth::user()->id_role==1){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->select("*","m.created_at as upload")
                ->get();
        }
        else if(Auth::user()->id_role==2){
            $makalah = DB::table('makalah as m')
                ->join('dosen_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->select("*","m.created_at as upload")
                ->where('e.id_user',Auth::user()->id_user)
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->select("*","m.created_at as upload")
                ->where([
                    ['m.id_user','!=',Auth::user()->id_user],
                    ['m.id_status','!=',0]
                ])
                ->orWhere('m.id_user',Auth::user()->id_user)
                ->get();
        }

        foreach ($makalah as $mak) {
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $kat = DB::table('kategori_makalah as km')
                ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
                ->where('km.id_makalah',$mak->id_makalah)
                ->get();
            $mak->kategori = implode(",", array_column($kat,'kategori'));

            $dosen = DB::table('dosen_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_dosen')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            if ($dosen) {
                $ed = array_column($dosen,'name');
                $mak->dosen1 = $ed[0];
                $mak->dosen2 = $ed[1];
            }
            else{
                $mak->dosen1 = '';
                $mak->dosen2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        return view('makalah.makalah',['active' => $active, 'sub_judul' => 'Semua makalah', 'makalah' => $makalah]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->id_role==2){
            return redirect('404');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            );

        return view('makalah.create',['active' => $active, 'sub_judul' => 'Tambah makalah']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->id_role==2){
            return redirect('404');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $this->validate($request, [
            'kategori_makalah' => 'required',
            'judul' => 'required',
            'abstrak' => 'required',
            'permasalahan' => 'required',
            'tujuan' => 'required',
            'tinjauan_pustaka' => 'required',
            'kesimpulan_sementara' => 'required',
            'daftar_pustaka' => 'required'
        ]);

        $makalah = Makalah::create([
            'judul' => $request->judul,
            'abstrak' => $request->abstrak,
            'permasalahan' => $request->permasalahan,
            'tujuan' => $request->tujuan,
            'tinjauan_pustaka' => $request->tinjauan_pustaka,
            'kesimpulan_sementara' => $request->kesimpulan_sementara,
            'daftar_pustaka' => $request->daftar_pustaka,
            'id_user' => Auth::user()->id_user,
            'id_status' => '0'
        ]);

        foreach ($request->kategori_makalah as $value) {
            $save = DB::table('kategori_makalah')->insert([
                'id_makalah' => $makalah->id_makalah,
                'id_kategori' => $value,
            ]);
        }

        $plag = new Plagiarisme();
        $set = DB::table('pengaturan')->first();
        $forms = explode(",", $set->form);

        $listmak = DB::table('makalah')
            ->where([
                ['id_status','!=',0],
                ['id_makalah','!=',$makalah->id_makalah]
            ])
            ->get();

        $waktu = date("Y-m-d H:i:s");
        $makalah->id_status = 1;
        foreach ($listmak as $mk) {
            foreach ($forms as $form) {
                $percen = $plag->detection($makalah->$form,$mk->$form);
                if($percen>=$set->persen){
                    DB::table('history_plagiat')->insert([
                        'id_makalah_uji' => $makalah->id_makalah,
                        'id_makalah_ref' => $mk->id_makalah,
                        'form' => $form,
                        'persentase' => $percen,
                        'waktu' => $waktu
                    ]);
                    $makalah->id_status = 0;
                }
            }
        }

        $makalah->save();
        $status=true;

        if($makalah->id_status==0){

            Timeline::create([
                'id_user' => Auth::user()->id_user,
                'aksi' => 'Melakukan penambahan makalah baru, namun terdeteksi plagiat!',
                'href' => 'makalah/'.$makalah->id_makalah
            ]);

            return redirect('makalah/'.$makalah->id_makalah)->with('plagiat',true);
        }
        else if($makalah->id_status==1){

            Timeline::create([
                'id_user' => Auth::user()->id_user,
                'aksi' => 'Melakukan penambahan makalah baru dan Lolos plagiat!',
                'href' => 'makalah/'.$makalah->id_makalah
            ]);

            return redirect('lolos')->with('berhasil',$status?"berhasil":"gagal");
        }
    }

    public function compare($id)
    {
        if(Auth::user()->id_role==2){
            return redirect('404');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            );
        $detail = DB::table('history_plagiat')
            ->where('id_plagiat',$id)
            ->first();
        
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $history_plagiat = DB::table('history_plagiat')
            ->where([
                ['id_makalah_uji',$detail->id_makalah_uji],
                ['id_makalah_ref',$detail->id_makalah_ref],
                ['waktu',$detail->waktu]
                ])
            ->get();
        $persentase = array_column($history_plagiat,'persentase','form');
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        
        $mak1 = DB::table('makalah as m')
            ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
            ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
            ->where('m.id_makalah', $detail->id_makalah_uji)
            ->first();
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $kat = DB::table('kategori_makalah as km')
            ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
            ->where('km.id_makalah',$mak1->id_makalah)
            ->get();
        $mak1->kategori = array_column($kat,'kategori');
        $mak1->id_kategori = array_column($kat,'id_kategori');

        $dosen = DB::table('dosen_makalah as e')
            ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
            ->orderBy('e.id_jenis_dosen')
            ->where('e.id_makalah',$mak1->id_makalah)
            ->get();

        if ($dosen) {
            $ed = array_column($dosen,'name');
            $mak1->dosen1 = $ed[0];
            $mak1->dosen2 = $ed[1];
        }
        else{
            $mak1->dosen1 = '';
            $mak1->dosen2 = '';
        }

        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        $mak2 = DB::table('makalah as m')
            ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
            ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
            ->where('m.id_makalah', $detail->id_makalah_ref)
            ->first();

        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $kat = DB::table('kategori_makalah as km')
            ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
            ->where('km.id_makalah',$mak2->id_makalah)
            ->get();
        $mak2->kategori = array_column($kat,'kategori');
        $mak2->id_kategori = array_column($kat,'id_kategori');

        $dosen = DB::table('dosen_makalah as e')
            ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
            ->orderBy('e.id_jenis_dosen')
            ->where('e.id_makalah',$mak2->id_makalah)
            ->get();

        if ($dosen) {
            $ed = array_column($dosen,'name');
            $mak2->dosen1 = $ed[0];
            $mak2->dosen2 = $ed[1];
        }
        else{
            $mak2->dosen1 = '';
            $mak2->dosen2 = '';
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        return view('makalah.compare',[
            'active' => $active, 
            'sub_judul' => 'Perbandingan makalah', 
            'makalah1' => $mak1, 
            'makalah2' => $mak2, 
            'persentase' => $persentase
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->id_role==2) {
            $count = DB::table('dosen_makalah')
                ->where([
                    ['id_makalah',$id],
                    ['id_user', Auth::user()->id_user]
                ])
                ->count();
            if(!($count>0)){
                return redirect('404');
            }
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            );
        
        $mak = DB::table('makalah as m')
            ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
            ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
            ->where('m.id_makalah', $id)
            ->first();
        if ($mak->id_status==0 && $mak->id_user!=Auth::user()->id_user && Auth::user()->id_role!=1) {
            return redirect('404');
        }
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $kat = DB::table('kategori_makalah as km')
            ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
            ->where('km.id_makalah',$mak->id_makalah)
            ->get();
        $mak->kategori = array_column($kat,'kategori');
        $mak->id_kategori = array_column($kat,'id_kategori');

        $dosen = DB::table('dosen_makalah as e')
            ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
            ->orderBy('e.id_jenis_dosen')
            ->where('e.id_makalah',$mak->id_makalah)
            ->get();

        if ($dosen) {
            $ed = array_column($dosen,'name');
            $mak->dosen1 = $ed[0];
            $mak->dosen2 = $ed[1];
        }
        else{
            $mak->dosen1 = '';
            $mak->dosen2 = '';
        }

        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        $history_plagiat = DB::table('history_plagiat as h')
            ->leftjoin('makalah as m', 'h.id_makalah_ref','=','m.id_makalah')
            ->where('h.id_makalah_uji',$mak->id_makalah)
            ->orderBy('h.waktu','desc')
            ->get();

        $km_read=null;
        $km_unread=null;
        $newmessage=null;
        if (Auth::user()->id_role==1) {
            $km_read = DB::table('komentar_makalah as k')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['k.id_makalah',$mak->id_makalah],
                    ['k.readby','like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $km_unread = DB::table('komentar_makalah as k')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['k.id_makalah',$mak->id_makalah],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $newmessage = DB::table('komentar_makalah as k')
                ->where([
                    ['k.id_makalah',$mak->id_makalah],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%'],
                    ])
                ->count();
        }
        else if(Auth::user()->id_role==2){
            $km_read = DB::table('komentar_makalah as k')
                ->leftjoin('dosen_makalah as em','em.id_makalah','=','k.id_makalah')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['em.id_user',Auth::user()->id_user],
                    ['k.id_makalah',$mak->id_makalah],
                    ['k.readby','like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $km_unread = DB::table('komentar_makalah as k')
                ->leftjoin('dosen_makalah as em','em.id_makalah','=','k.id_makalah')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['em.id_user',Auth::user()->id_user],
                    ['k.id_makalah',$mak->id_makalah],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $newmessage = DB::table('komentar_makalah as k')
                ->leftjoin('dosen_makalah as em','em.id_makalah','=','k.id_makalah')
                ->where([
                    ['em.id_user',Auth::user()->id_user],
                    ['k.id_makalah',$mak->id_makalah],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%'],
                    ])
                ->count();
        }
        else if(Auth::user()->id_role==3){
            $km_read = DB::table('komentar_makalah as k')
                ->leftjoin('makalah as m','m.id_makalah','=','k.id_makalah')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['m.id_user',Auth::user()->id_user],
                    ['k.id_makalah',$mak->id_makalah],
                    ['k.readby','like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $km_unread = DB::table('komentar_makalah as k')
                ->leftjoin('makalah as m','m.id_makalah','=','k.id_makalah')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['m.id_user',Auth::user()->id_user],
                    ['k.id_makalah',$mak->id_makalah],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $newmessage = DB::table('komentar_makalah as k')
                ->leftjoin('makalah as m','m.id_makalah','=','k.id_makalah')
                ->where([
                    ['m.id_user',Auth::user()->id_user],
                    ['k.id_makalah',$mak->id_makalah],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%'],
                    ])
                ->count();
        }
        foreach ($km_unread as $km) {
            DB::table('komentar_makalah')
                ->where('id_komentar',$km->id_komentar)
                ->update(['readby'=>$km->readby.(strlen($km->readby)>0?",":"")."(".Auth::user()->id_user.")"]);
        }

        return view('makalah.show',[
            'active' => $active, 
            'sub_judul' => 'Detail', 
            'makalah' => $mak, 
            'history_plagiat' => $history_plagiat,
            'km_read' => $km_read,
            'km_unread' => $km_unread,
            'newmessage' => $newmessage
        ]);
    }

    public function toPdf($id)
    {
        if (Auth::user()->id_role==2) {
            $count = DB::table('dosen_makalah')
                ->where([
                    ['id_makalah',$id],
                    ['id_user', Auth::user()->id_user]
                ])
                ->count();
            if(!($count>0)){
                return redirect('404');
            }
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            );
        
        $mak = DB::table('makalah as m')
            ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
            ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
            ->where('m.id_makalah', $id)
            ->first();
        if ($mak->id_status==0 && $mak->id_user!=Auth::user()->id_user && Auth::user()->id_role!=1) {
            return redirect('404');
        }
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $kat = DB::table('kategori_makalah as km')
            ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
            ->where('km.id_makalah',$mak->id_makalah)
            ->get();
        $mak->kategori = array_column($kat,'kategori');
        $mak->id_kategori = array_column($kat,'id_kategori');

        $dosen = DB::table('dosen_makalah as e')
            ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
            ->orderBy('e.id_jenis_dosen')
            ->where('e.id_makalah',$mak->id_makalah)
            ->get();

        if ($dosen) {
            $ed = array_column($dosen,'name');
            $mak->dosen1 = $ed[0];
            $mak->dosen2 = $ed[1];
        }
        else{
            $mak->dosen1 = '';
            $mak->dosen2 = '';
        }
		$content = view('makalah.pdf',[
            'active' => $active, 
            'makalah' => $mak
        ])->render();

		$html2pdf = new HTML2PDF('P','A4','fr');
		$html2pdf->WriteHTML($content);
		$html2pdf->Output('makalah'.$mak->id_makalah.'.pdf');
    }

    public function komentar(Request $request,$id)
    {
        if (Auth::user()->id_role==2) {
            $makalah = DB::table('dosen_makalah')
                ->where([
                    ['id_makalah',$id],
                    ['id_user',Auth::user()->id_user]
                ])
                ->count();
            if (!($makalah>0)) {
                return redirect('404');
            }
        }
        else if (Auth::user()->id_role==3) {
            $makalah = DB::table('makalah')
                ->where([
                    ['id_makalah',$id],
                    ['id_user',Auth::user()->id_user]
                ])
                ->count();
            if (!($makalah>0)) {
                return redirect('404');
            }
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        DB::table('komentar_makalah')->insert([
            'komentar' => $request->message,
            'id_makalah' => $id,
            'id_user' => Auth::user()->id_user,
            'waktu' => new DateTime("now"),
            'readby' => ""
        ]);

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Mengomentari proposal',
            'href' => 'makalah/'.$id
        ]);

        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::user()->id_role==2){
            return redirect('404');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            );
        
        $mak = DB::table('makalah as m')
            ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
            ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
            ->where('m.id_makalah', $id)
            ->first();
        if (Auth::user()->id_role!=1 && $mak->id_user!=Auth::user()->id_user) {
            return redirect('404');
        }
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $kat = DB::table('kategori_makalah as km')
            ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
            ->where('km.id_makalah',$mak->id_makalah)
            ->get();
        $mak->kategori = array_column($kat,'id_kategori');

        $dosen = DB::table('dosen_makalah as e')
            ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
            ->orderBy('e.id_jenis_dosen')
            ->where('e.id_makalah',$mak->id_makalah)
            ->get();
        if ($dosen) {
            $ed = array_column($dosen,'name');
            $mak->dosen1 = $ed[0];
            $mak->dosen2 = $ed[1];
        }
        else{
            $mak->dosen1 = '';
            $mak->dosen2 = '';
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        return view('makalah.edit',['active' => $active, 'sub_judul' => 'Edit', 'makalah' => $mak]);
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
        if(Auth::user()->id_role==2){
            return redirect('404');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        if ($request->id_status==2) {
            return redirect("plotdosen/".$id);
        }
        
        $makalah = Makalah::find($id);
        if (Auth::user()->id_role!=1 && $makalah->id_user!=Auth::user()->id_user) {
            return redirect('404');
        }
        $status = $makalah->update($request->all());
        $makalah = Makalah::find($id);
        $plag = new Plagiarisme();
        $set = DB::table('pengaturan')->first();
        $forms = explode(",", $set->form);

        $listmak = DB::table('makalah')
            ->where([
                ['id_status','!=',0],
                ['id_makalah','!=',$makalah->id_makalah]
            ])
            ->get();

        $waktu = date("Y-m-d H:i:s");
        $plagiat = false;
        foreach ($listmak as $mk) {
            foreach ($forms as $form) {
                $percen = $plag->detection($makalah->$form,$mk->$form);
                if($percen>=$set->persen){
                    DB::table('history_plagiat')->insert([
                        'id_makalah_uji' => $makalah->id_makalah,
                        'id_makalah_ref' => $mk->id_makalah,
                        'form' => $form,
                        'persentase' => $percen,
                        'waktu' => $waktu
                    ]);
                    $makalah->id_status = 0;
					$plagiat = true;
                }
            }
        }
		if(!$plagiat){
			$makalah->id_status = $makalah->id_status==0?1:$makalah->id_status;
		}
        
        $makalah->save();
        $status=true;

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan perubahan proposal',
            'href' => 'makalah/'.$makalah->id_makalah
        ]);

        if($makalah->id_status==0){
            return redirect('makalah/'.$makalah->id_makalah)->with('plagiat',true);
        }
        else if($makalah->id_status==1){
            return redirect('lolos')->with('berhasil',$status?"berhasil":"gagal");
        }
        else if ($makalah->id_status==3 || $makalah->id_status==4) {
            return redirect("makalah/".$id);
        }
        else{
            return redirect("makalah")->with('berhasil',$status?"berhasil":"gagal");
        }
    }

    public function savedosen(Request $request,$id)
    {
        if(Auth::user()->id_role!=1){
            return redirect('404');
        }
        $makalah = Makalah::find($id);
        $makalah->id_status="2";
        $status = $makalah->save();
		
		DB::table('dosen_makalah')
			->where('id_makalah',$id)
			->delete();

        DB::table('dosen_makalah')->insert([
            ['id_user'=>$request->dosen_makalah1,'id_makalah'=>$id,'id_jenis_dosen'=>1],
            ['id_user'=>$request->dosen_makalah2,'id_makalah'=>$id,'id_jenis_dosen'=>2]
        ]);
        
        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan ploting dosen pada proposal',
            'href' => 'makalah/'.$makalah->id_makalah
        ]);

        if ($makalah->id_status==3 || $makalah->id_status==4) {
            return redirect("makalah/".$id);
        }
        else{
            return redirect("diterima")->with('berhasil',$status?"berhasil":"gagal");
        }
    }

    public function plotdosen($id)
    {
        if(Auth::user()->id_role!=1){
            return redirect('404');
        }
        $active = array(
            'makalah' => 'active',
            );
        $mak = DB::table('makalah as m')
            ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
            ->where('m.id_makalah', $id)
            ->first();
			
		$dosen1 = DB::table("dosen_makalah")
			->where([
			["id_makalah",$id],
			["id_jenis_dosen",1]
			])
			->first();

		$dosen2 = DB::table("dosen_makalah")
			->where([
			["id_makalah",$id],
			["id_jenis_dosen",2]
			])
			->first();
		
        $user = DB::table('users')->where('id_role',2)->get();		
		
		foreach($user as $us){
			if($dosen1 && $dosen2){
				if($us->id_user == $dosen1->id_user){
					$us->selected = "dosen1";
				}
				else if($us->id_user == $dosen2->id_user){
					$us->selected = "dosen2";
				}
				else{
					$us->selected = "";
				}
			}
			else{
				$us->selected = "";
			}
		}
        
        return view('makalah.plotdosen',['active' => $active, 'sub_judul' => 'Plot dosen', 'dosen' => $user, 'makalah' => $mak]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if(Auth::user()->id_role!=1){
            return redirect('404');
        }
        $status = DB::table('makalah')->where('id_makalah',$id)->delete();
        if($status){
            DB::table('dosen_makalah')->where('id_makalah',$id)->delete();
            DB::table('kategori_makalah')->where('id_makalah',$id)->delete();
            DB::table('komentar_makalah')->where('id_makalah',$id)->delete();
        }

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Menghapus proposal',
            'href' => ''
        ]);

        return redirect("makalah")->with('berhasil',$status?"berhasil":"gagal");
    }
}
