@extends('layouts.app')

@section('style')
<!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('font-awesome-4.6.1/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('ionicons-2.0.1/css/ionicons.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.min.css')}}">
@endsection

@section('name')
  {{ Auth::user()->name }}
@endsection

@section('content')
            <section class="content-header">
              <h1>
                Makalah
                <small>{{ $sub_judul }}</small>
              </h1>
            </section>

            <!-- Main content -->
            <section class="content">
              <div class="row">
                <div class="col-xs-12">
                  <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">Data Makalah {{ $sub_judul }}</h3>
                    </div><!-- /.box-header -->
                    @if(Auth::user()->id_role!=2)
                    <div class="box-header with-border">
                      <div class="alert alert-danger alert-dismissable" id="gagal" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Gagal!</h4>
                        Transaksi Gagal!
                      </div>
                      <div class="alert alert-success alert-dismissable" id="berhasil" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4>  <i class="icon fa fa-check"></i> Sukses!</h4>
                        Transaksi Berhasil!
                      </div>
                      <a href="{{ url('makalah/create') }}" class="btn btn-default bg-green">Tambah <i class="fa fa-plus"></i></a>
                      @if(Auth::user()->id_role==1 && $sub_judul=='Diterima')
                      <a title="Export to Excel" class="btn btn-default bg-green" href="{{ url('diterima/excel') }}">Export Excel <i class="fa fa-file-excel-o"></i></a>
                      @endif
                    </div>
                    @endif
                    <div class="box-body">
                      <table id="tabel_makalah" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Penulis</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Editor Utama</th>
                            <th>Editor Pengembang</th>
							<th>Waktu Upload</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($makalah as $mk)
                            <tr>
                              <td><a href="{{ url('profileuser/'.$mk->id_user) }}">{{ $mk->name }}</a></td>
                              <td>{{ $mk->judul }}</td>
                              <td>{{ $mk->kategori }}</td>
                              <td>{{ $mk->editor1 }}</td>
                              <td>{{ $mk->editor2 }}</td>
							  <td>{{ date_format(date_create($mk->upload),"Y-m-d H:i:s") }}</td>

                                @if($mk->status=='plagiat')
                                <?php $warna='orange'; ?>
                                @elseif($mk->status=='lolos')
                                <?php $warna='green'; ?>
                                @elseif($mk->status=='diterima')
                                <?php $warna='blue'; ?>
                                @elseif($mk->status=='revisi')
                                <?php $warna='yellow'; ?>
                                @elseif($mk->status=='ditolak')
                                <?php $warna='red'; ?>
                                @endif

                              @if(Auth::user()->id_role == 1 && ($mk->status=='lolos' || $mk->status=='revisi'))
                              <td>
                                <div class="btn-group">
                                  <button type="button" class="btn btn-xs bg-{{ $warna }}">{{ ucfirst($mk->status) }}</button>
                                  <button type="button" class="btn btn-xs bg-{{ $warna }} dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <ul class="dropdown-menu" role="menu">
                                    {!!Form::model($mk, [
                                      "method" => "patch",
                                      "route" => ["makalah.update",$mk->id_makalah],
                                      "id" => "formid".$mk->id_makalah
                                    ])!!}
                                    {!! Form::hidden('id_status',$mk->id_status,array('id' => 'id_status'.$mk->id_makalah)) !!}
                                    {!! Form::close() !!}
                                    @if($mk->status!="diterima")
                                      <li><a class="btn bg-blue" href="#" onclick="if(confirm('Anda yakin akan mengubah status makalah {{ $mk->id_makalah }} DITERIMA?\nPerubahan ini tidak bisa dikembalikan!')){$('#id_status{{$mk->id_makalah}}').val(2);$('#formid{{$mk->id_makalah}}').submit();}">Diterima</a></li>
                                    @endif
                                    @if($mk->status!="revisi")
                                      <li><a class="btn bg-yellow" href="#" onclick="if(confirm('Anda yakin akan mengubah status makalah {{ $mk->id_makalah }} REVISI?')){$('#id_status{{$mk->id_makalah}}').val(3);$('#formid{{$mk->id_makalah}}').submit();}">Revisi</a></li>
                                    @endif
                                    @if($mk->status!="ditolak")
                                      <li><a class="btn bg-red" href="#" onclick="if(confirm('Anda yakin akan mengubah status makalah {{ $mk->id_makalah }} DITERIMA?\nPerubahan ini tidak bisa dikembalikan!')){$('#id_status{{$mk->id_makalah}}').val(4);$('#formid{{$mk->id_makalah}}').submit();}">Ditolak</a></li>
                                    @endif
                                  </ul>
                                </div>
                              </td>
                              @else
                              <td>
                                <!-- <span class="label bg-{{ $warna }}">{{ ucfirst($mk->status) }}</span> -->
                                <button type="button" class="btn btn-xs bg-{{ $warna }}">{{ ucfirst($mk->status) }}</button>
                              </td>
                              @endif
                              <td>
                                @if(Auth::user()->id_role==1)
                                {!! Form::open([
                                    'method' => 'delete',
                                    'route' => ['makalah.destroy',$mk->id_makalah],
                                    'id' => 'formdelete'.$mk->id_makalah
                                ]) !!}
                                {!!Form::close()!!}
                                <a title="Download makalah{{ $mk->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$mk->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                                <a title="Detail" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah) }}"><i class="fa fa-search"></i></a>
                                <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                                <a title="Delete" class="btn btn-xs btn-default" href="#" onclick="if(confirm('Apakah anda yakin akan menghapus makalah {{ $mk->id_makalah }}?')){ $('#formdelete{{$mk->id_makalah}}').submit(); }"><i class="fa fa-close"></i></a>
								@if($mk->status=='diterima')
								<a title="Plot Editor" class="btn btn-xs btn-default" href="{{ url('ploteditor/'.$mk->id_makalah) }}">plot editor</a>
								@endif
                                @elseif(Auth::user()->id_role==3 && Auth::user()->id_user==$mk->id_user)
                                <a title="Download makalah{{ $mk->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$mk->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                                <a title="Detail" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah) }}"><i class="fa fa-search"></i></a>
                                <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                                @else
                                <a title="Download makalah{{ $mk->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$mk->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                                <a title="Detail" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah) }}"><i class="fa fa-search"></i></a>
                                @endif
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>Penulis</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Editor Utama</th>
                            <th>Editor Pengembang</th>
							<th>Waktu Upload</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
            </section><!-- /.content -->
@endsection

@section('script')
<!-- jQuery 2.1.4 -->
    <script src="{{asset('plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/fastclick/fastclick.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/app.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- page script -->
    <script>
      $(function () {
        $("#tabel_makalah").DataTable({
			"order": [[ 5, "desc" ]]
		});
      });
        @if (session('berhasil'))
          @if (session('berhasil')=="berhasil")
            $("#berhasil").fadeIn(300).delay(2000).fadeOut(300);
          @elseif(session('berhasil')=="gagal")
            $("#gagal").fadeIn(300).delay(2000).fadeOut(300);
          @endif
        @endif
    </script>
@endsection