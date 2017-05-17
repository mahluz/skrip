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
                Data Master
                <small>Kategori</small>
              </h1>
            </section>

            <!-- Main content -->
            <section class="content">
              <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Kategori</h3>
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
                  <a href="{{ url('kategori/create') }}" class="btn btn-default bg-green">Tambah Kategori <i class="fa fa-plus"></i></a>
                </div>
                <div class="box-body">
                  <table id="tabel_kategori" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Kategori</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($kategori as $kat)
                        <tr>
                          <td>{{ $kat->id_kategori }}</td>
                          <td>{{ $kat->kategori }}</td>
                          <td>
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['kategori.destroy',$kat->id_kategori],
                                'id' => 'formdelete'.$kat->id_kategori
                            ]) !!}
                            {!!Form::close()!!}
                            <a title="Detail" class="btn btn-xs btn-default" href="{{ url('kategori/'.$kat->id_kategori) }}"><i class="fa fa-search"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('kategori/'.$kat->id_kategori.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Delete" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus kategori {{ $kat->kategori }}?')){ $('#formdelete{{ $kat->id_kategori }}').submit(); }"><i class="fa fa-close"></i></a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Id</th>
                        <th>Kategori</th>
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
        $("#tabel_kategori").DataTable();
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