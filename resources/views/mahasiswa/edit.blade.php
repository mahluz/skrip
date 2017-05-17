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
                mahasiswa
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
                      <h3 class="box-title">Edit mahasiswa</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    {!!Form::model($user,[
                      'method'  => 'patch',
                      'route'   => ['mahasiswa.update',$user->id_user],
                      'id'      => 'form-editmahasiswa',
                      'onsubmit'=> 'setFormSubmitting1()',
                    ])!!}
                    <!-- <form id="form-editmahasiswa" role="form" method="post" action="{{ url('makalah') }}" onsubmit="setFormSubmitting1()"> -->
                      <div class="box-body">
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                          <label for="name">Nama</label>
                          @if ($errors->has('name'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                          @endif
                          <input type="text" required name="name" class="form-control" id="name" placeholder="Nama" value="{{ $user->name }}">
                        </div>
                        <div class="form-group {{ $errors->has('no_id') ? ' has-error' : '' }}">
                          <label>Nomor Pegawai</label>
                          @if ($errors->has('no_id'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('no_id') }}</strong>
                              </span>
                          @endif
                          <input type="text" required name="no_id" class="form-control" id="no_id" placeholder="Nomor Pegawai" value="{{ $user->no_id }}">
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                          <label>Email</label>
                          @if ($errors->has('email'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                          <input type="email" required name="email" class="form-control" id="email" placeholder="Email" value="{{ $user->email }}">
                        </div>
                        <div class="form-group {{ $errors->has('no_telp') ? ' has-error' : '' }}">
                          <label>No Telepon</label>
                          @if ($errors->has('no_telp'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('no_telp') }}</strong>
                              </span>
                          @endif
                          <input type="text" name="no_telp" class="form-control" id="no_telp" placeholder="No Telepon" value="{{ $user->no_telp }}">
                        </div>
                        <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                          <label>Status</label>
                          @if ($errors->has('status'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('status') }}</strong>
                              </span>
                          @endif
                          <select required name="status" class="form-control select2" data-placeholder="Pilih Status" style="width: 100%;">
                            <option {{ $user->status==1?"selected":"" }} value="1">Aktif</option>
                            <option {{ $user->status==0?"selected":"" }} value="0">Non-Aktif</option>
                          </select>
                        </div>
                      </div><!-- /.box-body -->
                      <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Simpan <i class="fa fa-save"></i></button>
                      </div>
                    </form>
                  </div><!-- /.box -->
                  <!-- general form elements -->
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Edit Password mahasiswa</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    {!!Form::model($user,[
                      'method'  => 'patch',
                      'route'   => ['mahasiswa.update',$user->id_user],
                      'id'      => 'form-editpasswordmahasiswa',
                      'onsubmit'=> 'setFormSubmitting2()',
                    ])!!}
                      <div class="box-body">
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                          <label>Password</label>
                          @if ($errors->has('password'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('password') }}</strong>
                              </span>
                          @endif
                          <input type="password" required name="password" class="form-control" id="password" placeholder="Password" value="{{ old('password') }}">
                        </div>
                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                          <label>Confirmation</label>
                          @if ($errors->has('password_confirmation'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('password_confirmation') }}</strong>
                              </span>
                          @endif
                          <input type="password" required name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Retype password" value="{{ old('password_confirmation') }}">
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
      var formSubmitting1 = false;
      var formSubmitting2 = false;
      var setFormSubmitting1 = function() { formSubmitting1 = true; };
      var setFormSubmitting2 = function() { formSubmitting2 = true; };
      var serialize1 = $('#form-editmahasiswa').serialize();
      var serialize2 = $('#form-editpasswordmahasiswa').serialize();

      window.onload = function() {
          window.addEventListener("beforeunload", function (e) {
              if (formSubmitting1 && (serialize2==$('#form-editpasswordmahasiswa').serialize())) {
                  return undefined;
              }

              if (formSubmitting2 && (serialize1==$('#form-editmahasiswa').serialize())) {
                  return undefined;
              }

              if (serialize1==$('#form-editmahasiswa').serialize() && serialize2==$('#form-editpasswordmahasiswa').serialize()) {
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