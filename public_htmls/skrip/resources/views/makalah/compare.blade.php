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
                      <h3 class="box-title">Detail Makalah {{$makalah1->id_makalah}} & {{$makalah2->id_makalah}}</h3>
                    </div><!-- /.box-header -->
                      <div class="box-body">
                        <table class="table table-bordered">
                            @if($makalah1->status=='plagiat')
                            <?php $warna1='orange'; ?>
                            @elseif($makalah1->status=='lolos')
                            <?php $warna1='green'; ?>
                            @elseif($makalah1->status=='diterima')
                            <?php $warna1='blue'; ?>
                            @elseif($makalah1->status=='revisi')
                            <?php $warna1='yellow'; ?>
                            @elseif($makalah1->status=='ditolak')
                            <?php $warna1='red'; ?>
                            @endif
                            @if($makalah2->status=='plagiat')
                            <?php $warna2='orange'; ?>
                            @elseif($makalah2->status=='lolos')
                            <?php $warna2='green'; ?>
                            @elseif($makalah2->status=='diterima')
                            <?php $warna2='blue'; ?>
                            @elseif($makalah2->status=='revisi')
                            <?php $warna2='yellow'; ?>
                            @elseif($makalah2->status=='ditolak')
                            <?php $warna2='red'; ?>
                            @endif
                        <tr>
                          <th style="width:15%"></th>
                          <th style="width:40%">Makalah {{$makalah1->id_makalah}}</th>
                          <th style="width:5%">Persentase</th>
                          <th style="width:40%">Makalah {{$makalah2->id_makalah}}</th>
                        </tr>
                        <tr>
                          <th>Action</th>
                          <td>
                            @if(Auth::user()->id_role==1)
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['makalah.destroy',$makalah1->id_makalah],
                                'id' => 'formdelete'.$makalah1->id_makalah
                            ]) !!}
                            {!!Form::close()!!}
              							<a title="Download makalah{{ $makalah1->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah1->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$makalah1->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Delete" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus makalah {{ $makalah1->id_makalah }}?')){ $('#formdelete{{ $makalah1->id_makalah }}').submit(); }"><i class="fa fa-close"></i></a>
              							@if($makalah1->status=='diterima')
              							<a title="Plot Editor" class="btn btn-xs btn-default" href="{{ url('ploteditor/'.$makalah1->id_makalah) }}">plot editor</a>
              							@endif
              							@elseif(Auth::user()->id_role==3 && Auth::user()->id_user==$makalah1->id_user)
              							<a title="Download makalah{{ $makalah1->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah1->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$makalah1->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
              							@else
				                    <a title="Download makalah{{ $makalah1->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah1->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            @endif
                          </td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <th>Status</th>
                          @if(Auth::user()->id_role == 1 && $makalah1->status=='lolos')
                          <td>
                            <div class="btn-group">
                              <button type="button" class="btn btn-xs bg-{{ $warna1 }}">{{ ucfirst($makalah1->status) }}</button>
                              <button type="button" class="btn btn-xs bg-{{ $warna1 }} dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <ul class="dropdown-menu" role="menu">
                                {!!Form::model($makalah1, [
                                  "method" => "patch",
                                  "route" => ["makalah.update",$makalah1->id_makalah],
                                  "id" => "formid".$makalah1->id_makalah
                                ])!!}
                                {!! Form::hidden('id_status',$makalah1->id_status,array('id' => 'id_status'.$makalah1->id_makalah)) !!}
                                {!! Form::close() !!}
                                @if($makalah1->status!="diterima")
                                  <li><a class="btn bg-blue" href="#" onclick="if(confirm('Anda yakin akan mengubah status makalah {{ $makalah1->id_makalah }} DITERIMA?\nPerubahan ini tidak bisa dikembalikan!')){$('#id_status{{$makalah1->id_makalah}}').val(2);$('#formid{{$makalah1->id_makalah}}').submit();}">Diterima</a></li>
                                @endif
                                @if($makalah1->status!="revisi")
                                  <li><a class="btn bg-yellow" href="#" onclick="if(confirm('Anda yakin akan mengubah status makalah {{ $makalah1->id_makalah }} REVISI?\nPerubahan ini tidak bisa dikembalikan!')){$('#id_status{{$makalah1->id_makalah}}').val(3);$('#formid{{$makalah1->id_makalah}}').submit();}">Revisi</a></li>
                                @endif
                                @if($makalah1->status!="ditolak")
                                  <li><a class="btn bg-red" href="#" onclick="if(confirm('Anda yakin akan mengubah status makalah {{ $makalah1->id_makalah }} DITERIMA?\nPerubahan ini tidak bisa dikembalikan!')){$('#id_status{{$makalah1->id_makalah}}').val(4);$('#formid{{$makalah1->id_makalah}}').submit();}">Ditolak</a></li>
                                @endif
                              </ul>
                            </div>
                          </td>
                          @else
                          <td>
                            <!-- <span class="label bg-{{ $warna1 }}">{{ ucfirst($makalah1->status) }}</span> -->
                            <button type="button" class="btn btn-xs bg-{{ $warna1 }}">{{ ucfirst($makalah1->status) }}</button>
                          </td>
                          @endif
                          <td></td>
                          <td>
                            <!-- <span class="label bg-{{ $warna1 }}">{{ ucfirst($makalah1->status) }}</span> -->
                            <button type="button" class="btn btn-xs bg-{{ $warna2 }}">{{ ucfirst($makalah2->status) }}</button>
                          </td>
                        </tr>
                        <tr>
                          <th>Kategori Makalah</th>
                          <td>
                            @foreach($makalah1->kategori as $key => $val)
                              <a href="{{ url('katmakalah/'.$makalah1->id_kategori[$key]) }}"><span class="badge bg-blue">{{ $val }}</span></a>, 
                            @endforeach
                          </td>
                          <td></td>
                          <td>
                            @foreach($makalah2->kategori as $key => $val)
                              <a href="{{ url('katmakalah/'.$makalah2->id_kategori[$key]) }}"><span class="badge bg-blue">{{ $val }}</span></a>, 
                            @endforeach
                          </td>
                        </tr><!-- /.form-group -->
                        <tr>
                          <th>Editor Utama</th>
                          <td>{{ $makalah1->editor1 }}</td>
                          <td></td>
                          <td>{{ $makalah2->editor1 }}</td>
                        </tr>
                        <tr>
                          <th>Editor Pengembang</th>
                          <td>{{ $makalah1->editor2 }}</td>
                          <td></td>
                          <td>{{ $makalah2->editor2 }}</td>
                        </tr>
                        <tr>
                          <th>Penulis</th>
                          <td>{{ $makalah1->name }}</td>
                          <td></td>
                          <td>{{ $makalah2->name }}</td>
                        </tr>
                        <tr>
                          <th>Judul</th>
                          <td>{{ $makalah1->judul }}</td>
                          @if(isset($persentase['judul']))
                            @if($persentase['judul']>=75)
                            <?php $warna='red'; ?>
                            @elseif($persentase['judul']>=50)
                            <?php $warna='orange'; ?>
                            @elseif($persentase['judul']>=25)
                            <?php $warna='yellow'; ?>
                            @elseif($persentase['judul']>=0)
                            <?php $warna='green'; ?>
                            @endif
                          <td><span class="badge bg-{{ $warna }}">{{ $persentase['judul'] }}%</span></td>
                          @else
                          <td></td>
                          @endif
                          <td>{{ $makalah2->judul }}</td>
                        </tr>
                        <tr>
                          <th>Abstrak</th>
                          <td>{{ $makalah1->abstrak }}</td>
                          @if(isset($persentase['abstrak']))
                            @if($persentase['abstrak']>=75)
                            <?php $warna='red'; ?>
                            @elseif($persentase['abstrak']>=50)
                            <?php $warna='orange'; ?>
                            @elseif($persentase['abstrak']>=25)
                            <?php $warna='yellow'; ?>
                            @elseif($persentase['abstrak']>=0)
                            <?php $warna='green'; ?>
                            @endif
                          <td><span class="badge bg-{{ $warna }}">{{ $persentase['abstrak'] }}%</span></td>
                          @else
                          <td></td>
                          @endif
                          <td>{{ $makalah2->abstrak }}</td>
                        </tr>
                        <tr>
                          <th>Permasalahan</th>
                          <td>{{ $makalah1->permasalahan }}</td>
                          @if(isset($persentase['permasalahan']))
                            @if($persentase['permasalahan']>=75)
                            <?php $warna='red'; ?>
                            @elseif($persentase['permasalahan']>=50)
                            <?php $warna='orange'; ?>
                            @elseif($persentase['permasalahan']>=25)
                            <?php $warna='yellow'; ?>
                            @elseif($persentase['permasalahan']>=0)
                            <?php $warna='green'; ?>
                            @endif
                          <td><span class="badge bg-{{ $warna }}">{{ $persentase['permasalahan'] }}%</span></td>
                          @else
                          <td></td>
                          @endif
                          <td>{{ $makalah2->permasalahan }}</td>
                        </tr>
                        <tr>
                          <th>Tujuan</th>
                          <td>{{ $makalah1->tujuan }}</td>
                          @if(isset($persentase['tujuan']))
                            @if($persentase['tujuan']>=75)
                            <?php $warna='red'; ?>
                            @elseif($persentase['tujuan']>=50)
                            <?php $warna='orange'; ?>
                            @elseif($persentase['tujuan']>=25)
                            <?php $warna='yellow'; ?>
                            @elseif($persentase['tujuan']>=0)
                            <?php $warna='green'; ?>
                            @endif
                          <td><span class="badge bg-{{ $warna }}">{{ $persentase['tujuan'] }}%</span></td>
                          @else
                          <td></td>
                          @endif
                          <td>{{ $makalah2->tujuan }}</td>
                        </tr>
                        <tr>
                          <th>Tinjauan pustaka</th>
                          <td>{{ $makalah1->tinjauan_pustaka }}</td>
                          @if(isset($persentase['tinjauan_pustaka']))
                            @if($persentase['tinjauan_pustaka']>=75)
                            <?php $warna='red'; ?>
                            @elseif($persentase['tinjauan_pustaka']>=50)
                            <?php $warna='orange'; ?>
                            @elseif($persentase['tinjauan_pustaka']>=25)
                            <?php $warna='yellow'; ?>
                            @elseif($persentase['tinjauan_pustaka']>=0)
                            <?php $warna='green'; ?>
                            @endif
                          <td><span class="badge bg-{{ $warna }}">{{ $persentase['tinjauan_pustaka'] }}%</span></td>
                          @else
                          <td></td>
                          @endif
                          <td>{{ $makalah2->tinjauan_pustaka }}</td>
                        </tr>
                        <tr>
                          <th>Kesimpulan Sementara</th>
                          <td>{{ $makalah1->kesimpulan_sementara }}</td>
                          @if(isset($persentase['kesimpulan_sementara']))
                            @if($persentase['kesimpulan_sementara']>=75)
                            <?php $warna='red'; ?>
                            @elseif($persentase['kesimpulan_sementara']>=50)
                            <?php $warna='orange'; ?>
                            @elseif($persentase['kesimpulan_sementara']>=25)
                            <?php $warna='yellow'; ?>
                            @elseif($persentase['kesimpulan_sementara']>=0)
                            <?php $warna='green'; ?>
                            @endif
                          <td><span class="badge bg-{{ $warna }}">{{ $persentase['kesimpulan_sementara'] }}%</span></td>
                          @else
                          <td></td>
                          @endif
                          <td>{{ $makalah2->kesimpulan_sementara }}</td>
                        </tr>
                        <tr>
                          <th>Daftar Pustaka</th>
                          <td>{{ $makalah1->daftar_pustaka }}</td>
                          @if(isset($persentase['daftar_pustaka']))
                            @if($persentase['daftar_pustaka']>=75)
                            <?php $warna='red'; ?>
                            @elseif($persentase['daftar_pustaka']>=50)
                            <?php $warna='orange'; ?>
                            @elseif($persentase['daftar_pustaka']>=25)
                            <?php $warna='yellow'; ?>
                            @elseif($persentase['daftar_pustaka']>=0)
                            <?php $warna='green'; ?>
                            @endif
                          <td><span class="badge bg-{{ $warna }}">{{ $persentase['daftar_pustaka'] }}%</span></td>
                          @else
                          <td></td>
                          @endif
                          <td>{{ $makalah2->daftar_pustaka }}</td>
                        </tr>
                        <tr>
                          <th>Diunggah tanggal</th>
                          <td>{{ date_format(date_create($makalah1->created_at),"d-m-Y") }}</td>
                          <td></td>
                          <td>{{ date_format(date_create($makalah2->created_at),"d-m-Y") }}</td>
                        </tr>
                        <tr>
                          <th>Diupdate tanggal</th>
                          <td>{{ date_format(date_create($makalah1->updated_at),"d-m-Y") }}</td>
                          <td></td>
                          <td>{{ date_format(date_create($makalah2->updated_at),"d-m-Y") }}</td>
                        </tr>
                        <tr>
                          <th>Action</th>
                          <td>
                            @if(Auth::user()->id_role==1)
                            {!! Form::open([
                                'method' => 'delete',
                                'route' => ['makalah.destroy',$makalah1->id_makalah],
                                'id' => 'formdelete'.$makalah1->id_makalah
                            ]) !!}
                            {!!Form::close()!!}
                            <a title="Download makalah{{ $makalah1->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah1->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$makalah1->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                            <a title="Delete" class="btn btn-xs btn-default" onclick="if(confirm('Apakah anda yakin akan menghapus makalah {{ $makalah1->id_makalah }}?')){ $('#formdelete{{ $makalah1->id_makalah }}').submit(); }"><i class="fa fa-close"></i></a>
                            @if($makalah1->status=='diterima')
                            <a title="Plot Editor" class="btn btn-xs btn-default" href="{{ url('ploteditor/'.$makalah1->id_makalah) }}">plot editor</a>
                            @endif
                            @elseif(Auth::user()->id_role==3 && Auth::user()->id_user==$makalah1->id_user)
                            <a title="Download makalah{{ $makalah1->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah1->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$makalah1->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                            @else
                            <a title="Download makalah{{ $makalah1->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$makalah1->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                            @endif
                          </td>
                          <td></td>
                          <td></td>
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