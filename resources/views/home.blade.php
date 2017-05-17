@extends('layouts.app')

@section('style')
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('font-awesome-4.6.1/css/font-awesome.min.css')}}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ asset('ionicons-2.0.1/css/ionicons.min.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset('plugins/iCheck/flat/blue.css') }}">
        <!-- Morris chart -->
        <link rel="stylesheet" href="{{ asset('plugins/morris/morris.css') }}">
        <!-- jvectormap -->
        <link rel="stylesheet" href="{{ asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
        <!-- Date Picker -->
        <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker-bs3.css') }}">
        <!-- bootstrap wysihtml5 - text dosen -->
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
@endsection

@section('name')
  {{ Auth::user()->name }}
@endsection

@section('content')
            <section class="content-header">
              <h1>
                Dashboard
                <small>SKripsi akan dianggap <span class="badge bg-orange">Plagiat</span> jika terdapat kemiripan dengan skripsi lain sebesar <span class="badge bg-red">>={{ $persentase }}%</span> pada bagian form <span class="badge bg-green">{{ $form_makalah }}</span></small>
              </h1>
            </section>

            <!-- Main content -->
            <section class="content">
              <!-- Small boxes (Stat box) -->
              <div class="row">
                @if(Auth::user()->id_role!=2)
                <div class="col-lg-6 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-orange">
                    <div class="inner">
                      <h3>{{ $n_plagiat or "0"}}</h3>
                      <p>Terdeteksi Plagiat</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-alert-circled"></i>
                    </div>
                    <a href="{{url('/plagiat')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div><!-- ./col -->
                <div class="col-lg-6 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-green">
                    <div class="inner">
                      <h3>{{ $n_lolos or "0" }}</h3>
                      <p>Lolos Plagiat</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-log-out"></i>
                    </div>
                    <a href="{{url('/lolos')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div><!-- ./col -->
                @endif
                <div class="col-lg-4 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>{{ $n_diterima or "0" }}</h3>
                      <p>Skripsi diterima</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-checkmark-circled"></i>
                    </div>
                    <a href="{{url('/diterima')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div><!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-yellow">
                    <div class="inner">
                      <h3>{{ $n_revisi or "0" }}</h3>
                      <p>Skripsi Revisi</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-edit"></i>
                    </div>
                    <a href="{{url('/revisi')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div><!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                      <h3>{{ $n_ditolak or "0" }}</h3>
                      <p>Skripsi ditolak</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-close-circled"></i>
                    </div>
                    <a href="{{url('/ditolak')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div><!-- ./col -->
              </div><!-- /.row -->
              <!-- Main row -->
              <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable">

                  <!-- Calendar -->
                  <div class="box box-success">
                    <div class="box-header with-border">
                      <h3 class="box-title">Timeline</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
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
                          if(strpos($tl->aksi, "dosen")>-1 || strpos($tl->aksi, "penulis")>-1 || strpos($tl->aksi, "password")>-1 || strpos($tl->aksi, "profil")>-1 || strpos($tl->aksi, "registrasi")>-1){
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
                          else if(strpos($tl->aksi, "skripsi")>-1){
                            $class = "fa-book bg-green";
                          }
                        ?>
                        <!-- timeline item -->
                        <li>
                        <i class="fa {{ $class }}"></i>
                        <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i> {{ $tl->waktu }}</span>
                          @if(Auth::user()->id_role==1)
                          <h3 class="timeline-header no-border">@if(Auth::user()->id_user!=$tl->id_user)<a href="{{ url(($tl->id_role==2?'dosen':'penulis').'/'.$tl->id_user) }}">{{ ucwords($tl->name) }}</a> @else Anda @endif{{ $tl->aksi }}. @if(isset($tl->href)) <a href="{{ url($tl->href) }}">Details</a> @endif</h3>
                          @else
                          <h3 class="timeline-header no-border">Anda {{ $tl->aksi }}. @if(isset($tl->href)) <a href="{{ url($tl->href) }}">Details</a> @endif</h3>
                          @endif
                        </div>
                      </li>
                        <!-- END timeline item -->
                        <?php
                        }
                        ?>
                        <li onclick="window.location.href='{{ url('profile/timeline') }}'">
                          <i class="fa bg-gray" style="cursor:pointer"><strong>...</strong></i>
                        </li>
                      </ul>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->

                </section><!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-5 connectedSortable">
                  <!-- Custom tabs (Charts with tabs)-->
                  <div class="box box-success">
                    <div class="box-header with-border">
                      <h3 class="box-title">Summary</h3>
                    </div>
                    <div class="box-body">
                      <div class="chart">
                        <canvas id="barChart" style="height:230px"></canvas>
                      </div>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->

                </section><!-- right col -->
              </div><!-- /.row (main row) -->
              <div class="row">
                <div class="col-xs-12">
                  <div class="box">
                    <div class="box-header with-border">
                      <h3 class="box-title">10 Skripsi terbaru</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <table id="tabel_makalah" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Penulis</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>dosen Utama</th>
                            <th>dosen Pengembang</th>
							<th>Waktu Upload</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($makalah as $mk)
                            <tr>
                              <td><a href="{{ url('profileuser/'.$mk->id_user) }}">{{ $mk->name }}</a></td>
                              <td>{{ $mk->judul }}</td>
                              <td>{{ $mk->kategori }}</td>
                              <td>{{ $mk->dosen1 }}</td>
                              <td>{{ $mk->dosen2 }}</td>
							  <td>{{ date_format(date_create($mk->upload),"Y-m-d H:i:s") }}</td>

                                @if($mk->status=='plagiat')
                                <?php $warna='orange'; ?>
                                @elseif($mk->status=='lolos')
                                <?php $warna='green'; ?>
                                @elseif($mk->status=='diterima')
                                <?php $warna='blue'; ?>
                                @elseif($mk->status=='revisi')
                                <?php $warna='yellow'; ?>
                                @elseif($mk->status=='ditolak')
                                <?php $warna='red'; ?>
                                @endif

                              @if(Auth::user()->id_role == 1 && ($mk->status=='lolos' || $mk->status=='revisi'))
                              <td>
                                <div class="btn-group">
                                  <button type="button" class="btn btn-xs bg-{{ $warna }}">{{ ucfirst($mk->status) }}</button>
                                  <button type="button" class="btn btn-xs bg-{{ $warna }} dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <ul class="dropdown-menu" role="menu">
                                    {!!Form::model($mk, [
                                      "method" => "patch",
                                      "route" => ["makalah.update",$mk->id_makalah],
                                      "id" => "formid".$mk->id_makalah
                                    ])!!}
                                    {!! Form::hidden('id_status',$mk->id_status,array('id' => 'id_status'.$mk->id_makalah)) !!}
                                    {!! Form::close() !!}
                                    @if($mk->status!="diterima")
                                      <li><a class="btn bg-blue" href="#" onclick="if(confirm('Anda yakin akan mengubah status skripsi {{ $mk->id_makalah }} DITERIMA?\nPerubahan ini tidak bisa dikembalikan!')){$('#id_status{{$mk->id_makalah}}').val(2);$('#formid{{$mk->id_makalah}}').submit();}">Diterima</a></li>
                                    @endif
                                    @if($mk->status!="revisi")
                                      <li><a class="btn bg-yellow" href="#" onclick="if(confirm('Anda yakin akan mengubah status skripsi {{ $mk->id_makalah }} REVISI?')){$('#id_status{{$mk->id_makalah}}').val(3);$('#formid{{$mk->id_makalah}}').submit();}">Revisi</a></li>
                                    @endif
                                    @if($mk->status!="ditolak")
                                      <li><a class="btn bg-red" href="#" onclick="if(confirm('Anda yakin akan mengubah status skripsi {{ $mk->id_makalah }} DITERIMA?\n

 ini tidak bisa dikembalikan!')){$('#id_status{{$mk->id_makalah}}').val(4);$('#formid{{$mk->id_makalah}}').submit();}">Ditolak</a></li>
                                    @endif
                                  </ul>
                                </div>
                              </td>
                              @else
                              <td>
                                <!-- <span class="label bg-{{ $warna }}">{{ ucfirst($mk->status) }}</span> -->
                                <button type="button" class="btn btn-xs bg-{{ $warna }}">{{ ucfirst($mk->status) }}</button>
                              </td>
                              @endif
                              <td>
                                @if(Auth::user()->id_role==1)
                                {!! Form::open([
                                    'method' => 'delete',
                                    'route' => ['makalah.destroy',$mk->id_makalah],
                                    'id' => 'formdelete'.$mk->id_makalah
                                ]) !!}
                                {!!Form::close()!!}
                                <a title="Download skripsi{{ $mk->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$mk->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                                <a title="Detail" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah) }}"><i class="fa fa-search"></i></a>
                                <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                                <a title="Delete" class="btn btn-xs btn-default" href="#" onclick="if(confirm('Apakah anda yakin akan menghapus skripsi {{ $mk->id_makalah }}?')){ $('#formdelete{{$mk->id_makalah}}').submit(); }"><i class="fa fa-close"></i></a>
								@if($mk->status=='diterima')
								<a title="Plot dosen" class="btn btn-xs btn-default" href="{{ url('plotdosen/'.$mk->id_makalah) }}">plot dosen</a>
								@endif
                                @elseif(Auth::user()->id_role==3 && Auth::user()->id_user==$mk->id_user)
                                <a title="Download skripsi{{ $mk->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$mk->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                                <a title="Detail" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah) }}"><i class="fa fa-search"></i></a>
                                <a title="Edit" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah.'/edit') }}"><i class="fa fa-edit"></i></a>
                                @else
                                <a title="Download skripsi{{ $mk->id_makalah }}.pdf" class="btn btn-xs btn-default" target="_blank" href="{{ url('makalah/'.$mk->id_makalah.'/pdf') }}"><i class="fa fa-file-pdf-o"></i></a>
                                <a title="Detail" class="btn btn-xs btn-default" href="{{ url('makalah/'.$mk->id_makalah) }}"><i class="fa fa-search"></i></a>
                                @endif
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>Penulis</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>dosen Utama</th>
                            <th>dosen Pengembang</th>
							<th>Waktu Upload</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div><!-- /.box-body -->
                  </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->              
            </section>
