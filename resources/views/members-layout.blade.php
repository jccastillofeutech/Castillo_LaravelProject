<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Manage members and membership plans.">
    <title>@yield('title', 'Members') · Puihaha</title>
    @vite(['resources/css/members.css', 'resources/js/members.js'])
</head>

<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('members.index') }}"><span class="brand-mark">P</span><span>Puihaha</span></a>
            <p class="sidebar-label">MEMBERSHIP</p>
            <nav class="dashboard-nav" aria-label="Membership navigation">
                <a href="{{ route('members.index') }}" @if (request()->routeIs('members.*')) aria-current="page" @endif>Members</a>
                <a href="{{ route('categories.index') }}" @if (request()->routeIs('categories.*')) aria-current="page" @endif>Categories</a>
                <a href="{{ route('levels.index') }}" @if (request()->routeIs('levels.*')) aria-current="page" @endif>Membership levels</a>
            </nav>
            <div class="sidebar-footer">Member management<br><span>Standalone workspace</span></div>
        </aside>

        <div class="main-column">
            <header class="topbar"><span>Member management</span><span class="topbar-dot"></span></header>
            <main class="dashboard-content">
            @if (session('success'))
                <div class="notice notice-success" role="status">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="notice notice-error" role="alert">
                    <strong>Please check the form.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
