<nav id="sidebar" aria-label="Main Navigation">
  <!-- Side Header -->
  <div class="bg-header-dark">
    <div class="content-header bg-white-10">
      <!-- Logo -->
      <a class="link-fx font-w600 font-size-lg text-white" href="{{ route('dashboard') }}">
        <img class="img-logo" src="{{config('app.url')}}/assets/icon/icon-mobile.png">
      </a>
    </div>
  </div>
  <!-- END Side Header -->

  <!-- Side Navigation -->
  <div class="content-side content-side-full">
    <ul class="nav-main">
     

      <li class="nav-main-item {{ ($name == 'Pages') ? 'open' : ''}}">
        <a class="nav-main-link {{ ($name == 'Pages') ? 'active' : ''}}" href="{{ route('pages.index') }}">
          <i class="nav-main-link-icon si si-user"></i>
          <span class="nav-main-link-name">หน้าเว็บ</span>
        </a>
      </li>

      <li class="nav-main-item {{ ($name == 'Users') ? 'open' : ''}}">
        <a class="nav-main-link {{ ($name == 'Users') ? 'active' : ''}}" href="{{ route('users.index') }}">
          <i class="nav-main-link-icon si si-user"></i>
          <span class="nav-main-link-name">ผู้ใช้งานระบบ</span>
        </a>
      </li>

    </ul>
  </div>
  <!-- END Side Navigation -->
</nav>