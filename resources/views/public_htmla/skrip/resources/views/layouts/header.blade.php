        <header class="main-header">
            <!-- Logo -->
            <a href="{{url('/')}}" class="logo">
              <!-- mini logo for sidebar mini 50x50 pixels -->
              <span class="logo-mini"><b>DM</b>P</span>
              <!-- logo for regular state and mobile devices -->
              <span class="logo-lg"><b>Plagiarisme</b> Sistem</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
              <!-- Sidebar toggle button-->
              <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
              </a>
              <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                  <!-- Messages: style can be found in dropdown.less-->
                    <?php
        $km_unread=null;
        $newmessage=null;
        if (Auth::user()->id_role==1) {
            $km_unread = DB::table('komentar_makalah as k')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['k.readby','not like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $newmessage = DB::table('komentar_makalah as k')
                ->where([
                    ['k.readby','not like','%('.Auth::user()->id_user.')%'],
                    ])
                ->count();
        }
        else if(Auth::user()->id_role==2){
            $km_unread = DB::table('komentar_makalah as k')
                ->leftjoin('editor_makalah as em','em.id_makalah','=','k.id_makalah')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['em.id_user',Auth::user()->id_user],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $newmessage = DB::table('komentar_makalah as k')
                ->leftjoin('editor_makalah as em','em.id_makalah','=','k.id_makalah')
                ->where([
                    ['em.id_user',Auth::user()->id_user],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%'],
                    ])
                ->count();
        }
        else if(Auth::user()->id_role==3){
            $km_unread = DB::table('komentar_makalah as k')
                ->leftjoin('makalah as m','m.id_makalah','=','k.id_makalah')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['m.id_user',Auth::user()->id_user],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $newmessage = DB::table('komentar_makalah as k')
                ->leftjoin('makalah as m','m.id_makalah','=','k.id_makalah')
                ->where([
                    ['m.id_user',Auth::user()->id_user],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%'],
                    ])
                ->count();
        }

                    ?>
                  <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-envelope-o"></i>
                      @if($newmessage>0)
                      <span class="label label-success">{{ $newmessage }}</span>
                      @endif
                    </a>
                    <ul class="dropdown-menu">
                      <li class="header">Anda memiliki {{ $newmessage or '0' }} komentar baru</li>
                      <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">
                          @foreach($km_unread as $km)
                          <li><!-- start message -->
                            <a href="{{ url('makalah/'.$km->id_makalah) }}">
                              <div class="pull-left">
                                <img src="{{url($km->image)}}" class="img-circle" alt="User Image">
                              </div>
                              <h4>
                                {{ $km->name }}
                              </h4>
                              <p>{{ $km->komentar }}</p>
                            </a>
                          </li><!-- end message -->
                          @endforeach
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <!-- Notifications: style can be found in dropdown.less -->
                  <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-bell-o"></i>
                      @if(Auth::user()->status==1)
                      <span class="label label-warning">
                      @if(Auth::user()->id_role!=2)
                        5
                      @else
                        3
                      @endif
                      </span>
                      @endif
                    </a>
                    <?php
        if(Auth::user()->id_role==1){
                $n_plagiat = DB::table('makalah')->where('id_status',0)->count();
                $n_lolos = DB::table('makalah')->where('id_status',1)->count();
                $n_diterima = DB::table('makalah')->where('id_status',2)->count();
                $n_revisi = DB::table('makalah')->where('id_status',3)->count();
                $n_ditolak = DB::table('makalah')->where('id_status',4)->count();
        }
        else if(Auth::user()->id_role==2){
                $n_diterima = DB::table('makalah as m')
                    ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                    ->where([
                        ['e.id_user',Auth::user()->id_user],
                        ['m.id_status', 2]
                        ])
                    ->count();
                $n_revisi = DB::table('makalah as m')
                    ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                    ->where([
                        ['e.id_user',Auth::user()->id_user],
                        ['m.id_status', 3]
                        ])
                    ->count();
                $n_ditolak = DB::table('makalah as m')
                    ->join('editor_makalah as e', 'e.id_makalah', '=', 'm.id_makalah')
                    ->where([
                        ['e.id_user',Auth::user()->id_user],
                        ['m.id_status', 4]
                        ])
                    ->count();
        }
        else if(Auth::user()->id_role==3){
                $n_plagiat = DB::table('makalah as m')
                    ->where([
                        ['m.id_user',Auth::user()->id_user],
                        ['m.id_status', 0]
                        ])
                    ->count();
                $n_lolos = DB::table('makalah as m')
                    ->where([
                        ['m.id_status', 1]
                        ])
                    ->count();
                $n_diterima = DB::table('makalah as m')
                    ->where([
                        ['m.id_status', 2]
                        ])
                    ->count();
                $n_revisi = DB::table('makalah as m')
                    ->where([
                        ['m.id_status', 3]
                        ])
                    ->count();
                $n_ditolak = DB::table('makalah as m')
                    ->where([
                        ['m.id_status', 4]
                        ])
                    ->count();
        }

                    ?>
                    <ul class="dropdown-menu">
                      <li>
                        <!-- inner menu: contains the actual data -->
                        <ul class="menu">
                          @if(Auth::user()->status==1)
                          @if(Auth::user()->id_role!=2)
                          <li>
                            <a href="{{ url('plagiat') }}">
                              <i class="fa fa-warning text-orange"></i> {{ $n_plagiat or "0" }} Dokumen terdeteksi plagiat
                            </a>
                          </li>
                          <li>
                            <a href="{{ url('lolos') }}">
                              <i class="ion ion-log-out text-green"></i> {{ $n_lolos or "0" }} Dokumen lolos plagiat
                            </a>
                          </li>
                          @endif
                          <li>
                            <a href="{{ url('diterima') }}">
                              <i class="ion ion-checkmark-circled text-aqua"></i> {{ $n_diterima or "0" }} Dokumen status diterima
                            </a>
                          </li>
                          <li>
                            <a href="{{ url('revisi') }}">
                              <i class="ion ion-edit text-yellow"></i> {{ $n_revisi or "0" }} Dokumen status revisi
                            </a>
                          </li>
                          <li>
                            <a href="{{ url('ditolak') }}">
                              <i class="ion ion-close-circled text-red"></i> {{ $n_ditolak or "0" }} Dokumen status ditolak
                            </a>
                          </li>
                          @endif
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <!-- User Account: style can be found in dropdown.less -->
                  <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <img src="{{url(Auth::user()->image)}}" class="user-image" alt="User Image">
                      <span class="hidden-xs">{{ ucwords(Auth::user()->name) }}</span>
                    </a>
                    <ul class="dropdown-menu">
                      <!-- User image -->
                      <li class="user-header">
                        <img src="{{url(Auth::user()->image)}}" class="img-circle" alt="User Image">
                        <p>
                          {{ ucwords(Auth::user()->name) }}
                          <small>Member since {{ date_format(date_create(Auth::user()->created_at),"M j") }}<sup>{{ date_format(date_create(Auth::user()->created_at),"S") }}</sup>, {{ date_format(date_create(Auth::user()->created_at),"Y") }}</small>
                        </p>
                      </li>
                      <!-- Menu Body -->
                      <li class="user-body">
                        <div class="col-xs-4 text-center">
                          <a href="{{ url('profile/timeline') }}">Timeline</a>
                        </div>
                        <div class="col-xs-4 text-center">
                          <a href="{{ url('profile/edit') }}">Edit Profile</a>
                        </div>
                        <div class="col-xs-4 text-center">
                          <a href="{{ url('profile/password') }}">Change Password</a>
                        </div>
                      </li>
                      <!-- Menu Footer-->
                      <li class="user-footer">
                        <div class="pull-left">
                          <a href="{{ url('profile') }}" class="btn btn-default btn-flat">Profile</a>
                        </div>
                        <div class="pull-right">
                          <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                        </div>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
            </nav>
          </header>