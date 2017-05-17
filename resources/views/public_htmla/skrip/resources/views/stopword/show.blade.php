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
                Stopword
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
                      <h3 class="box-title">Detail Stopword</h3>
                    </div><!-- /.box-header -->
                      <div class="box-body">
                        <table class="table">
                        <tr>
                          <th style="width:30%">Action</th>
                          <td style="width:70%">
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['stopword.destroy',$stopword->kata],
                                'id' => 'formdelete'.$stopword->kata
                            ]) !!}
                            {!!Form::close()!!}
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('stopword/'.$stopword->kata.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Delete" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus stopword {{ $stopword->kata }}?')){ $('#formdelete{{$stopword->kata}}').submit(); }"><i class="fa fa-close"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <th>Kata</th>
                          <td>{{ $stopword->kata }}</td>
                        </tr>
                        <tr>
                          <th style="width:30%">Action</th>
                          <td style="width:70%">
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['stopword.destroy',$stopword->kata],
                                'id' => 'formdelete'.$stopword->kata
                            ]) !!}
                            {!!Form::close()!!}
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('stopword/'.$stopword->kata.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Delete" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus stopword {{ $stopword->kata }}?')){ $('#formdelete{{$stopword->kata}}').submit(); }"><i class="fa fa-close"></i></a>
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