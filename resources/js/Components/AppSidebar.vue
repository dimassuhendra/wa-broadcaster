<script setup>
import { Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    ChartNoAxesCombined,
    ChevronRight,
    FileText,
    LayoutDashboard,
    Megaphone,
    MessageCircle,
    MoreHorizontal,
    Settings2,
    ShieldCheck,
    UsersRound,
    X,
} from '@lucide/vue';

defineProps({
    section: {
        type: String,
        default: 'dashboard',
    },
    mobileOpen: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['close']);

const navigation = [
    { label: 'Ringkasan', section: 'dashboard', href: '/', icon: LayoutDashboard },
    { label: 'Kampanye', section: 'campaigns', href: '/campaigns', icon: Megaphone },
    { label: 'Kontak', section: 'contacts', href: '/contacts', icon: UsersRound },
    { label: 'Template pesan', section: 'templates', href: '/templates', icon: FileText },
    { label: 'Analitik', section: 'analytics', href: '/analytics', icon: ChartNoAxesCombined },
];
</script>

<template>
    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-[258px] flex-col border-r border-[#e9ede9] bg-white transition-transform duration-200 lg:translate-x-0"
        :class="mobileOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="flex h-[76px] items-center justify-between px-6">
            <Link href="/" class="flex items-center gap-3" @click="$emit('close')">
                <span class="flex h-10 w-10 items-center justify-center rounded-[13px] bg-[#16845b] text-white shadow-sm shadow-[#16845b]/20">
                    <MessageCircle :size="21" :stroke-width="2.2" />
                </span>
                <span class="leading-tight">
                    <span class="block text-[16px] font-bold tracking-[-0.5px] text-[#1e2a23]">SapaFlow</span>
                    <span class="mt-1 block text-[10px] font-semibold uppercase tracking-[1.3px] text-[#91a097]">WhatsApp workspace</span>
                </span>
            </Link>
            <button
                class="rounded-lg p-2 text-[#809087] hover:bg-[#f4f6f4] lg:hidden"
                aria-label="Tutup navigasi"
                @click="$emit('close')"
            >
                <X :size="19" />
            </button>
        </div>

        <div class="mx-4 mt-5 rounded-xl border border-[#e9ede9] bg-[#fbfcfb] px-3.5 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#e2a44b] opacity-35" />
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-[#e2a44b]" />
                    </span>
                    <span class="text-xs font-semibold text-[#48574e]">WhatsApp belum terhubung</span>
                </div>
                <ChevronRight :size="15" class="text-[#a6b0aa]" />
            </div>
            <p class="mb-0 mt-1.5 pl-5 text-[11px] text-[#91a097]">Hubungkan API untuk mulai</p>
        </div>

        <nav class="mt-8 flex-1 px-4" aria-label="Navigasi utama">
            <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[1.2px] text-[#a4aea7]">Menu utama</p>
            <div class="space-y-1">
                <Link
                    v-for="item in navigation"
                    :key="item.section"
                    :href="item.href"
                    class="group flex items-center gap-3 rounded-xl px-3 py-[11px] text-[13px] font-medium transition-colors"
                    :class="section === item.section
                        ? 'bg-[#eaf4ee] font-semibold text-[#16734f]'
                        : 'text-[#68766e] hover:bg-[#f5f7f5] hover:text-[#2b3b31]'"
                    @click="$emit('close')"
                >
                    <component :is="item.icon" :size="18" :stroke-width="1.8" />
                    <span>{{ item.label }}</span>
                </Link>
            </div>

            <p class="mb-3 mt-9 px-3 text-[10px] font-bold uppercase tracking-[1.2px] text-[#a4aea7]">Workspace</p>
            <Link
                href="/settings"
                class="flex items-center gap-3 rounded-xl px-3 py-[11px] text-[13px] font-medium transition-colors"
                :class="section === 'settings' ? 'bg-[#eaf4ee] font-semibold text-[#16734f]' : 'text-[#68766e] hover:bg-[#f5f7f5] hover:text-[#2b3b31]'"
                @click="$emit('close')"
            >
                <Settings2 :size="18" :stroke-width="1.8" />
                <span>Pengaturan</span>
            </Link>
        </nav>

        <div class="mx-4 mb-4 rounded-2xl bg-[#f5f8f5] p-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-[#16845b] shadow-sm">
                <ShieldCheck :size="17" />
            </div>
            <p class="mb-1 mt-3 text-xs font-semibold text-[#34453a]">Kirim dengan bertanggung jawab</p>
            <p class="mb-3 text-[11px] leading-[1.6] text-[#849188]">Pastikan setiap kontak sudah menyetujui pesan yang akan diterima.</p>
            <a href="https://www.whatsapp.com/legal/business-policy/" target="_blank" rel="noreferrer" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#16845b]">
                Baca panduan <ArrowRight :size="13" />
            </a>
        </div>

        <button class="flex items-center gap-3 border-t border-[#edf0ed] px-5 py-4 text-left hover:bg-[#fafbfa]">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#eaf0eb] text-xs font-bold text-[#557260]">SF</span>
            <span class="min-w-0 flex-1">
                <span class="block truncate text-xs font-semibold text-[#344139]">SapaFlow Studio</span>
                <span class="mt-0.5 block text-[10px] text-[#94a097]">Paket Starter</span>
            </span>
            <MoreHorizontal :size="18" class="text-[#94a097]" />
        </button>
    </aside>
</template>
