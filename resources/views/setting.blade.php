    @extends('layouts.app')

@section('style')
<!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('font-awesome-4.6.1/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('ionicons-2.0.1/css/ionicons.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/ionslider/ion.rangeSlider.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/ionslider/ion.rangeSlider.skinNice.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-slider/slider.css')}}">
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
                Pengaturan
                <small>Plagiarisme</small>
              </h1>
            </section>

            <!-- Main content -->
            <section class="content">
              <div class="row">
                <div class="col-xs-12">
                  <div class="box box-primary">
                    <div class="box-body">
                      <div class="alert alert-danger alert-dismissable" id="gagal" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Gagal!</h4>
                        Pengaturan Plagiarisme Gagal disimpan!
                      </div>
                      <div class="alert alert-success alert-dismissable" id="berhasil" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4>  <i class="icon fa fa-check"></i> Sukses!</h4>
                        Pengaturan Plagiarisme Berhasil disimpan!
                      </div>
                    </div><!-- /.box-body -->
                    <form method="post" id="form-setting" action="{{ url('simpanPengaturan') }}" onsubmit="setFormSubmitting()">
                      {!! csrf_field() !!}
                    <div class="box-header">
                      <h3 class="box-title">Persentase Plagiat</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <div class="row margin">
                        <div class="col-sm-12">
                          <input id="persentase" type="text" name="persentase" value="0;100" data-slider-id="red">
                        </div>
                      </div>
                    </div><!-- /.box-body -->
                    <div class="box-header">
                      <h3 class="box-title">Rabin Karp K-gram</h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input id="kgram" type="number" min="4" max="15" name="kgram" value="{{ $setting->kgram }}">
                    </div><!-- /.box-header -->
                    <div class="box-header">
                      <h3 class="box-title">Form Plagiat</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <div class="form-group">
                        <select name="form_plagiat[]" class="form-control select2" multiple="multiple" data-placeholder="Pilih Form apa saja yang akan dihitung plagiat" style="width: 100%;">
                          <option value="judul" {{ $form['judul'] or '' }}>Judul</option>
                          <option value="abstrak" {{ $form['abstrak'] or '' }}>Abstrak</option>
                          <option value="permasalahan" {{ $form['permasalahan'] or '' }}>Permasalahan</option>
                          <option value="tujuan" {{ $form['tujuan'] or '' }}>Tujuan</option>
                          <option value="tinjauan" {{ $form['tinjauan'] or '' }}>Tinjauan Pustaka</option>
                          <option value="kesimpulan" {{ $form['kesimpulan'] or '' }}>Kesimpulan Sementara</option>
                        </select>
                      </div><!-- /.form-group -->
                    </div>
                    <div class="box-footer clearfix">
                      <button class="btn btn-default bg-green">Simpan <i class="fa fa-save"></i></button>
                    </div>
                    </form>
                  </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
        </section>
            <!-- /.content -->
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
    <!-- page script -->
    <script src="{{ asset('plugins/ionslider/ion.rangeSlider.min.js') }}"></script>
    <!-- Bootstrap slider -->
    <script src="{{ asset('plugins/bootstrap-slider/bootstrap-slider.js') }}"></script>
    <script>
      var formSubmitting = false;
      var setFormSubmitting = function() { formSubmitting = true; };
      $(function () {
        /* BOOTSTRAP SLIDER */
        $('.slider').slider();
        $(".select2").select2();

        /* ION SLIDER */
        $("#persentase").ionRangeSlider({
          type: "single",
          step: 1,
          postfix: " %",
          from: {{ $setting->persen }},
          hideMinMax: false,
          hideFromTo: false,
          hasGrid : true
        });
        @if (session('berhasil'))
          @if (session('berhasil')=="berhasil")
            $("#berhasil").fadeIn(300).delay(2000).fadeOut(300);
          @elseif(session('berhasil')=="gagal")
            $("#gagal").fadeIn(300).delay(2000).fadeOut(300);
          @endif
        @endif
        var serialize = $('#form-setting').serialize();

        window.onload = function() {
            window.addEventListener("beforeunload", function (e) {

                if (formSubmitting) {
                    return undefined;
                }

                if (serialize==$('#form-setting').serialize()) {
                    return undefined;
                }

                var confirmationMessage = 'Sepertinya anda telah mengubah sesuatu pada halaman ini. '
                                        + 'Jika anda pindah halaman sebelum perubahan ini disimpan, maka perubahan ini akan hilang.';

                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
            });
        };
      });
    </script>
@endsection
