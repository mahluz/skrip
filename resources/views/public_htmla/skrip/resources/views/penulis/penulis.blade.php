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
                Pengguna
                <small>Penulis</small>
              </h1>
            </section>

            <!-- Main content -->
            <section class="content">
              <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Data Penulis</h3>
                </div><!-- /.box-header -->
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
                  <a href="{{ url('penulis/create') }}" class="btn btn-default bg-green">Tambah Penulis <i class="fa fa-plus"></i></a>
                </div>
                <div class="box-body">
                  <table id="tabel_user" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Nama</th>
                        <th>No Pegawai</th>
                        <th>Email</th>
                        <th>No Telp</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($users as $user)
                        <tr>
                          <td>{{ $user->id_user }}</td>
                          <td>{{ $user->name }}</td>
                          <td>{{ $user->no_id }}</td>
                          <td>{{ $user->email }}</td>
                          <td>{{ $user->no_telp }}</td>
                            @if($user->status=='1')
                            <?php $warna='green'; ?>
                            @else
                            <?php $warna='red'; ?>
                            @endif
                          <td>
                            <div class="btn-group">
                              <button type="button" class="btn btn-xs bg-{{ $warna }}">{{ ucfirst($user->status==1?"Aktif":"Non-aktif") }}</button>
                              <button type="button" class="btn btn-xs bg-{{ $warna }} dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                {!!Form::model($user, [
                                  "method" => "patch",
                                  "route" => ["penulis.update",$user->id_user],
                                  "id" => "formid".$user->id_user
                                ])!!}
                                {!! Form::hidden('status',$user->status,array('id' => 'status'.$user->id_user)) !!}
                                {!! Form::close() !!}
                                @if($user->status!="1")
                                  <li><a class="btn btn-xs bg-green"  onclick="if(confirm('Anda yakin akan mengubah status penulis {{ $user->id_user }} menjadi AKTIF?')){$('#status{{$user->id_user}}').val(1);$('#formid{{$user->id_user}}').submit();}">Aktif</a></li>
                                @endif
                                @if($user->status!="0")
                                  <li><a class="btn btn-xs bg-red"  onclick="if(confirm('Anda yakin akan mengubah status penulis {{ $user->id_user }} menjadi NON-AKTIF?')){$('#status{{$user->id_user}}').val(0);$('#formid{{$user->id_user}}').submit();}">Non-Aktif</a></li>
                                @endif
                              </ul>
                            </div>
                          </td>
                          <td>
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['penulis.destroy',$user->id_user],
                                'id' => 'formdelete'.$user->id_user
                            ]) !!}
                            {!!Form::close()!!}
                            <a title="Detail" class="btn btn-xs btn-default" href="{{ url('penulis/'.$user->id_user) }}"><i class="fa fa-search"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('penulis/'.$user->id_user.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Delete" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus penulis {{ $user->id_user }}?')){ $('#formdelete{{ $user->id_user }}').submit(); }"><i class="fa fa-close"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Id</th>
                        <th>Nama</th>
                        <th>No Pegawai</th>
                        <th>Email</th>
                        <th>No Telp</th>
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
        $("#tabel_user").DataTable();
        @if (session('berhasil'))
          @if (session('berhasil')=="berhasil")
            $("#berhasil").fadeIn(300).delay(2000).fadeOut(300);
          @elseif(session('berhasil')=="gagal")
            $("#gagal").fadeIn(300).delay(2000).fadeOut(300);
          @endif
        @endif
      });
    </script>
@endsection