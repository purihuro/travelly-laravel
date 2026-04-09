<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Exflore KBB Admin')</title>
    <style>
      :root { --bg:#f4efe6; --panel:#fffdf8; --line:#e7dccb; --text:#1f2a24; --muted:#6f746e; --brand:#1d4d42; --brand-soft:#d7ebe1; --danger:#b44434; --success:#246a4b; --warning:#7a5c11; --shadow:0 18px 40px rgba(30,47,37,.08); --radius:18px; }
      * { box-sizing:border-box; }
      body { margin:0; font-family:"Segoe UI",Tahoma,Geneva,Verdana,sans-serif; color:var(--text); background:radial-gradient(circle at top left, rgba(196,107,61,.14), transparent 28%),radial-gradient(circle at top right, rgba(29,77,66,.12), transparent 24%),linear-gradient(180deg,#fbf7f1 0%,#f4efe6 100%); }
      a { color:inherit; }
      .shell { display:grid; grid-template-columns:260px minmax(0,1fr); min-height:100vh; }
      .sidebar { background:#16392f; color:#eef6f1; padding:28px 22px; }
      .brand { margin-bottom:28px; }
      .brand small { display:block; color:rgba(238,246,241,.72); letter-spacing:.08em; text-transform:uppercase; margin-bottom:6px; }
      .brand strong { display:block; font-size:1.5rem; }
      .nav-link { display:block; padding:12px 14px; margin-bottom:8px; border-radius:12px; text-decoration:none; color:rgba(238,246,241,.92); }
      .nav-link:hover,.nav-link.active { background:rgba(255,255,255,.1); }
      .nav-title { margin:18px 0 8px; color:rgba(238,246,241,.6); font-size:.78rem; text-transform:uppercase; letter-spacing:.08em; }
      .sidebar .meta { margin-top:24px; padding-top:20px; border-top:1px solid rgba(255,255,255,.12); color:rgba(238,246,241,.72); font-size:.92rem; }
      .logout-button { margin-top:14px; width:100%; border:0; background:rgba(180,68,52,.18); color:#fff3ea; border-radius:12px; padding:11px 14px; cursor:pointer; }
      .content { padding:28px; }
      .topbar { display:flex; justify-content:space-between; gap:16px; align-items:flex-start; margin-bottom:22px; }
      .topbar h1 { margin:0; font-size:1.9rem; }
      .topbar p { margin:8px 0 0; color:var(--muted); }
      .panel { background:var(--panel); border:1px solid rgba(231,220,203,.9); border-radius:var(--radius); box-shadow:var(--shadow); }
      .panel-body { padding:22px; }
      .grid { display:grid; gap:18px; }
      .stats { grid-template-columns:repeat(4,minmax(0,1fr)); }
      .stat-card { padding:20px; background:linear-gradient(180deg, rgba(255,255,255,.95), rgba(255,249,242,.96)); border:1px solid var(--line); border-radius:16px; }
      .stat-card span { display:block; color:var(--muted); font-size:.92rem; margin-bottom:10px; }
      .stat-card strong { font-size:1.9rem; }
      .actions { display:flex; gap:10px; flex-wrap:wrap; }
      .btn,button.btn,a.btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; border-radius:12px; padding:11px 16px; text-decoration:none; border:1px solid transparent; cursor:pointer; font-weight:600; }
      .btn-primary { background:var(--brand); color:#fff; }
      .btn-secondary { background:var(--brand-soft); color:var(--brand); }
      .btn-danger { background:#f8e0dc; color:var(--danger); }
      .btn-ghost { background:transparent; border-color:var(--line); color:var(--text); }
      .flash { padding:14px 16px; border-radius:14px; margin-bottom:18px; border:1px solid var(--line); }
      .flash-success { background:#edf8f1; color:var(--success); border-color:#cfe8d7; }
      .flash-error { background:#fff1ef; color:var(--danger); border-color:#efcbc4; }
      .table-wrap { overflow-x:auto; }
      table { width:100%; border-collapse:collapse; }
      th,td { padding:14px 12px; border-bottom:1px solid var(--line); text-align:left; vertical-align:top; }
      th { color:var(--muted); font-size:.88rem; text-transform:uppercase; letter-spacing:.05em; }
      .muted { color:var(--muted); }
      .badge { display:inline-flex; padding:6px 10px; border-radius:999px; font-size:.8rem; font-weight:600; background:#efe7d9; color:#6d5336; }
      .badge-success { background:#dff0e7; color:#1f6a48; }
      .badge-warning { background:#f7e7bf; color:var(--warning); }
      .badge-neutral { background:#eceae5; color:#5b5f5a; }
      .form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
      .field,.field-full { display:flex; flex-direction:column; gap:8px; }
      .field-full { grid-column:1 / -1; }
      label { font-weight:600; font-size:.95rem; }
      input,textarea,select { width:100%; border:1px solid #d8ccbb; border-radius:12px; padding:12px 14px; background:#fff; color:var(--text); font:inherit; }
      textarea { min-height:140px; resize:vertical; }
      .checkbox { flex-direction:row; align-items:center; gap:10px; }
      .checkbox input { width:auto; }
      .errors { margin:0; padding-left:18px; color:var(--danger); }
      .split { display:grid; grid-template-columns:1.3fr .7fr; gap:18px; }
      .stack > * + * { margin-top:18px; }
      .pagination { margin-top:18px; }
      @media (max-width:1100px){ .stats,.split,.form-grid{ grid-template-columns:1fr 1fr; } }
      @media (max-width:860px){ .shell{ grid-template-columns:1fr; } .content{ padding:18px; } .stats,.split,.form-grid{ grid-template-columns:1fr; } }
    </style>
  </head>
  <body>
    <div class="shell">
      <aside class="sidebar">
        <div class="brand"><small>Panel Admin</small><strong>Exflore KBB</strong></div>
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <div class="nav-title">Website</div>
        <a class="nav-link {{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}" href="{{ route('admin.homepage.edit') }}">Homepage</a>
        <a class="nav-link {{ request()->routeIs('admin.content.about.*') ? 'active' : '' }}" href="{{ route('admin.content.about.edit') }}">About</a>
        <a class="nav-link {{ request()->routeIs('admin.content.contact.*') ? 'active' : '' }}" href="{{ route('admin.content.contact.edit') }}">Contact</a>
        <a class="nav-link {{ request()->routeIs('admin.content.footer.*') ? 'active' : '' }}" href="{{ route('admin.content.footer.edit') }}">Footer</a>
        <div class="nav-title">Operasional</div>
        <a class="nav-link {{ request()->routeIs('admin.travel-packages.*') ? 'active' : '' }}" href="{{ route('admin.travel-packages.index') }}">Paket Wisata</a>
        <a class="nav-link {{ request()->routeIs('admin.destination-tickets.*') ? 'active' : '' }}" href="{{ route('admin.destination-tickets.index') }}">Tiket Wisata</a>
        <a class="nav-link {{ request()->routeIs('admin.accommodations.*') ? 'active' : '' }}" href="{{ route('admin.accommodations.index') }}">Penginapan</a>
        <a class="nav-link {{ request()->routeIs('admin.culinary-packages.*', 'admin.culinary-venues.*') ? 'active' : '' }}" href="{{ route('admin.culinary-venues.index') }}">Kuliner</a>
        <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">Booking</a>
        <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}" href="{{ route('admin.contacts.index') }}">Kontak</a>
        <div class="meta"><div>Login sebagai <strong>{{ auth()->user()?->name }}</strong></div><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="logout-button" type="submit">Keluar</button></form></div>
      </aside>
      <main class="content">
        <div class="topbar"><div><h1>@yield('page_title', 'Exflore KBB Admin')</h1><p>@yield('page_description', 'Kelola konten, booking, dan komunikasi pelanggan dari satu panel.')</p></div><div class="actions">@yield('page_actions')</div></div>
        @if (session('status'))<div class="flash flash-success">{{ session('status') }}</div>@endif
        @if (session('error'))<div class="flash flash-error">{{ session('error') }}</div>@endif
        @if ($errors->any())<div class="flash flash-error"><strong>Periksa lagi input form.</strong><ul class="errors">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('content')
      </main>
    </div>
  </body>
</html>
