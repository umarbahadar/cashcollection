<!-- ======= Sidebar ======= -->
@php
    function isActive($routes, $output = 'active') {
        return request()->routeIs($routes) ? $output : '';
    }

    function isDropdownOpen($routes) {
        return request()->routeIs($routes) ? 'show' : '';
    }

    function isCollapsed($routes) {
        return request()->routeIs($routes) ? '' : 'collapsed';
    }
@endphp

<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <li class="nav-item">
    <a class="nav-link {{ isActive('dashboard') }}" href="{{ route('dashboard')}}">
      <i class="bi bi-grid"></i>
      <span>Dashboard</span>
    </a>
  </li>

   <li class="nav-item">
    <a class="nav-link {{ isActive('receive-payment.index') }}" href="{{ route('receive-payment.index')}}">
      <i class="bi bi-grid"></i>
      <span>Receive Payment</span>
    </a>
  </li>

   <li class="nav-item">
    <a class="nav-link {{ isActive('ledger.index') }}" href="{{ route('ledger.index')}}">
      <i class="bi bi-grid"></i>
      <span>Reports & ledgers</span>
    </a>
  </li>
  
  

  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
  </form>
</ul>

</aside>