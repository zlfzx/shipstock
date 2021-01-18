<aside class="main-sidebar elevation-4 sidebar-light-indigo">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link text-center">
      <span class="brand-text font-weight-bold">SHIPStock</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user1-128x128.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="/" class="nav-link @if(request()->is('/')) active @endif">
              <i class="nav-icon fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('pemakaian.index') }}" class="nav-link @if(request()->is('pemakaian')) active @endif">
              <i class="nav-icon fas fa-bars"></i>
              <p>Pemakaian</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('sparepart.index') }}" class="nav-link @if(request()->is('sparepart')) active @endif">
              <i class="nav-icon fas fa-tools"></i>
              <p>Sparepart</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('warehouse.index') }}" class="nav-link @if(request()->is('warehouse')) active @endif">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>Warehouse</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('kapal.index') }}" class="nav-link @if(request()->is('kapal')) active @endif">
              <i class="nav-icon fas fa-ship"></i>
              <p>Kapal</p>
            </a>
          </li>
          @if(auth()->user()->status == 'admin')
          <li class="nav-item">
            <a href="{{ route('user.index') }}" class="nav-link @if(request()->is('user')) active @endif">
              <i class="nav-icon fas fa-users"></i>
              <p>User</p>
            </a>
          </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
