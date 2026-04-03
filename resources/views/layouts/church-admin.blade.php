<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Church Admin') - ChurchSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
<div class="min-h-screen flex">
    @php
        $activeSection = trim($__env->yieldContent('active'));
        $active = $activeSection !== '' ? $activeSection : ($active ?? '');
        $mainClass = trim($__env->yieldContent('main_class')) ?: 'px-6 py-8';
        $hasHeaderRight = trim($__env->yieldContent('header_right')) !== '';
    @endphp
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full md:translate-x-0 transition-transform duration-200 bg-white border-r border-slate-200">
        <div class="px-6 py-6">
            <div class="text-xl font-semibold text-slate-800">ChurchSystem</div>
            <div class="text-xs uppercase tracking-widest text-slate-400 mt-1">Church Admin</div>
        </div>
        <nav class="px-4 space-y-1 text-sm">
            <a class="flex items-center gap-3 px-4 py-2 rounded-xl {{ $active === 'dashboard' ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-100' }}" href="/admin/church-dashboard">Dashboard</a>
            <a class="flex items-center gap-3 px-4 py-2 rounded-xl {{ $active === 'leaders' ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-100' }}" href="/admin/church/leaders">Leaders</a>
            <a class="flex items-center gap-3 px-4 py-2 rounded-xl {{ $active === 'meetings' ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-100' }}" href="/admin/church/meetings">Meetings</a>
            <a class="flex items-center gap-3 px-4 py-2 rounded-xl {{ $active === 'projects' ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-100' }}" href="/admin/church/projects">Projects</a>
            <a class="flex items-center gap-3 px-4 py-2 rounded-xl {{ $active === 'reports' ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-100' }}" href="/admin/church/reports">Reports</a>
            <a class="flex items-center gap-3 px-4 py-2 rounded-xl {{ $active === 'settings' ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-100' }}" href="/admin/church/settings">Settings</a>
        </nav>
    </aside>

    <div class="flex-1 md:ml-64">
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button id="menuBtn" class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl border border-slate-200 text-slate-600">
                        <span class="sr-only">Open menu</span>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <div class="text-xs text-slate-400">@yield('header_kicker')</div>
                        <div class="text-lg font-semibold text-slate-900">@yield('header_title')</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($hasHeaderRight)
                        @yield('header_right')
                    @else
                        <button data-logout class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Logout</button>
                    @endif
                </div>
            </div>
        </header>

        <main class="{{ $mainClass }}">
            <div id="errorBanner" class="hidden mb-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                <div class="font-semibold">Please fix the following:</div>
                <ul id="errorBannerList" class="list-disc pl-5 mt-2 space-y-1"></ul>
            </div>
            @yield('content')
        </main>
    </div>
</div>

<script src="/js/admin/app.js"></script>
<script>
    document.getElementById('menuBtn')?.addEventListener('click', function () {
        document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
    });
</script>
@stack('scripts')
</body>
</html>
