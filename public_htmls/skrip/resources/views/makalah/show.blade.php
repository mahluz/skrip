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
                      <div class="alert alert-danger alert-dismissable" id="plagiat-alert" style="display:none">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terdeteksi Plagiat!</h4>
                        Maaf makalah yang anda submit, terdeteksi plagiat, Silahkan lihat detail plagiat dibagian bawah halaman ini!
                      </div>
                      <h3 class="box-title">Detail Makalah</h3>
                    </div><!-- /.box-header -->
                      <div class="box-body">
                        <table class="table">
                            @if($makalah->status=='plagiat')
                            <?php $warna='orange'; ?>
                            @elseif($makalah->status=='lolos')
                            <?php $warna='green'; ?>
                            @elseif($makalah->status=='diterima')
                            <?php $warna='blue'; ?>
                            @elseif($makalah->status=='revisi')
                            <?php $warna='yellow'; ?>
                            @elseif($makalah->status=='ditolak')
                            <?php $warna='red'; ?>
                            @endif
                        @if(Auth::user()->id_role==1 || (Auth::user()->id_role==3 && Auth::user()->id_user==$makalah->id_user))
                        <tr>
                          <th style="width:30%">Action</th>
                          <td style="width:70%">
                            @if(Auth::user()->id_role==1)
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['makalah.destroy',$makalah->id_makalah],
                                'id' => 'formdelete'.$makalah->id_makalah
                            ]) !!}
                            {!!Form::close()!!}
							              <a title="Download makalah{{ $makalah->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$makalah->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Delete" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus makalah {{ $makalah->id_makalah }}?')){ $('#formdelete{{$makalah->id_makalah}}').submit(); }"><i class="fa fa-close"></i></a>
							@if($makalah->status=='diterima')
							<a title="Plot Editor" class="btn btn-xs btn-default" href="{{ url('ploteditor/'.$makalah->id_makalah) }}">plot editor</a>
							@endif
                            @else
							              <a title="Download makalah{{ $makalah->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$makalah->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                            @endif
                          </td>
                        </tr>
                        @endif
                        <tr>
                          <th>Status</th>
                          @if(Auth::user()->id_role == 1 && ($makalah->status=='lolos' || $makalah->status=='revisi'))
                          <td>
                            <div class="btn-group">
                              <button type="button" class="btn btn-xs bg-{{ $warna }}">{{ ucfirst($makalah->status) }}</button>
                              <button type="button" class="btn btn-xs bg-{{ $warna }} dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                {!!Form::model($makalah, [
                                  "method" => "patch",
                                  "route" => ["makalah.update",$makalah->id_makalah],
                                  "id" => "formid".$makalah->id_makalah
                                ])!!}
                                {!! Form::hidden('id_status',$makalah->id_status,array('id' => 'id_status'.$makalah->id_makalah)) !!}
                                {!! Form::close() !!}
                                @if($makalah->status!="diterima")
                                  <li><a class="btn bg-blue" href="#" onclick="if(confirm('Anda yakin akan mengubah status makalah {{ $makalah->id_makalah }} DITERIMA?\nPerubahan ini tidak bisa dikembalikan!')){$('#id_status{{$makalah->id_makalah}}').val(2);$('#formid{{$makalah->id_makalah}}').submit();}">Diterima</a></li>
                                @endif
                                @if($makalah->status!="revisi")
                                  <li><a class="btn bg-yellow" href="#" onclick="if(confirm('Anda yakin akan mengubah status makalah {{ $makalah->id_makalah }} REVISI?\nPerubahan ini tidak bisa dikembalikan!')){$('#id_status{{$makalah->id_makalah}}').val(3);$('#formid{{$makalah->id_makalah}}').submit();}">Revisi</a></li>
                                @endif
                                @if($makalah->status!="ditolak")
                                  <li><a class="btn bg-red" href="#" onclick="if(confirm('Anda yakin akan mengubah status makalah {{ $makalah->id_makalah }} DITERIMA?\nPerubahan ini tidak bisa dikembalikan!')){$('#id_status{{$makalah->id_makalah}}').val(4);$('#formid{{$makalah->id_makalah}}').submit();}">Ditolak</a></li>
                                @endif
                              </ul>
                            </div>
                          </td>
                          @else
                          <td>
                            <!-- <span class="label bg-{{ $warna }}">{{ ucfirst($makalah->status) }}</span> -->
                            <button type="button" class="btn btn-xs bg-{{ $warna }}">{{ ucfirst($makalah->status) }}</button>
                          </td>
                          @endif
                        </tr>
                        <tr>
                          <th>Kategori Makalah</th>
                          <td>
                            @foreach($makalah->kategori as $key => $val)
                              <a href="{{ url('katmakalah/'.$makalah->id_kategori[$key]) }}"><span class="badge bg-blue">{{ $val }}</span></a>, 
                            @endforeach
                          </td>
                        </tr><!-- /.form-group -->
                        <tr>
                          <th>Editor Utama</th>
                          <td>{{ $makalah->editor1 }}</td>
                        </tr>
                        <tr>
                          <th>Editor Pengembang</th>
                          <td>{{ $makalah->editor2 }}</td>
                        </tr>
                        <tr>
                          <th>Penulis</th>
                          <td><a href="{{ url('profileuser/'.$makalah->id_user) }}">{{ $makalah->name }}</a></td>
                        </tr>
                        <tr>
                          <th>Judul</th>
                          <td>{{ $makalah->judul }}</td>
                        </tr>
                        <tr>
                          <th>Abstrak</th>
                          <td>{{ $makalah->abstrak }}</td>
                        </tr>
                        <tr>
                          <th>Permasalahan</th>
                          <td>{{ $makalah->permasalahan }}</td>
                        </tr>
                        <tr>
                          <th>Tujuan</th>
                          <td>{{ $makalah->tujuan }}</td>
                        </tr>
                        <tr>
                          <th>Tinjauan pustaka</th>
                          <td>{{ $makalah->tinjauan_pustaka }}</td>
                        </tr>
                        <tr>
                          <th>Kesimpulan Sementara</th>
                          <td>{{ $makalah->kesimpulan_sementara }}</td>
                        </tr>
                        <tr>
                          <th>Daftar Pustaka</th>
                          <td>{{ $makalah->daftar_pustaka }}</td>
                        </tr>
                        <tr>
                          <th>Diunggah tanggal</th>
                          <td>{{ date_format(date_create($makalah->created_at),"d-m-Y") }}</td>
                        </tr>
                        <tr>
                          <th>Diupdate tanggal</th>
                          <td>{{ date_format(date_create($makalah->updated_at),"d-m-Y") }}</td>
                        </tr>
                        @if(Auth::user()->id_role==1 || (Auth::user()->id_role==3 && Auth::user()->id_user==$makalah->id_user))
                        <tr>
                          <th>Action</th>
                          <td style="width:70%">
                            @if(Auth::user()->id_role==1)
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['makalah.destroy',$makalah->id_makalah],
                                'id' => 'formdelete'.$makalah->id_makalah
                            ]) !!}
                            {!!Form::close()!!}
                            <a title="Download makalah{{ $makalah->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$makalah->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Delete" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus makalah {{ $makalah->id_makalah }}?')){ $('#formdelete{{$makalah->id_makalah}}').submit(); }"><i class="fa fa-close"></i></a>
              @if($makalah->status=='diterima')
              <a title="Plot Editor" class="btn btn-xs btn-default" href="{{ url('ploteditor/'.$makalah->id_makalah) }}">plot editor</a>
              @endif
                            @else
                            <a title="Download makalah{{ $makalah->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$makalah->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                            @endif
                          </td>
                        </tr>
                        @endif
                      </table>
                      </div><!-- /.box-body -->
                  </div><!-- /.box -->
                  @if($makalah->status!='plagiat')
                    @if(Auth::user()->id_role!=3||Auth::user()->id_user==$makalah->id_user)
                  <!-- DIRECT CHAT PRIMARY -->
                  <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Komentar tentang makalah</h3>
                      <div class="box-tools pull-right">
                        <span data-toggle="tooltip" title="{{ $newmessage }} Pesan Baru" class="badge bg-light-blue">{{ $newmessage }}</span>
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <!-- Conversations are loaded here -->
                      <div class="direct-chat-messages">
                        @foreach($km_read as $km)
                          @if($km->id_user==Auth::user()->id_user)
                            <!-- Message. Default to the left -->
                            <div class="direct-chat-msg read-komen" style="display:none">
                              <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-left">{{ $km->name }}</span>
                                <span class="direct-chat-timestamp pull-right">{{ date_format(date_create($km->waktu),"j M y H:i:s") }}</span>
                              </div><!-- /.direct-chat-info -->
                              <img class="direct-chat-img" src="{{ url($km->image) }}" alt="message user image"><!-- /.direct-chat-img -->
                              <div class="direct-chat-text bg-yellow">
                                {{ $km->komentar }}
                              </div><!-- /.direct-chat-text -->
                            </div><!-- /.direct-chat-msg -->
                          @else
                            <!-- Message to the right -->
                            <div class="direct-chat-msg right read-komen" style="display:none">
                              <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-right">{{ $km->name }}</span>
                                <span class="direct-chat-timestamp pull-left">{{ date_format(date_create($km->waktu),"j M y H:i:s") }}</span>
                              </div><!-- /.direct-chat-info -->
                              <img class="direct-chat-img" src="{{ url($km->image) }}" alt="message user image"><!-- /.direct-chat-img -->
                              <div class="direct-chat-text bg-green">
                                {{ $km->komentar }}
                              </div><!-- /.direct-chat-text -->
                            </div><!-- /.direct-chat-msg -->
                          @endif
                        @endforeach
                        <div class="direct-chat-msg">
                          <button class="btn bg-yellow" style="width:100%;text-align:center" onclick="$('.read-komen').show();$(this).hide();">
                            Tampilkan komentar sebelumnya!
                          </button><!-- /.direct-chat-text -->
                        </div><!-- /.direct-chat-msg -->
                        @if(count($km_unread)>0)
                        <div class="direct-chat-msg">
                          <span class="btn bg-grey read-komen" style="width:100%;text-align:center;display:none">{{ date_format(date_create($km_unread[0]->waktu),"j M y H:i:s") }}</span>
                        </div>
                        @endif
                        @foreach($km_unread as $km)
                          @if($km->id_user==Auth::user()->id_user)
                            <!-- Message. Default to the left -->
                            <div class="direct-chat-msg">
                              <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-left">{{ $km->name }}</span>
                                <span class="direct-chat-timestamp pull-right">{{ date_format(date_create($km->waktu),"j M y H:i:s") }}</span>
                              </div><!-- /.direct-chat-info -->
                              <img class="direct-chat-img" src="{{ url($km->image) }}" alt="message user image"><!-- /.direct-chat-img -->
                              <div class="direct-chat-text">
                                {{ $km->komentar }}
                              </div><!-- /.direct-chat-text -->
                            </div><!-- /.direct-chat-msg -->
                          @else
                            <!-- Message to the right -->
                            <div class="direct-chat-msg right">
                              <div class="direct-chat-info clearfix">
                                <span class="direct-chat-name pull-right">{{ $km->name }}</span>
                                <span class="direct-chat-timestamp pull-left">{{ date_format(date_create($km->waktu),"j M y H:i:s") }}</span>
                              </div><!-- /.direct-chat-info -->
                              <img class="direct-chat-img" src="{{ url($km->image) }}" alt="message user image"><!-- /.direct-chat-img -->
                              <div class="direct-chat-text">
                                {{ $km->komentar }}
                              </div><!-- /.direct-chat-text -->
                            </div><!-- /.direct-chat-msg -->
                          @endif
                        @endforeach
                      </div><!--/.direct-chat-messages-->
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                      <form action="{{ url('makalah/'.$makalah->id_makalah.'/komentar') }}" method="post" id="form-chat" onsubmit="setFormSubmitting()">
                        {!! csrf_field() !!}
                        <div class="input-group">
                          <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                          <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary btn-flat">Send</button>
                          </span>
                        </div>
                      </form>
                    </div><!-- /.box-footer-->
                  </div><!--/.direct-chat -->
                    @endif
                  @endif
                  @if($makalah->status=='plagiat')
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Detail Plagiat</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <table id="tabel_history" class="table table-condensed">
                        <thead>
                          <tr>
                            <th>Id Makalah</th>
                            <th>Plagiat dengan makalah</th>
                            <th>Pada Form</th>
                            <th>Persentase</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $waktu=""; ?>
                          @foreach($history_plagiat as $hp)
                          <?php
                          if ($waktu!=$hp->waktu) {
                            ?>
                            <tr><td colspan="5" class="bg-green" style="text-align:center">{{ date_format(date_create($hp->waktu),"d-m-Y H:i:s") }}</td></tr>
                            <?php
                            $waktu = $hp->waktu;
                          }
                          ?>
                          <tr>
                            <td>{{ $hp->id_makalah_ref }}</td>
                            <td>{{ $hp->judul }}</td>
                            <td>{{ $hp->form }}</td>
                              @if($hp->persentase>=75)
                              <?php $warna='red'; ?>
                              @elseif($hp->persentase>=50)
                              <?php $warna='orange'; ?>
                              @elseif($hp->persentase>=25)
                              <?php $warna='yellow'; ?>
                              @elseif($hp->persentase>=0)
                              <?php $warna='green'; ?>
                              @endif
                            <td><span class="badge bg-{{ $warna }}">{{ $hp->persentase }}%</span></td>
                            <td>
                              <a title="Bandingkan" class="btn btn-xs btn-default" href="{{ url('compare/'.$hp->id_plagiat) }}">Bandingkan <i class="fa fa-search"></i></a>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>Id Makalah</th>
                            <th>Plagiat dengan makalah</th>
                            <th>Pada Form</th>
                            <th>Persentase</th>
                            <th>Action</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div><!-- /.box-body -->
                  </div>
                  @endif
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
      var serialize = $('#form-chat').serialize();

      window.onload = function() {
          window.addEventListener("beforeunload", function (e) {
              if (formSubmitting) {
                  return undefined;
              }

              if (serialize==$('#form-chat').serialize()) {
                  return undefined;
              }

              var confirmationMessage = 'Komentar anda belum terkirim. '
                                      + 'Jika anda pindah halaman sebelum komentar dikirim, komentar yang anda ketik akan hilang.';

              (e || window.event).returnValue = confirmationMessage; //Gecko + IE
              return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
          });
      };

      $(function () {
        $(".select2").select2();
        @if (session('plagiat'))
          $("#plagiat-alert").fadeIn(300);
        @endif
      })
    </script>
@endsection