@endsection

@section('script')
        <!-- jQuery 2.1.4 -->
        <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset('plugins/jQueryUI/jquery-ui.min.js') }}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
          $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.5 -->
        <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- DataTables -->
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
        <!-- Sparkline -->
        <script src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- jvectormap -->
        <script src="{{ asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script src="{{ asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{ asset('plugins/knob/jquery.knob.js') }}"></script>
        <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
        <!-- datepicker -->
        <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
        <!-- Slimscroll -->
        <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
        <!-- ChartJS 1.0.1 -->
        <script src="{{ asset('plugins/chartjs/Chart.min.js') }}"></script>
        <!-- FastClick -->
        <script src="{{ asset('plugins/fastclick/fastclick.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('dist/js/app.min.js') }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset('dist/js/demo.js') }}"></script>
        <script type="text/javascript">
          $(function() {
            $("#tabel_makalah").DataTable({
				"order": [[ 5, "desc" ]]
			});
            var areaChartData = {
              labels: ["Skripsi"],
              datasets: [
				@if(Auth::user()->id_role!=2)
                {
                  label: "Plagiat",
                  fillColor: "rgb(255, 133, 27)",
                  strokeColor: "rgba(210, 214, 222, 0)",
                  pointColor: "rgba(210, 214, 222, 1)",
                  pointStrokeColor: "#c1c7d1",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(220,220,220,1)",
                  data: [{{ $n_plagiat }}]
                },
                {
                  label: "Lolos",
                  fillColor: "rgb(0, 166, 90)",
                  strokeColor: "rgba(210, 214, 222, 0)",
                  pointColor: "rgba(210, 214, 222, 1)",
                  pointStrokeColor: "#c1c7d1",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(220,220,220,1)",
                  data: [{{ $n_lolos }}]
                },
				@endif
                {
                  label: "Diterima",
                  fillColor: "rgb(0, 192, 239)",
                  strokeColor: "rgba(210, 214, 222, 0)",
                  pointColor: "rgba(210, 214, 222, 1)",
                  pointStrokeColor: "#c1c7d1",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(220,220,220,1)",
                  data: [{{ $n_diterima }}]
                },
                {
                  label: "Revisi",
                  fillColor: "rgb(243, 156, 18)",
                  strokeColor: "rgba(210, 214, 222, 0)",
                  pointColor: "rgba(210, 214, 222, 1)",
                  pointStrokeColor: "#c1c7d1",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(220,220,220,1)",
                  data: [{{ $n_revisi }}]
                },
                {
                  label: "Ditolak",
                  fillColor: "rgb(221, 75, 57)",
                  strokeColor: "rgba(60,141,188,0)",
                  pointColor: "#3b8bba",
                  pointStrokeColor: "rgba(60,141,188,1)",
                  pointHighlightFill: "#fff",
                  pointHighlightStroke: "rgba(60,141,188,1)",
                  data: [{{ $n_ditolak }}]
                }
              ]
            };

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $("#barChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = areaChartData;
            barChartData.datasets[1].fillColor = "#00a65a";
            barChartData.datasets[1].strokeColor = "#00a65a";
            barChartData.datasets[1].pointColor = "#00a65a";
            var barChartOptions = {
              //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
              scaleBeginAtZero: true,
              //Boolean - Whether grid lines are shown across the chart
              scaleShowGridLines: true,
              //String - Colour of the grid lines
              scaleGridLineColor: "rgba(0,0,0,.05)",
              //Number - Width of the grid lines
              scaleGridLineWidth: 1,
              //Boolean - Whether to show horizontal lines (except X axis)
              scaleShowHorizontalLines: true,
              //Boolean - Whether to show vertical lines (except Y axis)
              scaleShowVerticalLines: true,
              //Boolean - If there is a stroke on each bar
              barShowStroke: true,
              //Number - Pixel width of the bar stroke
              barStrokeWidth: 2,
              //Number - Spacing between each of the X value sets
              barValueSpacing: 5,
              //Number - Spacing between data sets within X values
              barDatasetSpacing: 1,
              //String - A legend template
              legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
              //Boolean - whether to make the chart responsive
              responsive: true,
              maintainAspectRatio: true
            };

            barChartOptions.datasetFill = false;
            barChart.Bar(barChartData, barChartOptions);
          });
        </script>
@endsection