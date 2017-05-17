		          <!-- Left side column. contains the logo and sidebar -->
          <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
              <!-- Sidebar user panel -->
              <div class="user-panel">
                <div class="pull-left image">
                  <img src="{{url(Auth::user()->image)}}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                  <p>{{ ucwords(Auth::user()->name) }}</p>
                  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
              </div>
              <!-- search form -->
              <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                  <input type="text" name="q" class="form-control" placeholder="Search...">
                  <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                  </span>
                </div>
              </form>
              <!-- /.search form -->
              <!-- sidebar menu: : style can be found in sidebar.less -->
              <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                @if(Auth::user()->status==1)
                <li class="{{ $active['dashboard'] or '' }}">
                  <a href="{{ url('/home') }}">
                    <i class="fa fa-dashboard"></i> 
                    <span>Dashboard</span> 
                    <small class="label pull-right bg-green"></small>
                  </a>
                </li>
                <li class="{{ $active['makalah'] or '' }} treeview">
                  <a href="#">
                    <i class="fa fa-book"></i> <span>Makalah</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                    <li class="{{ $active['semua'] or '' }}">
                      <a href="{{ url('/makalah') }}">
                        <i class="fa fa-circle-o"></i> 
                        <span>Semua</span> 
                        <small class="label pull-right bg-green"></small>
                      </a>
                    </li>
                    @if(Auth::user()->id_role!=2)
                    <li class="{{ $active['makalahuser'] or '' }}">
                      <a href="{{ url('/makalahuser') }}">
                        <i class="fa fa-circle-o"></i> 
                        <span>Makalah Saya</span> 
                        <small class="label pull-right bg-green"></small>
                      </a>
                    </li>
                    <li class="{{ $active['baru'] or '' }}">
                      <a href="#"><i class="fa fa-circle-o"></i> Baru<i class="fa fa-angle-left pull-right"></i></a>
                      <ul class="treeview-menu">
                        <li class="{{ $active['plagiat'] or '' }}"><a href="{{ url('plagiat') }}"><i class="fa fa-circle-o"></i> Terdeteksi Plagiat</a></li>
                        <li class="{{ $active['lolos'] or '' }}"><a href="{{ url('lolos') }}"><i class="fa fa-circle-o"></i> Lolos Plagiat</a></li>
                      </ul>
                    </li>
                    @endif
                    <li class="{{ $active['direktori'] or '' }}">
                      <a href="#"><i class="fa fa-circle-o"></i> Direktori<i class="fa fa-angle-left pull-right"></i></a>
                      <ul class="treeview-menu">
                        <li class="{{ $active['diterima'] or '' }}"><a href="{{ url('diterima') }}"><i class="fa fa-circle-o"></i> Diterima</a></li>
                        <li class="{{ $active['revisi'] or '' }}"><a href="{{ url('revisi') }}"><i class="fa fa-circle-o"></i> Revisi</a></li>
                        <li class="{{ $active['ditolak'] or '' }}"><a href="{{ url('ditolak') }}"><i class="fa fa-circle-o"></i> Ditolak</a></li>
                      </ul>
                    </li>
                    <li class="{{ $active['katmakalah'] or '' }}">
                      <a href="#"><i class="fa fa-circle-o"></i> Kategori<i class="fa fa-angle-left pull-right"></i></a>
                      <ul class="treeview-menu">
                        <?php
                        $katma = DB::table('kategori')->get();
                        ?>
                        @foreach($katma as $kat)
                          <li class="{{ $active['katma'.$kat->id_kategori] or '' }}"><a href="{{ url('katmakalah/'.$kat->id_kategori) }}"><i class="fa fa-circle-o"></i> {{ $kat->kategori }}</a></li>
                        @endforeach
                      </ul>
                    </li>
                  </ul>
                </li>
                @if(Auth::user()->id_role==1)
                <li class="{{ $active['plagiarisme'] or '' }}">
                  <a href="{{ url('/setting') }}">
                    <i class="fa fa-cog"></i> <span>Settings Plagiarisme</span>
                  </a>
                </li>
                <li class="{{ $active['pengguna'] or '' }} treeview">
                  <a href="#">
                    <i class="fa fa-users"></i> <span>Pengguna</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                    <li class="{{ $active['editor'] or '' }}"><a href="{{url('editor')}}"><i class="fa fa-circle-o"></i> Editor</a></li>
                    <li class="{{ $active['penulis'] or '' }}"><a href="{{url('penulis')}}"><i class="fa fa-circle-o"></i> Penulis</a></li>
                  </ul>
                </li>
                <li class="{{ $active['master'] or '' }} treeview">
                  <a href="#">
                    <i class="fa fa-list"></i> <span>Data Master</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>
                  <ul class="treeview-menu">
                    <li class="{{ $active['stop_word'] or '' }}"><a href="{{url('stopword')}}"><i class="fa fa-circle-o"></i> Stop Word</a></li>
                    <li class="{{ $active['kategori'] or '' }}"><a href="{{url('kategori')}}"><i class="fa fa-circle-o"></i> Kategori</a></li>
                  </ul>
                </li>
                @endif
                @endif
                <li class="{{ $active['profile'] or '' }}">
                  <a href="{{url('/profile')}}">
                    <i class="fa fa-user"></i>
                     <span>Profile</span>
                  </a>
                </li>
              </ul>
            </section>
            <!-- /.sidebar -->
          </aside>