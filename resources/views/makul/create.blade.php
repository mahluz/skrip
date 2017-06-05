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

@section('mataKuliah','class=active')
@section('inputMakul','class=active')

@section('content')
            <section class="content-header">
              <h1>
                Mata Kuliah
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
                      <h3 class="box-title">Tambah Mata Kuliah</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form-kategori" required role="form" method="post" action="{{ url('makul') }}" onsubmit="setFormSubmitting()">
                      {!! csrf_field() !!}
                      <div class="box-body">
                        <div class="form-group {{ $errors->has('kode_makul') ? ' has-error' : '' }}">
                          <label for="name">Kode Makul</label>
                          @if ($errors->has('kode_makul'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('kode_makul') }}</strong>
                              </span>
                          @endif
                          <input type="text" required name="kode_makul" class="form-control" id="kode_makul" placeholder="Kode Makul" value="{{ old('kode_makul') }}">
                        </div>
						 <div class="form-group {{ $errors->has('mata_kuliah') ? ' has-error' : '' }}">
                          <label for="name">Mata Kuliah</label>
                          @if ($errors->has('mata_kuliah'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('mata_kuliah') }}</strong>
                              </span>
                          @endif
                          <input type="text" required name="mata_kuliah" class="form-control" id="mata_kuliah" placeholder="Mata Kuliah" value="{{ old('mata_kuliah') }}">
                        </div>
						<div class="form-group {{ $errors->has('sks') ? ' has-error' : '' }}">
                          <label for="name">SKS</label>
                          @if ($errors->has('sks'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('sks') }}</strong>
                              </span>
                          @endif
                          <input type="text" required name="sks" class="form-control" id="sks" placeholder="SKS" value="{{ old('sks') }}">
                        </div>


                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Simpan <i class="fa fa-save"></i></button>
                      </div>
                    </form>
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
      var formSubmitting = false;
      var setFormSubmitting = function() { formSubmitting = true; };
      var serialize = $('#form-kategori').serialize();

      window.onload = function() {
          window.addEventListener("beforeunload", function (e) {
              if (formSubmitting) {
                  return undefined;
              }

              if (serialize==$('#form-kategori').serialize()) {
                  return undefined;
              }

              var confirmationMessage = 'Sepertinya anda telah mengubah sesuatu pada halaman ini. '
                                      + 'Jika anda pindah halaman sebelum perubahan ini disimpan, maka perubahan ini akan hilang.';

              (e || window.event).returnValue = confirmationMessage; //Gecko + IE
              return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
          });
      };

      $(function () {
        $(".select2").select2();
      })
    </script>
@endsection
