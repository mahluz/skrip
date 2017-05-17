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
                Modul Pembelajaran
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
                      <h3 class="box-title">Input Modul</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form enctype="multipart/form-data" id="form-mahasiswa" required role="form" method="post" action="{{ url('modul') }}" onsubmit="setFormSubmitting()">
                      {!! csrf_field() !!}
                      <div class="box-body">
                        <div class="form-group {{ $errors->has('nama_modul') ? ' has-error' : '' }}">
                          <label for="name">Nama Modul</label>
                          @if ($errors->has('nama_modul'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('nama_modul') }}</strong>
                              </span>
                          @endif
                          <input type="text" required name="nama_modul" class="form-control" id="nama_modul" placeholder="Nama Modul" value="{{ old('nama_modul') }}"/>
                        </div>

                         <div class="form-group {{ $errors->has('id_makul') ? ' has-error' : '' }}">
							{!! Form::label('mata_kuliah', 'Mata kuliah', array('class' => 'control-label')) !!}
							<select name="id_makul" class="form-control">
							<option>--Pilih Mata Kuliah--</option>
							@foreach ($mata_kuliah as $mata_kuliah)
					<option value="{{ $mata_kuliah->id_makul }}">{{ $mata_kuliah->kode_makul }} [{{ $mata_kuliah->mata_kuliah}}]</option>
							@endforeach
							</select>
						</div>
						 <div class="form-group {{ $errors->has('no_id') ? ' has-error' : '' }}">
							{!! Form::label('no_id', 'Nama', array('class' => 'control-label')) !!}
							<select name="no_id" class="form-control">
							<option>--Pilih Dosen--</option>
							@foreach ($users as $users)
					<option value="{{ $users->no_id }}">{{ $users->name }} </option>
							@endforeach
							</select>
							</div>
                        <div class="form-group {{ $errors->has('file') ? ' has-error' : '' }}">
                        <label for="file" >Upload Modul</label>
                        
                     
                          <input type="file" name="file" class="form-control" id="file">
                       
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
      var serialize = $('#form-mahasiswa').serialize();

      window.onload = function() {
          window.addEventListener("beforeunload", function (e) {
              if (formSubmitting) {
                  return undefined;
              }

              if (serialize==$('#form-mahasiswa').serialize()) {
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