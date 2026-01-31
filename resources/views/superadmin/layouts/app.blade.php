<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nebeng - Superadmin</title>

  <style>
    :root{
      --sidebar:#0B3E7A;
      --sidebar2:#083a70;
      --bg:#EEF4FF;
      --card:#FFFFFF;
      --border:#E5EAF3;
      --text:#0F172A;
      --muted:#6B7280;
      --hover:rgba(255,255,255,.10);
      --active:rgba(255,255,255,.14);
    }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:Arial, sans-serif; background:var(--bg); color:var(--text); }

    .app{ display:flex; min-height:100vh; }

    /* Sidebar */
    .sidebar{
      width:240px;
      background:linear-gradient(180deg,var(--sidebar),var(--sidebar2));
      color:#fff;
      padding:18px 14px;
      display:flex;
      flex-direction:column;
    }
    .brand{ font-weight:800; letter-spacing:.5px; font-size:20px; }
    .tagline{ margin-top:6px; font-size:11px; color:rgba(255,255,255,.75); line-height:1.2; }

    .side-section{ margin-top:22px; }
    .side-section-title{
      font-size:11px;
      color:rgba(255,255,255,.6);
      margin:0 0 10px 6px;
      letter-spacing:.6px;
    }

    .nav{ display:flex; flex-direction:column; gap:6px; }
    .nav a{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      padding:10px 10px;
      border-radius:10px;
      text-decoration:none;
      color:rgba(255,255,255,.88);
      font-size:13px;
      transition:.15s;
    }
    .nav a:hover{ background:var(--hover); color:#fff; }
    .nav a.active{ background:var(--active); color:#fff; }
    .nav-left{ display:flex; align-items:center; gap:10px; }
    .icon{
      width:18px; height:18px;
      display:inline-flex; align-items:center; justify-content:center;
      font-size:14px;
      opacity:.95;
    }
    .chev{ color:rgba(255,255,255,.7); font-size:14px; }

    .logout-wrap{ margin-top:auto; }
    .logout{
      width:100%;
      border:0;
      padding:11px 12px;
      border-radius:10px;
      background:rgba(255,255,255,.10);
      color:#fff;
      cursor:pointer;
      display:flex;
      align-items:center;
      gap:10px;
      transition:.15s;
    }
    .logout:hover{ background:rgba(255,255,255,.14); }

    /* Main */
    .main{ flex:1; display:flex; flex-direction:column; }

    /* Topbar */
    .topbar{
      height:64px;
      background:transparent;
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:18px 22px 0 22px;
    }
    .welcome{
      font-size:18px;
      font-weight:700;
      color:#0F172A;
    }
    .top-actions{
      display:flex;
      align-items:center;
      gap:10px;
    }
    .search{
      width:220px;
      height:30px;
      border-radius:999px;
      border:1px solid var(--border);
      background:#fff;
      padding:0 12px;
      outline:none;
      font-size:12px;
    }
    .icon-btn{
      width:30px; height:30px;
      border-radius:8px;
      border:1px solid var(--border);
      background:#fff;
      cursor:pointer;
      position:relative;
      display:flex; align-items:center; justify-content:center;
      font-size:14px;
    }
    .notif-dot{
      position:absolute;
      width:8px;height:8px;
      background:#EF4444;
      border-radius:999px;
      top:6px; right:6px;
    }
    .profile{
      display:flex;
      align-items:center;
      gap:8px;
      border:1px solid var(--border);
      background:#fff;
      height:30px;
      padding:0 10px 0 6px;
      border-radius:8px;
      cursor:pointer;
      font-size:12px;
    }
    .avatar{
      width:22px;height:22px;border-radius:999px;
      background:#f59e0b;
      display:inline-flex; align-items:center; justify-content:center;
      color:#fff; font-weight:800;
      font-size:12px;
    }
    .caret{ color:var(--muted); font-size:12px; }

    /* Content area */
    .content{
      flex:1;
      margin:10px 22px 22px 22px;
      background:#fff0; /* transparan seperti screenshot */
      border-radius:14px;
      position:relative;
      overflow:hidden;
    }

    /* “Watermark” home */
    .watermark{
      position:absolute;
      inset:0;
      display:flex;
      align-items:center;
      justify-content:center;
      pointer-events:none;
      opacity:.22;
    }
    .watermark img{
      max-width:720px;
      width:70%;
      filter:drop-shadow(0 8px 10px rgba(0,0,0,.12));
    }

    /* Page container */
    .page{ position:relative; min-height:calc(100vh - 64px - 40px); }

    @media (max-width: 900px){
      .sidebar{ display:none; }
      .topbar{ padding:14px 14px 0 14px; }
      .content{ margin:10px 14px 14px 14px; }
      .search{ width:160px; }
    }
  </style>
</head>

<body>
  <div class="app">
    <aside class="sidebar">
      @include('superadmin.layouts.sidebar')
    </aside>

    <section class="main">
      <div class="topbar">
        @include('superadmin.layouts.topbar')
      </div>

      <div class="content">
        <div class="page">
          @yield('content')
        </div>
      </div>
    </section>
  </div>
</body>
</html>
