@extends('layouts.app')

@section('style')
<!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('font-awesome-4.6.1/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('ionicons-2.0.1/css/ionicons.min.css') }}">
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
                User Profile
              </h1>
            </section>

            <!-- Main content -->
            <section class="content">
              
          <div class="row">
            <div class="col-md-12">
              @if(Auth::user()->status==0)
              <div class="box-header with-border">
                <div class="alert alert-info alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-info"></i> Selamat Datang!</h4>
                  Untuk sementara, anda belum bisa beraktifitas di sistem ini, karena status registrasi anda sedang dalam proses aproval oleh admin.
                </div>
              </div>
              @endif
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="{{ url(Auth::user()->image) }}" alt="User profile picture">
                  <h3 class="profile-username text-center">{{ ucfirst(Auth::user()->name) }}</h3>
                  <p class="text-muted text-center">Member since {{ date_format(date_create(Auth::user()->created_at),"M j") }}<sup>{{ date_format(date_create(Auth::user()->created_at),"S") }}</sup>, {{ date_format(date_create(Auth::user()->created_at),"Y") }}</p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>No Pegawai</b> <a class="pull-right">{{ Auth::user()->no_id }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>Email</b> <a class="pull-right">{{ Auth::user()->email }}</a>
                    </li>
                    <li class="list-group-item">
                      <b>No Telp</b> <a class="pull-right">{{ Auth::user()->no_telp }}</a>
                    </li>
                    <li class="list-group-item">
                      <?php
                      $nmak = DB::table('makalah')->where('id_user',Auth::user()->id_user)->count();
                      ?>
                      <b>Post Proposal</b> <a class="pull-right">{{ $nmak }}</a>
                    </li>
                  </ul>

                  <a class="btn btn-primary btn-block" onclick="$('.profile').removeClass('active');$('#tab-edit').addClass('active');$('#edit').addClass('active');"><b>Edit</b></a>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="{{ $tab['timeline'] or '' }} profile"><a href="#timeline" data-toggle="tab">Timeline</a></li>
                  <li class="{{ $tab['edit'] or '' }} profile" id="tab-edit"><a href="#edit" data-toggle="tab">Edit</a></li>
                  <li class="{{ $tab['password'] or '' }} profile"><a href="#password" data-toggle="tab">Change Password</a></li>

                </ul>
                <div class="tab-content">
                  <div class="{{ $tab['timeline'] or '' }} tab-pane profile" id="timeline">
                    <!-- The timeline -->
                    <ul class="timeline timeline-inverse">
                      <?php 
                      $date = "";
            
            $i=0;
                      foreach ($timeline as $tl) {
                        if ($tl->tanggal!=$date) {
              switch($i%6){
                case 0: $warna = "green"; break;
                case 1: $warna = "yellow"; break;
                case 2: $warna = "red"; break;
                case 3: $warna = "blue"; break;
                case 4: $warna = "orange"; break;
                case 5: $warna = "aqua"; break;
              }
              $i++;
                      ?>
                      <!-- timeline time label -->
                      <li class="time-label">
                        <span class="bg-{{ $warna }}">
                          {{ date_format(date_create($tl->tanggal),"M j") }}<sup>{{ date_format(date_create($tl->tanggal),"S") }}</sup>, {{ date_format(date_create($tl->tanggal),"Y") }}
                        </span>
                      </li>
                      <!-- /.timeline-label -->
                      <?php
                          $date = $tl->tanggal;
                        }
                        if(strpos($tl->aksi, "dosen")>-1 || strpos($tl->aksi, "mahasiswa")>-1 || strpos($tl->aksi, "password")>-1 || strpos($tl->aksi, "profil")>-1 || strpos($tl->aksi, "registrasi")>-1){
                          $class = "fa-user bg-aqua";
                        }
                        else if(strpos($tl->aksi, "pengaturan")>-1){
                          $class = "fa-cog bg-red";
                        }
                        else if(strpos($tl->aksi, "kategori")>-1){
                          $class = "fa-bars bg-blue";
                        }
                        else if(strpos($tl->aksi, "Mengomentari")>-1){
                          $class = "fa-comments bg-yellow";
                        }
                        else if(strpos($tl->aksi, "makalah")>-1){
                          $class = "fa-book bg-green";
                        }
                      ?>
                      <!-- timeline item -->
                      <li>
                        <i class="fa {{ $class }}"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> {{ $tl->waktu }}</span>
                          @if(Auth::user()->id_role==1)
                          <h3 class="timeline-header no-border">@if(Auth::user()->id_user!=$tl->id_user)<a href="{{ url(($tl->id_role==2?'Dosen':'mahasiswa').'/'.$tl->id_user) }}">{{ ucwords($tl->name) }}</a> @else Anda @endif{{ $tl->aksi }}. @if(isset($tl->href)) <a href="{{ url($tl->href) }}">Details</a> @endif</h3>
                          @else
                          <h3 class="timeline-header no-border">Anda {{ $tl->aksi }}. @if(isset($tl->href)) <a href="{{ url($tl->href) }}">Details</a> @endif</h3>
                          @endif
                        </div>
                      </li>
                      <!-- END timeline item -->
                      <?php
                      }
                      ?>
                      <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                      </li>
                    </ul>
                  </div><!-- /.tab-pane -->

                  <div class="{{ $tab['edit'] or '' }} tab-pane profile" id="edit">
                    <form enctype="multipart/form-data" class="form-horizontal" id="form-edituser" method="post" action="{{ url('profileupdate') }}" onsubmit="setFormSubmitting1()">
                      {!! csrf_field() !!}
                      <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-10">
                          @if ($errors->has('name'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                          @endif
                          <input type="text" required name="name" class="form-control" id="name" placeholder="Nama" value="{{ Auth::user()->name }}">
                        </div>
                      </div>
                      <div class="form-group {{ $errors->has('no_id') ? ' has-error' : '' }}">
                        <label for="no_id" class="col-sm-2 control-label">Nomor Pegawai</label>
                        <div class="col-sm-10">
                          @if ($errors->has('no_id'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('no_id') }}</strong>
                              </span>
                          @endif
                          <input type="text" required name="no_id" class="form-control" id="no_id" placeholder="Nomor Pegawai" value="{{ Auth::user()->no_id }}">
                        </div>
                      </div>
                      <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                          @if ($errors->has('email'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                          <input type="email" required name="email" class="form-control" id="email" placeholder="Email" value="{{ Auth::user()->email }}">
                        </div>
                      </div>
                      <div class="form-group {{ $errors->has('no_telp') ? ' has-error' : '' }}">
                        <label for="no_telp" class="col-sm-2 control-label">No Telp</label>
                        <div class="col-sm-10">
                          @if ($errors->has('no_telp'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('no_telp') }}</strong>
                              </span>
                          @endif
                          <input type="text" name="no_telp" class="form-control" id="no_telp" placeholder="No Telepon" value="{{ Auth::user()->no_telp }}">
                        </div>
                      </div>
                      <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                        <label for="no_telp" class="col-sm-2 control-label">Photo</label>
                        <div class="col-sm-10">
                          @if ($errors->has('image'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('image') }}</strong>
                              </span>
                          @endif
                          <input type="file" name="image" class="form-control" id="image" placeholder="Photo">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div><!-- /.tab-pane -->

                  <div class="{{ $tab['password'] or '' }} tab-pane profile" id="password">
                    <form class="form-horizontal" id="form-changepwd" method="post" action="{{ url('changepwd') }}" onsubmit="setFormSubmitting2()">
                      {!! csrf_field() !!}
                      <div class="form-group {{ $errors->has('old_password') ? ' has-error' : '' }}">
                        <label for="old_password" class="col-sm-2 control-label">Password Lama</label>
                        <div class="col-sm-10">
                          @if ($errors->has('old_password'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('old_password') }}</strong>
                              </span>
                          @endif
                          <input type="password" required class="form-control" id="old_password" name="old_password" placeholder="Password Lama">
                        </div>
                      </div>
                      <div class="form-group {{ $errors->has('new_password') ? ' has-error' : '' }}">
                        <label for="new_password" class="col-sm-2 control-label">Password Baru</label>
                        <div class="col-sm-10">
                          @if ($errors->has('new_password'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('new_password') }}</strong>
                              </span>
                          @endif
                          <input type="password" required class="form-control" id="new_password" name="new_password" placeholder="Password Baru">
                        </div>
                      </div>
                      <div class="form-group {{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                        <label for="new_password_confirmation" class="col-sm-2 control-label">Konfirmasi</label>
                        <div class="col-sm-10">
                          @if ($errors->has('new_password_confirmation'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                              </span>
                          @endif
                          <input type="password" required class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ketik Ulang">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div><!-- /.tab-pane -->
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
              <div class="box-header with-border">
                <div class="alert alert-danger alert-dismissable" id="gagal" style="display:none">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-ban"></i> Gagal!</h4>
                  Transaksi Gagal!
                </div>
                <div class="alert alert-success alert-dismissable" id="berhasil" style="display:none">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4>  <i class="icon fa fa-check"></i> Sukses!</h4>
                  Transaksi Berhasil!
                </div>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->

            </section><!-- /.content -->
@endsection

@section('script')
<!-- jQuery 2.1.4 -->
    <script src="{{asset('plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
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
      var serialize1 = $('#form-edituser').serialize();
      var serialize2 = $('#form-changepwd').serialize();

      window.onload = function() {
          window.addEventListener("beforeunload", function (e) {
              if ((formSubmitting1 && serialize2==$('#form-changepwd').serialize()) || (formSubmitting2 && serialize1==$('#form-edituser').serialize())) {
                  return undefined;
              }

              if (serialize1==$('#form-edituser').serialize() && serialize2==$('#form-changepwd').serialize()) {
                  return undefined;
              }

              var confirmationMessage = 'Sepertinya anda telah mengubah sesuatu pada halaman ini. '
                                      + 'Jika anda pindah halaman sebelum perubahan ini disimpan, maka perubahan ini akan hilang.';

              (e || window.event).returnValue = confirmationMessage; //Gecko + IE
              return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
          });
      };
        @if (session('berhasil'))
          @if (session('berhasil')=="berhasil")
            $("#berhasil").fadeIn(300).delay(2000).fadeOut(300);
          @elseif(session('berhasil')=="gagal")
            $("#gagal").fadeIn(300).delay(2000).fadeOut(300);
          @endif
        @endif
    </script>
@endsection