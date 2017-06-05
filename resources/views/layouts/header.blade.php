        <header class="main-header">
            <!-- Logo -->
            <a href="{{url('/')}}" class="logo">
              <!-- mini logo for sidebar mini 50x50 pixels -->
              <span class="logo-mini"><b>SDL</b></span>
              <!-- logo for regular state and mobile devices -->
              <span class="logo-lg"><b>SDL Teknik Kimia FT </b></span>
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
                ->leftjoin('dosen_makalah as em','em.id_makalah','=','k.id_makalah')
                ->leftjoin('users as u','k.id_user','=','u.id_user')
                ->where([
                    ['em.id_user',Auth::user()->id_user],
                    ['k.readby','not like','%('.Auth::user()->id_user.')%']
                    ])
                ->orderBy('k.waktu','asc')
                ->get();

            $newmessage = DB::table('komentar_makalah as k')
                ->leftjoin('dosen_makalah as em','em.id_makalah','=','k.id_makalah')
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
                          <a href="{{ url('profile') }}" class="btn btn-default btn-flat">Profiles</a>
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
