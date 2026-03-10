<div class="flex h-20 items-center justify-between px-6 border-b border-slate-50">
    <a href="#" class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="h-6 w-auto object-contain">
        </div>
        <span class="font-heading text-xl font-bold text-slate-900 tracking-tight">Juragan<span class="text-green-600">Urug</span></span>
    </a>
    <button id="closeSidebar" class="p-2 text-slate-400 hover:text-red-500 lg:hidden">
        <i class="fa-solid fa-xmark text-xl"></i>
    </button>
</div>

<nav class="flex-1 overflow-y-auto p-4 space-y-1.5">
    <p class="px-4 pb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Menu Utama</p>
    
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-green-50 text-green-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-medium' }} px-4 py-3.5 text-sm transition-colors">
        <i class="fa-solid fa-chart-pie w-5 text-center text-lg {{ request()->routeIs('admin.dashboard') ? '' : 'text-slate-400' }}"></i> Dashboard
    </a>

    <a href="{{ route('admin.input_order') }}" class="flex items-center gap-3 rounded-xl {{ request()->routeIs('admin.input_order') ? 'bg-green-50 text-green-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-medium' }} px-4 py-3.5 text-sm transition-colors">
        <i class="fa-solid fa-cart-plus w-5 text-center text-lg {{ request()->routeIs('admin.input_order') ? '' : 'text-slate-400' }}"></i> Input Order
    </a>

    <a href="{{ route('admin.rekap_order') }}" class="flex items-center gap-3 rounded-xl {{ request()->routeIs('admin.rekap_order') ? 'bg-green-50 text-green-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-medium' }} px-4 py-3.5 text-sm transition-colors">
        <i class="fa-solid fa-list-ul w-5 text-center text-lg {{ request()->routeIs('admin.rekap_order') ? '' : 'text-slate-400' }}"></i> Rekap Order
    </a>

    <p class="px-4 pb-2 pt-6 text-xs font-bold uppercase tracking-wider text-slate-400">Keuangan</p>

    <a href="{{ route('admin.invoice_index') }}" class="flex items-center gap-3 rounded-xl {{ request()->routeIs('admin.invoice_index') ? 'bg-green-50 text-green-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-medium' }} px-4 py-3.5 text-sm transition-colors">
        <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-lg {{ request()->routeIs('admin.invoice_index') ? '' : 'text-slate-400' }}"></i> Create Invoice
    </a>

    <a href="{{ route('admin.payment_index') }}" class="flex items-center gap-3 rounded-xl {{ request()->routeIs('admin.payment_index') ? 'bg-green-50 text-green-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 font-medium' }} px-4 py-3.5 text-sm transition-colors">
        <i class="fa-solid fa-wallet w-5 text-center text-lg {{ request()->routeIs('admin.payment_index') ? '' : 'text-slate-400' }}"></i> Payment
    </a>
</nav>

<div class="border-t border-slate-100 p-4">
    <div class="flex items-center gap-3 rounded-xl p-3 transition-colors hover:bg-slate-50">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-slate-600 font-bold">A</div>
        <div class="flex-1 overflow-hidden">
            <p class="truncate text-sm font-bold text-slate-900">Admin Juragan Urug</p>
            <p class="truncate text-xs text-slate-500">admin@juraganurug.com</p>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST" class="m-0 p-0">
            @csrf
            <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors cursor-pointer"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
        </form>
    </div>
</div>