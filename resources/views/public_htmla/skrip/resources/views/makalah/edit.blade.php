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
                Makalah
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
                      <h3 class="box-title">Edit Makalah</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    {!!Form::model($makalah,[
                      'method'  => 'patch',
                      'route'   => ['makalah.update',$makalah->id_makalah],
                      'id'      => 'form-editmakalah',
                      'onsubmit'=> 'setFormSubmitting()',
                    ])!!}
                    <!-- <form id="form-editmakalah" role="form" method="post" action="{{ url('makalah') }}" onsubmit="setFormSubmitting()"> -->
                      <div class="box-body">
                        <div class="form-group">
                          <label for="judul">Kategori</label>
                          <select required name="kategori_makalah[]" class="form-control select2" multiple="multiple" data-placeholder="Pilih kategori" style="width: 100%;">
                        <?php
                        $katma = DB::table('kategori')->get();
                        ?>
                            @foreach($katma as $kat)
                              <option {{ strlen(array_search($kat->id_kategori,$makalah->kategori))>0?"selected":"" }} value="{{ $kat->id_kategori }}">{{ $kat->kategori }}</option>
                            @endforeach
                          </select>
                        </div><!-- /.form-group -->
                        <div class="form-group {{ $errors->has('judul') ? ' has-error' : '' }}">
                          <label for="judul">Judul</label>
                          @if ($errors->has('judul'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('judul') }}</strong>
                              </span>
                          @endif
                          <input type="text" required name="judul" class="form-control" id="judul" placeholder="Judul" value="{{ $makalah->judul }}">
                        </div>
                        <div class="form-group {{ $errors->has('abstrak') ? ' has-error' : '' }}">
                          <label>Abstrak</label>
                          @if ($errors->has('abstrak'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('abstrak') }}</strong>
                              </span>
                          @endif
                          <textarea name="abstrak" required class="form-control" rows="10" placeholder="Abstrak ...">{{ $makalah->abstrak }}</textarea>
                        </div>
                        <div class="form-group {{ $errors->has('permasalahan') ? ' has-error' : '' }}">
                          <label>Permasalahan</label>
                          @if ($errors->has('permasalahan'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('permasalahan') }}</strong>
                              </span>
                          @endif
                          <textarea name="permasalahan" required class="form-control" rows="5" placeholder="Permasalahan ...">{{ $makalah->permasalahan }}</textarea>
                        </div>
                        <div class="form-group {{ $errors->has('tujuan') ? ' has-error' : '' }}">
                          <label>Tujuan</label>
                          @if ($errors->has('tujuan'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('tujuan') }}</strong>
                              </span>
                          @endif
                          <textarea name="tujuan" required class="form-control" rows="5" placeholder="Tujuan ...">{{ $makalah->tujuan }}</textarea>
                        </div>
                        <div class="form-group {{ $errors->has('tinjauan_pustaka') ? ' has-error' : '' }}">
                          <label>Tinjauan pustaka</label>
                          @if ($errors->has('tinjauan_pustaka'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('tinjauan_pustaka') }}</strong>
                              </span>
                          @endif
                          <textarea name="tinjauan_pustaka" required class="form-control" rows="10" placeholder="Tinjauan pustaka ...">{{ $makalah->tinjauan_pustaka }}</textarea>
                        </div>
                        <div class="form-group {{ $errors->has('kesimpulan_sementara') ? ' has-error' : '' }}">
                          <label>Kesimpulan Sementara</label>
                          @if ($errors->has('kesimpulan_sementara'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('kesimpulan_sementara') }}</strong>
                              </span>
                          @endif
                          <textarea name="kesimpulan_sementara" required class="form-control" rows="5" placeholder="Kesimpulan sementara ...">{{ $makalah->kesimpulan_sementara }}</textarea>
                        </div>
                        <div class="form-group {{ $errors->has('daftar_pustaka') ? ' has-error' : '' }}">
                          <label>Daftar Pustaka</label>
                          @if ($errors->has('daftar_pustaka'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('daftar_pustaka') }}</strong>
                              </span>
                          @endif
                          <textarea name="daftar_pustaka" required class="form-control" rows="5" placeholder="Daftar pustaka ...">{{ $makalah->daftar_pustaka }}</textarea>
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
      var serialize = $('#form-editmakalah').serialize();

      window.onload = function() {
          window.addEventListener("beforeunload", function (e) {
              if (formSubmitting) {
                  return undefined;
              }

              if (serialize==$('#form-editmakalah').serialize()) {
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