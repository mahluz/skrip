@extends('layouts.app')

@section('style')
<!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('font-awesome-4.6.1/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('ionicons-2.0.1/css/ionicons.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
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
                Kategori
                <small>{{ $sub_judul }}</small>
              </h1>
            </section>

            <!-- Main content -->
            <section class="content">
              <div class="row">
                <div class="col-xs-12">
                  <!-- general form elements -->
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Detail Kategori</h3>
                    </div><!-- /.box-header -->
                      <div class="box-body">
                        <table class="table">
                        <tr>
                          <th style="width:30%">Action</th>
                          <td>
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['kategori.destroy',$kategori->id_kategori],
                                'id' => 'formdelete'.$kategori->id_kategori
                            ]) !!}
                            {!!Form::close()!!}
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('kategori/'.$kategori->id_kategori.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Delete" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus kategori {{ $kategori->kategori }}?')){ $('#formdelete{{ $kategori->id_kategori }}').submit(); }"><i class="fa fa-close"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <th>Kategori</th>
                          <td>{{ $kategori->kategori }}</td>
                        </tr>
                        <tr>
                          <th style="width:30%">Action</th>
                          <td>
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['kategori.destroy',$kategori->id_kategori],
                                'id' => 'formdelete'.$kategori->id_kategori
                            ]) !!}
                            {!!Form::close()!!}
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('kategori/'.$kategori->id_kategori.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Detail" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus kategori {{ $kategori->kategori }}?')){ $('#formdelete{{ $kategori->id_kategori }}').submit(); }"><i class="fa fa-close"></i></a>
                          </td>
                        </tr>
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
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/fastclick/fastclick.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/app.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script type="text/javascript">
      $(function () {
        $(".select2").select2();
      })
    </script>
@endsection