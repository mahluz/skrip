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
                      <h3 class="box-title">Plot Dosen</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form-plotdosen" required role="form" method="post" action="{{ url('savedosen/'.$makalah->id_makalah) }}" onsubmit="return setFormSubmitting()">
                      {!! csrf_field() !!}
                      <div class="box-body">
                        <div class="form-group">
                          <label>Judul</label>
                          <p>{{ $makalah->judul }}</p>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                          <label>Penulis</label>
                          <p>{{ $makalah->name }}</p>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                          <label>Dosen Pembimbing 1</label>
                          <select id="dosen1" required name="dosen_makalah1" class="form-control select2" data-placeholder="Pilih dosbing1" style="width: 100%;" onchange="$('.ed2').removeAttr('disabled');$('#dosen2-'+this.value).attr('disabled','disabled');$('.select2').select2();">
                            <option>-</option>
                            @foreach($dosen as $ed1)
                              <option class="ed1" @if($ed1->selected=="dosen1") selected @elseif($ed1->selected=="dosen2") disabled @endif id="dosen1-{{ $ed1->id_user }}" value="{{ $ed1->id_user }}">{{ $ed1->name }}</option>
                            @endforeach
                          </select>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                          <label>Dosen Pembimbing 2</label>
                          <select id="dosen2" required name="dosen_makalah2" class="form-control select2" data-placeholder="Pilih Dosen Pembimbing 2" style="width: 100%;" onchange="$('.ed1').removeAttr('disabled');$('#dosen1-'+this.value).attr('disabled','disabled');$('.select2').select2();">
                            <option>-</option>
                            @foreach($dosen as $ed2)
                              <option class="ed2" @if($ed2->selected=="dosen2") selected @elseif($ed2->selected=="dosen1") disabled @endif id="dosen2-{{ $ed2->id_user }}" value="{{ $ed2->id_user }}">{{ $ed2->name }}</option>
                            @endforeach
                          </select>
                        </div><!-- /.form-group -->
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
      var setFormSubmitting = function() { 
        if ($('#dosen1').val()=="-" || $('#dosen2').val()=="-") {
          alert("Silahkan pilih dosen!");
          return false;
        }
        else{
          formSubmitting = true; 
          return true;
        }
      };
      var serialize = $('#form-plotdosen').serialize();

      window.onload = function() {
          window.addEventListener("beforeunload", function (e) {
              if (formSubmitting) {
                  return undefined;
              }

              if (serialize==$('#form-plotdosen').serialize()) {
                  return undefined;
              }

              var confirmationMessage = 'Sepertinya anda telah mengubah sesuatu pada halaman ini. '
                                      + 'Jika anda pindah halaman, maka perubahan ini dan status perubahan makalah tidak akan disimpan.';

              (e || window.event).returnValue = confirmationMessage; //Gecko + IE
              return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
          });
      };

      $(function () {
        $(".select2").select2();
      })
    </script>
@endsection