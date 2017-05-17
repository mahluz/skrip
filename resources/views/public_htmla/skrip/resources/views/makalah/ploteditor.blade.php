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
                      <h3 class="box-title">Plot Editor</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form-ploteditor" required role="form" method="post" action="{{ url('saveeditor/'.$makalah->id_makalah) }}" onsubmit="return setFormSubmitting()">
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
                          <label>Editor Utama</label>
                          <select id="editor1" required name="editor_makalah1" class="form-control select2" data-placeholder="Pilih Editor Utama" style="width: 100%;" onchange="$('.ed2').removeAttr('disabled');$('#editor2-'+this.value).attr('disabled','disabled');$('.select2').select2();">
                            <option>-</option>
                            @foreach($editor as $ed1)
                              <option class="ed1" @if($ed1->selected=="editor1") selected @elseif($ed1->selected=="editor2") disabled @endif id="editor1-{{ $ed1->id_user }}" value="{{ $ed1->id_user }}">{{ $ed1->name }}</option>
                            @endforeach
                          </select>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                          <label>Editor Pengembang</label>
                          <select id="editor2" required name="editor_makalah2" class="form-control select2" data-placeholder="Pilih Editor Pengembang" style="width: 100%;" onchange="$('.ed1').removeAttr('disabled');$('#editor1-'+this.value).attr('disabled','disabled');$('.select2').select2();">
                            <option>-</option>
                            @foreach($editor as $ed2)
                              <option class="ed2" @if($ed2->selected=="editor2") selected @elseif($ed2->selected=="editor1") disabled @endif id="editor2-{{ $ed2->id_user }}" value="{{ $ed2->id_user }}">{{ $ed2->name }}</option>
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
        if ($('#editor1').val()=="-" || $('#editor2').val()=="-") {
          alert("Silahkan pilih editor!");
          return false;
        }
        else{
          formSubmitting = true; 
          return true;
        }
      };
      var serialize = $('#form-ploteditor').serialize();

      window.onload = function() {
          window.addEventListener("beforeunload", function (e) {
              if (formSubmitting) {
                  return undefined;
              }

              if (serialize==$('#form-ploteditor').serialize()) {
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