<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use PDO;
use Excel;
use Validator;
use App\Http\Requests;
use App\Makalah;
use App\Timeline;
use App\User;
use App\Kategori;
use App\Stopword;
use App\Setting;
use App\Libraries\Plagiarisme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $active = array();

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->status==0){
            return redirect('profile');
        }

        $makalah = null;
        if(Auth::user()->id_role==1){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->orderBy('m.created_at','desc')
				->select("*","m.created_at as upload")
                ->limit(10)
                ->get();
        }
        else if(Auth::user()->id_role==2){
            $makalah = DB::table('makalah as m')
                ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->where('e.id_user',Auth::user()->id_user)
                ->orderBy('m.created_at','desc')
				->select("*","m.created_at as upload")
                ->limit(10)
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->where([
                    ['m.id_user','!=',Auth::user()->id_user],
                    ['m.id_status','!=',0]
                ])
                ->orWhere('m.id_user',Auth::user()->id_user)
                ->orderBy('m.created_at','desc')
				->select("*","m.created_at as upload")
                ->limit(10)
                ->get();
        }

        foreach ($makalah as $mak) {
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $kat = DB::table('kategori_makalah as km')
                ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
                ->where('km.id_makalah',$mak->id_makalah)
                ->get();
            $mak->kategori = implode(",", array_column($kat,'kategori'));

            $editor = DB::table('editor_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_editor')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            if ($editor) {
                $ed = array_column($editor,'name');
                $mak->editor1 = $ed[0];
                $mak->editor2 = $ed[1];
            }
            else{
                $mak->editor1 = '';
                $mak->editor2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        $active = array('dashboard' => 'active');

        if (Auth::user()->id_role!=1) {
            $timeline = DB::table('timeline')
                ->where('id_user',Auth::user()->id_user)
                ->select(DB::raw("*,DATE(created_at) as tanggal,TIME(created_at) as waktu"))
                ->orderBy('created_at','desc')
				->limit(5)
                ->get();
        }
        else{
            $timeline = DB::table('timeline as t')
                ->join('users as u','u.id_user','=','t.id_user')
                ->select(DB::raw("*,DATE(t.created_at) as tanggal,TIME(t.created_at) as waktu"))
                ->orderBy('t.created_at','desc')
				->limit(5)
                ->get();
        }

        $pengaturan = DB::table('pengaturan')->first();

        if(Auth::user()->id_role==1){
            return view('home',[
                'active' => $active,
                'makalah' => $makalah,
                'timeline' => $timeline,
                'persentase' => $pengaturan->persen,
                'form_makalah' => $pengaturan->form,
                'n_plagiat' => DB::table('makalah')->where('id_status',0)->count(),
                'n_lolos' => DB::table('makalah')->where('id_status',1)->count(),
                'n_diterima' => DB::table('makalah')->where('id_status',2)->count(),
                'n_revisi' => DB::table('makalah')->where('id_status',3)->count(),
                'n_ditolak' => DB::table('makalah')->where('id_status',4)->count()
            ]);
        }
        else if(Auth::user()->id_role==2){
            return view('home',[
                'active' => $active,
                'makalah' => $makalah,
                'timeline' => $timeline,
                'persentase' => $pengaturan->persen,
                'form_makalah' => $pengaturan->form,
                'n_diterima' => DB::table('makalah as m')
                    ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                    ->where([
                        ['e.id_user',Auth::user()->id_user],
                        ['m.id_status', 2]
                        ])
                    ->count(),
                'n_revisi' => DB::table('makalah as m')
                    ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                    ->where([
                        ['e.id_user',Auth::user()->id_user],
                        ['m.id_status', 3]
                        ])
                    ->count(),
                'n_ditolak' => DB::table('makalah as m')
                    ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                    ->where([
                        ['e.id_user',Auth::user()->id_user],
                        ['m.id_status', 4]
                        ])
                    ->count()
            ]);
        }
        else if(Auth::user()->id_role==3){
            return view('home',[
                'active' => $active,
                'makalah' => $makalah,
                'timeline' => $timeline,
                'persentase' => $pengaturan->persen,
                'form_makalah' => $pengaturan->form,
                'n_plagiat' => DB::table('makalah as m')
                    ->where([
                        ['m.id_user',Auth::user()->id_user],
                        ['m.id_status', 0]
                        ])
                    ->count(),
                'n_lolos' => DB::table('makalah as m')
                    ->where([
                        ['m.id_status', 1]
                        ])
                    ->count(),
                'n_diterima' => DB::table('makalah as m')
                    ->where([
                        ['m.id_status', 2]
                        ])
                    ->count(),
                'n_revisi' => DB::table('makalah as m')
                    ->where([
                        ['m.id_status', 3]
                        ])
                    ->count(),
                'n_ditolak' => DB::table('makalah as m')
                    ->where([
                        ['m.id_status', 4]
                        ])
                    ->count()
            ]);
        }
    }
	
	public function makalahuser(){
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        if(Auth::user()->id_role==2){
			return redirect('404');
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
				->where("m.id_user",Auth::user()->id_user)
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->select("*","m.created_at as upload")
                ->where('m.id_user',Auth::user()->id_user)
                ->get();
        }

        foreach ($makalah as $mak) {
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $kat = DB::table('kategori_makalah as km')
                ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
                ->where('km.id_makalah',$mak->id_makalah)
                ->get();
            $mak->kategori = implode(",", array_column($kat,'kategori'));

            $editor = DB::table('editor_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_editor')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            if ($editor) {
                $ed = array_column($editor,'name');
                $mak->editor1 = $ed[0];
                $mak->editor2 = $ed[1];
            }
            else{
                $mak->editor1 = '';
                $mak->editor2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        return view('makalah.makalah',['active' => $active, 'sub_judul' => 'Semua makalah', 'makalah' => $makalah]);
	}

    public function plagiat()
    {
        if (Auth::user()->id_role==2) {
            return redirect('404');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            'baru' => 'active',
            'plagiat' => 'active'
            );

        $makalah = null;
        if(Auth::user()->id_role==1){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where('m.id_status', 0)
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where([
                    ['m.id_user',Auth::user()->id_user],
                    ['m.id_status', 0]
                    ])
                ->get();
        }
        foreach ($makalah as $mak) {
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $kat = DB::table('kategori_makalah as km')
                ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
                ->where('km.id_makalah',$mak->id_makalah)
                ->get();
            $mak->kategori = implode(",", array_column($kat,'kategori'));

            $editor = DB::table('editor_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_editor')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            if ($editor) {
                $ed = array_column($editor,'name');
                $mak->editor1 = $ed[0];
                $mak->editor2 = $ed[1];
            }
            else{
                $mak->editor1 = '';
                $mak->editor2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        return view('makalah.makalah',['active' => $active, 'sub_judul' => 'Terdeteksi Plagiat', 'makalah' => $makalah]);
    }

    public function lolos()
    {
        if (Auth::user()->id_role==2) {
            return redirect('404');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            'baru' => 'active',
            'lolos' => 'active'
            );
        $makalah = null;
        if(Auth::user()->id_role==1){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where('m.id_status', 1)
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where([
                        ['m.id_status', 1]
                    ])
                ->get();
        }
        foreach ($makalah as $mak) {
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $kat = DB::table('kategori_makalah as km')
                ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
                ->where('km.id_makalah',$mak->id_makalah)
                ->get();
            $mak->kategori = implode(",", array_column($kat,'kategori'));

            $editor = DB::table('editor_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_editor')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            if ($editor) {
                $ed = array_column($editor,'name');
                $mak->editor1 = $ed[0];
                $mak->editor2 = $ed[1];
            }
            else{
                $mak->editor1 = '';
                $mak->editor2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        return view('makalah.makalah',['active' => $active, 'sub_judul' => 'Lolos Plagiat', 'makalah' => $makalah]);
    }

    public function diterima()
    {
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            'direktori' => 'active',
            'diterima' => 'active',
            );
        $makalah = null;
        if(Auth::user()->id_role==1){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where('m.id_status', 2)
                ->get();
        }
        else if(Auth::user()->id_role==2){
            $makalah = DB::table('makalah as m')
                ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where([
                    ['e.id_user',Auth::user()->id_user],
                    ['m.id_status', 2]
                    ])
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where('m.id_status', 2)
                ->get();
        }
        foreach ($makalah as $mak) {
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $kat = DB::table('kategori_makalah as km')
                ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
                ->where('km.id_makalah',$mak->id_makalah)
                ->get();
            $mak->kategori = implode(",", array_column($kat,'kategori'));

            $editor = DB::table('editor_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_editor')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            if ($editor) {
                $ed = array_column($editor,'name');
                $mak->editor1 = $ed[0];
                $mak->editor2 = $ed[1];
            }
            else{
                $mak->editor1 = '';
                $mak->editor2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        return view('makalah.makalah',['active' => $active, 'sub_judul' => 'Diterima', 'makalah' => $makalah]);
    }

    public function diterimaexcel()
    {
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            'direktori' => 'active',
            'diterima' => 'active',
            );
        $makalah = null;
        if(Auth::user()->id_role==1){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->select("*","m.created_at as upload")
                ->where('m.id_status', 2)
                ->get();
        }
        else if(Auth::user()->id_role==2){
            $makalah = DB::table('makalah as m')
                ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->select("*","m.created_at as upload")
                ->where([
                    ['e.id_user',Auth::user()->id_user],
                    ['m.id_status', 2]
                    ])
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
                ->select("*","m.created_at as upload")
                ->where('m.id_status', 2)
                ->get();
        }
        foreach ($makalah as $mak) {
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $kat = DB::table('kategori_makalah as km')
                ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
                ->where('km.id_makalah',$mak->id_makalah)
                ->get();
            $mak->kategori = implode(",", array_column($kat,'kategori'));

            $editor = DB::table('editor_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_editor')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            if ($editor) {
                $ed = array_column($editor,'name');
                $mak->editor1 = $ed[0];
                $mak->editor2 = $ed[1];
            }
            else{
                $mak->editor1 = '';
                $mak->editor2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        Excel::create("makalah-diterima-".date("Y-m-d H:i:s"), function ($excel) use($makalah) {
            // Set the title
            $excel->setTitle("makalah-diterima-".date("Y-m-d H:i:s"));

            // Chain the setters
            $excel->setCreator(Auth::user()->name)
                  ->setCompany('Sistem Direktori Makalah');

            // Call them separately
            $excel->setDescription('Semua makalah dengan status diterima');

            $excel->sheet('Diterima', function($sheet) use($makalah) {
                $data = array(
                        array("Nama","No. Pegawai","Judul","Editor Utama","Editor Pengembang","Tanggal Upload")
                    );

                foreach ($makalah as $mak) {
                    $data[] = array(ucwords($mak->name),$mak->no_id,$mak->judul,ucwords($mak->editor1),ucwords($mak->editor2),$mak->upload);
                }

                $sheet->fromArray($data,null,"A1",false,false);
            });

        })->export("xlsx");
    }
	
	public function revisi()
    {
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            'direktori' => 'active',
            'revisi' => 'active',
            );
        
        $makalah = null;
        if(Auth::user()->id_role==1){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where('m.id_status', 3)
                ->get();
        }
        else if(Auth::user()->id_role==2){
            $makalah = DB::table('makalah as m')
                ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where([
                    ['e.id_user',Auth::user()->id_user],
                    ['m.id_status', 3]
                    ])
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where([
                    ['m.id_status', 3]
                    ])
                ->get();
        }
        foreach ($makalah as $mak) {
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $kat = DB::table('kategori_makalah as km')
                ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
                ->where('km.id_makalah',$mak->id_makalah)
                ->get();
            $mak->kategori = implode(",", array_column($kat,'kategori'));

            $editor = DB::table('editor_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_editor')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            if ($editor) {
                $ed = array_column($editor,'name');
                $mak->editor1 = $ed[0];
                $mak->editor2 = $ed[1];
            }
            else{
                $mak->editor1 = '';
                $mak->editor2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        return view('makalah.makalah',['active' => $active, 'sub_judul' => 'Revisi', 'makalah' => $makalah]);
    }

    public function ditolak()
    {
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            'direktori' => 'active',
            'ditolak' => 'active',
            );
        
        $makalah = null;
        if(Auth::user()->id_role==1){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where('m.id_status', 4)
                ->get();
        }
        else if(Auth::user()->id_role==2){
            $makalah = DB::table('makalah as m')
                ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where([
                    ['e.id_user',Auth::user()->id_user],
                    ['m.id_status', 4]
                    ])
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('makalah as m')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where([
                    ['m.id_status', 4]
                    ])
                ->get();
        }
        foreach ($makalah as $mak) {
            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $kat = DB::table('kategori_makalah as km')
                ->leftjoin('kategori as k', 'k.id_kategori', '=', 'km.id_kategori')
                ->where('km.id_makalah',$mak->id_makalah)
                ->get();
            $mak->kategori = implode(",", array_column($kat,'kategori'));

            $editor = DB::table('editor_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_editor')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            $ed = array_column($editor,'name');
            if ($editor) {
                $ed = array_column($editor,'name');
                $mak->editor1 = $ed[0];
                $mak->editor2 = $ed[1];
            }
            else{
                $mak->editor1 = '';
                $mak->editor2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
        return view('makalah.makalah',['active' => $active, 'sub_judul' => 'Ditolak', 'makalah' => $makalah]);
    }

    public function setting()
    {
        if(Auth::user()->id_role != 1)
        {
            return redirect('404');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array('plagiarisme' => 'active');
        $setting = Setting::find(1)->first();
        $data = explode(",", $setting->form);
        $form = array();

        foreach ($data as $key => $value) {
            $form[$value] = "selected";
        }
        
        return view('setting',['active' => $active, 'setting' => $setting, 'form' => $form]);
    }

    public function simpanPengaturan(Request $request)
    {

        if(Auth::user()->id_role != 1)
        {
            return redirect('404');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $setting = Setting::find(1)->first();
        $setting->persen = $request->persentase;
        $setting->form = implode(",", $request->form_plagiat);
        $setting->kgram = $request->kgram>15 || $request->kgram<4?4:$request->kgram;
        $status = $setting->save();

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan perubahan pengaturan plagiarisme',
            'href' => 'setting'
        ]);

        return redirect('setting')->with('berhasil',$status?"berhasil":"gagal");
    }

    public function katmakalah($id)
    {
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            'katmakalah' => 'active',
            'katma'.$id => 'active', 
            );
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return redirect('404');
        }
        $makalah = null;
        if(Auth::user()->id_role==1){
            $makalah = DB::table('kategori_makalah as km')
                ->leftjoin('makalah as m', 'km.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where('km.id_kategori', $id)
                ->get();
        }
        else if(Auth::user()->id_role==2){
            $makalah = DB::table('kategori_makalah as km')
                ->leftjoin('makalah as m', 'km.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where([
                    ['e.id_user',Auth::user()->id_user],
                    ['km.id_kategori', $id]
                    ])
                ->get();
        }
        else if(Auth::user()->id_role==3){
            $makalah = DB::table('kategori_makalah as km')
                ->leftjoin('makalah as m', 'km.id_makalah', '=', 'm.id_makalah')
                ->leftjoin('users as u1', 'm.id_user', '=', 'u1.id_user')
                ->leftjoin('status_makalah as s', 'm.id_status', '=', 's.id_status')
				->select("*","m.created_at as upload")
                ->where([
                    ['m.id_user','!=',Auth::user()->id_user],
                    ['m.id_status','!=',0],
                    ['km.id_kategori', $id]
                ])
                ->orWhere('m.id_user',Auth::user()->id_user)
                ->get();
        }

        foreach ($makalah as $mak) {
            $mak->kategori = $kategori->kategori;

            DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
            $editor = DB::table('editor_makalah as e')
                ->leftjoin('users as u', 'e.id_user', '=', 'u.id_user')
                ->orderBy('e.id_jenis_editor')
                ->where('e.id_makalah',$mak->id_makalah)
                ->get();
            if ($editor) {
                $ed = array_column($editor,'name');
                $mak->editor1 = $ed[0];
                $mak->editor2 = $ed[1];
            }
            else{
                $mak->editor1 = '';
                $mak->editor2 = '';
            }
        }
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);

        return view('makalah.makalah',['active' => $active, 'sub_judul' => $kategori->kategori, 'makalah' => $makalah]);
    }

    public function profile($tab = "timeline")
    {
        $active = array(
            'profile' => 'active',
            );
        if ($tab!="timeline" && $tab!="edit" && $tab!="password") {
            return redirect("profile/timeline");
        }
        $tabs[$tab] = 'active';

        if (Auth::user()->id_role!=1) {
            $timeline = DB::table('timeline')
                ->where('id_user',Auth::user()->id_user)
                ->select(DB::raw("*,DATE(created_at) as tanggal,TIME(created_at) as waktu"))
                ->orderBy('created_at','desc')
                ->get();
        }
        else{
            $timeline = DB::table('timeline as t')
                ->join('users as u','u.id_user','=','t.id_user')
                ->select(DB::raw("*,DATE(t.created_at) as tanggal,TIME(t.created_at) as waktu"))
                ->orderBy('t.created_at','desc')
                ->get();
        }

        // print_r($timeline);die();
        
        return view('profile',['active' => $active, 'tab' => $tabs, 'timeline' => $timeline]);
    }

    public function profileuser($id)
    {
        if($id==Auth::user()->id_user){
            return redirect('profile');
        }
        if(Auth::user()->status==0){
            return redirect('profile');
        }
        $active = array(
            'makalah' => 'active',
            );

        $user = User::find($id);
        $makalah = Makalah::where('id_user',$id);

        return view('profileuser',['active' => $active, 'sub_judul' => "Detail Penulis", 'user' => $user, 'makalah' => $makalah]);
    }

    public function profileupdate(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'no_id' => 'required|max:255',
            'email' => 'required|email|max:255'
        ]);

        if ($request->no_id != Auth::user()->no_id) {
            $user = DB::table('users')
                ->where([
                    ['no_id',$request->no_id],
                    ['id_user','!=',Auth::user()->id_user]
                    ])
                ->first();
            if ($user) {
                return redirect("profile/edit")->withErrors(['no_id' => 'Nomor pegawai sudah digunakan']);
            }
        }
        
        if ($validator->fails()) {
            return redirect('profile/edit')
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = User::find(Auth::user()->id_user);
        $user->name = $request->name;
        $user->no_id = $request->no_id;
        $user->email = $request->email;
        $user->no_telp = $request->no_telp;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $filename = Auth::user()->id_user.".".$file->getClientOriginalExtension();
            $file->move("dist/img",$filename);
            $user->image = "dist/img/".$filename;
        }

        $status = $user->save();

        Timeline::create([
            'id_user' => Auth::user()->id_user,
            'aksi' => 'Melakukan perubahan profil pribadi',
            'href' => 'profile'
        ]);

        return redirect('profile/edit')->with('berhasil',$status?"berhasil":"gagal");
    }

    public function changepwd(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect('profile/password')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $status = false;
        $user = User::find(Auth::user()->id_user);
        
        if(Hash::check($request->old_password,$user->password)){
            $user->password = bcrypt($request->new_password);
            $status = $user->save();

            Timeline::create([
                'id_user' => Auth::user()->id_user,
                'aksi' => 'Melakukan perubahan password',
                'href' => 'profile/password'
            ]);


            return redirect('profile/password')->with('berhasil',$status?"berhasil":"gagal");
        }
        else{
            $validator->errors()->add('old_password', 'Password lama yang anda masukkan, Salah!');
            return redirect('profile/password')
                ->withErrors($validator)
                ->withInput();
        }
    }
}
