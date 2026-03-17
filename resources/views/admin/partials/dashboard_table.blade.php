@forelse($jadwalHariIni as $order)
    {{-- Cek apakah ada setidaknya satu item yang aktif --}}
    @if($order->items->where('status_item', 'Aktif')->count() > 0)
    <tr class="hover:bg-slate-50/50 transition-colors">
        <td class="px-6 py-4">
            <p class="font-bold text-slate-900 leading-tight">{{ $order->nama_pelanggan }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">{{ $order->no_wa }}</p>
        </td>
        <td class="px-6 py-4">
            <div class="flex flex-wrap gap-2 max-w-[400px]">
                @foreach($order->items->where('status_item', 'Aktif') as $service)
                    <span class="inline-flex flex-col bg-slate-100 border border-slate-200 px-2.5 py-1.5 rounded-lg min-w-[120px]">
                        <span class="text-[10px] font-bold text-slate-800 leading-none mb-1">{{ $service->jenis_layanan }}</span>
                        <span class="text-[9px] text-slate-500 leading-none">{{ $service->jumlah_rit }} Rit | {{ $service->armada }}</span>
                    </span>
                @endforeach
            </div>
        </td>
        <td class="px-6 py-4">
            @if($order->total_pendapatan > 0)
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">
                    <i class="fa-solid fa-check mr-1.5"></i> Rekap Selesai
                </span>
            @else
                <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700 border border-orange-100">
                    <i class="fa-solid fa-clock mr-1.5"></i> Tunggu Rekap
                </span>
            @endif
        </td>
        <td class="px-6 py-4 text-right whitespace-nowrap">
            <a href="{{ route('admin.rekap_order.edit', $order->id) }}" class="text-slate-400 hover:text-green-600 transition-colors" title="Lengkapi Data">
                <i class="fa-solid fa-pen-to-square text-lg"></i>
            </a>
        </td>
    </tr>
    @endif
@empty
<tr>
    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
        Tidak ada jadwal pesanan untuk hari ini.
    </td>
</tr>
@endforelse