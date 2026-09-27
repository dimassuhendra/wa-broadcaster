<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowDownRight,
    ArrowRight,
    ArrowUpRight,
    Bell,
    CalendarDays,
    ChartNoAxesCombined,
    Check,
    CheckCircle2,
    ChevronDown,
    ChevronRight,
    CircleHelp,
    FileText,
    LayoutDashboard,
    LockKeyhole,
    Megaphone,
    Menu,
    MessageCircle,
    MoreHorizontal,
    Plus,
    Search,
    Send,
    Settings2,
    ShieldCheck,
    Sparkles,
    UsersRound,
    X,
} from '@lucide/vue';

const props = defineProps({
    section: {
        type: String,
        default: 'dashboard',
    },
});

const navigation = [
    { label: 'Ringkasan', section: 'dashboard', href: '/', icon: LayoutDashboard },
    { label: 'Kampanye', section: 'campaigns', href: '/campaigns', icon: Megaphone },
    { label: 'Kontak', section: 'contacts', href: '/contacts', icon: UsersRound },
    { label: 'Template pesan', section: 'templates', href: '/templates', icon: FileText },
    { label: 'Analitik', section: 'analytics', href: '/analytics', icon: ChartNoAxesCombined },
];

const pageTitles = {
    dashboard: 'Ringkasan',
    campaigns: 'Kampanye',
    contacts: 'Kontak',
    templates: 'Template pesan',
    analytics: 'Analitik',
    settings: 'Pengaturan',
};

const currentTitle = computed(() => pageTitles[props.section] ?? 'Ringkasan');
const todayLabel = new Intl.DateTimeFormat('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
}).format(new Date());
const currentYear = new Date().getFullYear();
const mobileMenuOpen = ref(false);
const showCampaignModal = ref(false);
const toastMessage = ref('');
const contactSearch = ref('');
const campaignForm = ref({
    name: '',
    message: '',
    audience: 0,
    confirmedConsent: false,
});
let toastTimeout;

const weeklyMessages = [
    { day: 'Sen', value: 36 },
    { day: 'Sel', value: 52 },
    { day: 'Rab', value: 43 },
    { day: 'Kam', value: 75 },
    { day: 'Jum', value: 58 },
    { day: 'Sab', value: 90 },
    { day: 'Min', value: 68 },
];

const campaigns = ref([
    { name: 'Promo akhir bulan', detail: 'Pelanggan loyal · 128 penerima', time: 'Hari ini, 10.42', status: 'Selesai', tone: 'green', icon: Send },
    { name: 'Pengingat jadwal', detail: 'Pelanggan aktif · 64 penerima', time: 'Hari ini, 09.15', status: 'Terjadwal', tone: 'blue', icon: CalendarDays },
    { name: 'Sambutan pelanggan baru', detail: 'Pelanggan baru · 32 penerima', time: 'Kemarin, 15.20', status: 'Draf', tone: 'gray', icon: FileText },
]);

const contacts = [
    { name: 'Andi Pratama', phone: '+62 812-••••-1204', group: 'Pelanggan loyal', initials: 'AP', color: 'bg-[#e4efe8] text-[#24744d]', consent: true },
    { name: 'Dina Maharani', phone: '+62 813-••••-8702', group: 'Pelanggan aktif', initials: 'DM', color: 'bg-[#f5e9db] text-[#a46934]', consent: true },
    { name: 'Rizky Saputra', phone: '+62 857-••••-3401', group: 'Pelanggan baru', initials: 'RS', color: 'bg-[#e6e9f4] text-[#5466a6]', consent: true },
    { name: 'Nadia Putri', phone: '+62 878-••••-5210', group: 'Pelanggan aktif', initials: 'NP', color: 'bg-[#f3e5ed] text-[#9b5780]', consent: true },
];

const filteredContacts = computed(() => {
    const query = contactSearch.value.toLowerCase().trim();

    return contacts.filter((contact) =>
        `${contact.name} ${contact.phone} ${contact.group}`.toLowerCase().includes(query),
    );
});

function createDraft() {
    if (!campaignForm.value.confirmedConsent) {
        return;
    }

    campaigns.value.unshift({
        name: campaignForm.value.name,
        detail: `Daftar penerima · ${campaignForm.value.audience} penerima`,
        time: 'Baru saja',
        status: 'Draf',
        tone: 'gray',
        icon: FileText,
    });
    showCampaignModal.value = false;
    campaignForm.value = { name: '', message: '', audience: 0, confirmedConsent: false };
    showToast('Draf kampanye berhasil dibuat.');
}

function showToast(message) {
    toastMessage.value = message;
    window.clearTimeout(toastTimeout);
    toastTimeout = window.setTimeout(() => {
        toastMessage.value = '';
    }, 2800);
}
</script>

<template>
    <Head :title="`${currentTitle} — SapaFlow`" />

    <div class="min-h-screen bg-[#f7f8f6] text-[#202923]">
        <div
            v-if="mobileMenuOpen"
            class="fixed inset-0 z-40 bg-[#18231d]/35 lg:hidden"
            @click="mobileMenuOpen = false"
        />

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-[258px] flex-col border-r border-[#e9ede9] bg-white transition-transform duration-200 lg:translate-x-0"
            :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-[76px] items-center justify-between px-6">
                <Link href="/" class="flex items-center gap-3" @click="mobileMenuOpen = false">
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
                    @click="mobileMenuOpen = false"
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
                        @click="mobileMenuOpen = false"
                    >
                        <component :is="item.icon" :size="18" :stroke-width="1.8" />
                        <span>{{ item.label }}</span>
                        <span
                            v-if="item.section === 'campaigns'"
                            class="ml-auto rounded-md bg-white px-1.5 py-0.5 text-[10px] font-semibold text-[#8b9890]"
                        >3</span>
                    </Link>
                </div>

                <p class="mb-3 mt-9 px-3 text-[10px] font-bold uppercase tracking-[1.2px] text-[#a4aea7]">Workspace</p>
                <Link
                    href="/settings"
                    class="flex items-center gap-3 rounded-xl px-3 py-[11px] text-[13px] font-medium transition-colors"
                    :class="section === 'settings' ? 'bg-[#eaf4ee] font-semibold text-[#16734f]' : 'text-[#68766e] hover:bg-[#f5f7f5] hover:text-[#2b3b31]'"
                    @click="mobileMenuOpen = false"
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
                <a href="#panduan" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#16845b]">
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

        <div class="min-h-screen lg:pl-[258px]">
            <header class="sticky top-0 z-30 flex h-[76px] items-center justify-between border-b border-[#e9ede9] bg-white/95 px-5 backdrop-blur sm:px-8">
                <div class="flex items-center gap-3">
                    <button
                        class="rounded-lg p-2 text-[#718078] hover:bg-[#f3f6f3] lg:hidden"
                        aria-label="Buka navigasi"
                        @click="mobileMenuOpen = true"
                    >
                        <Menu :size="20" />
                    </button>
                    <div>
                        <p class="text-[11px] font-medium text-[#98a29b]">Workspace <span class="mx-1.5 text-[#c4ccc6]">/</span> <span class="text-[#637168]">{{ currentTitle }}</span></p>
                        <h1 class="mb-0 mt-0.5 text-[15px] font-semibold tracking-[-0.2px] text-[#28352d]">{{ currentTitle }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-2.5">
                    <button class="hidden h-9 w-[210px] items-center gap-2 rounded-lg border border-[#e9ede9] px-3 text-left text-xs text-[#a1aaa4] sm:flex">
                        <Search :size="15" />
                        <span>Cari apa saja...</span>
                        <span class="ml-auto rounded border border-[#e9ede9] px-1 py-0.5 text-[9px]">⌘ K</span>
                    </button>
                    <button class="relative flex h-9 w-9 items-center justify-center rounded-lg text-[#77847b] hover:bg-[#f3f6f3]" aria-label="Notifikasi">
                        <Bell :size="18" :stroke-width="1.8" />
                        <span class="absolute right-[8px] top-[7px] h-1.5 w-1.5 rounded-full bg-[#dc7959] ring-2 ring-white" />
                    </button>
                    <span class="hidden h-7 w-px bg-[#e9ede9] sm:block" />
                    <button class="flex items-center gap-2 rounded-lg p-1.5 pr-0.5 hover:bg-[#f6f8f6]">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#dcece1] text-[11px] font-bold text-[#347353]">SF</span>
                        <ChevronDown :size="14" class="hidden text-[#859188] sm:block" />
                    </button>
                </div>
            </header>

            <main class="mx-auto max-w-[1440px] px-5 pb-10 pt-7 sm:px-8 sm:pt-9">
                <template v-if="section === 'dashboard'">
                    <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <div class="mb-2 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-[#16845b]" />
                                <p class="m-0 text-[11px] font-semibold uppercase tracking-[1.15px] text-[#829087]">{{ todayLabel }}</p>
                            </div>
                            <h2 class="m-0 text-[25px] font-semibold tracking-[-1px] text-[#25332a] sm:text-[29px]">Selamat datang, SapaFlow Studio <span class="ml-1">👋</span></h2>
                            <p class="mb-0 mt-2 text-[13px] text-[#849087]">Ini yang terjadi di workspace kamu hari ini.</p>
                        </div>
                        <button
                            class="inline-flex h-10 items-center justify-center gap-2 self-start rounded-[10px] bg-[#16845b] px-4 text-xs font-semibold text-white shadow-sm shadow-[#16845b]/15 transition hover:bg-[#116f4b] sm:self-auto"
                            @click="showCampaignModal = true"
                        >
                            <Plus :size="16" /> Buat kampanye
                        </button>
                    </div>

                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-[#f0e4c8] bg-[#fffbf2] px-4 py-3.5">
                        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#f8edcf] text-[#a77a2d]">
                            <LockKeyhole :size="15" />
                        </span>
                        <div class="flex-1">
                            <p class="mb-0 text-xs font-semibold text-[#655333]">Mode demo — WhatsApp API belum terhubung</p>
                            <p class="mb-0 mt-1 text-[11px] leading-relaxed text-[#8e7c59]">Angka di bawah adalah data contoh. Kampanye hanya tersimpan sebagai draf dan tidak ada pesan yang dikirim.</p>
                        </div>
                        <Link href="/settings" class="hidden items-center gap-1 self-center whitespace-nowrap text-[11px] font-semibold text-[#9a7330] sm:flex">
                            Hubungkan API <ArrowRight :size="13" />
                        </Link>
                    </div>

                    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <article class="rounded-2xl border border-[#e9ede9] bg-white p-5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-[#7d8981]">Total kontak</span>
                                <span class="flex h-8 w-8 items-center justify-center rounded-[10px] bg-[#eaf4ee] text-[#16845b]"><UsersRound :size="16" /></span>
                            </div>
                            <div class="mt-4 flex items-end justify-between">
                                <span class="text-[26px] font-semibold leading-none tracking-[-1px] text-[#27352c]">248</span>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-semibold text-[#4c9a70]"><ArrowUpRight :size="13" /> 12%</span>
                            </div>
                            <p class="mb-0 mt-2 text-[10px] text-[#a0aaa3]">dibanding bulan lalu</p>
                        </article>
                        <article class="rounded-2xl border border-[#e9ede9] bg-white p-5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-[#7d8981]">Kampanye bulan ini</span>
                                <span class="flex h-8 w-8 items-center justify-center rounded-[10px] bg-[#f2edf9] text-[#8a69b2]"><Megaphone :size="16" /></span>
                            </div>
                            <div class="mt-4 flex items-end justify-between">
                                <span class="text-[26px] font-semibold leading-none tracking-[-1px] text-[#27352c]">3</span>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-semibold text-[#a4aaa5]"><span class="h-1.5 w-1.5 rounded-full bg-[#9b79bf]" /> 1 terjadwal</span>
                            </div>
                            <p class="mb-0 mt-2 text-[10px] text-[#a0aaa3]">kampanye bulan ini</p>
                        </article>
                        <article class="rounded-2xl border border-[#e9ede9] bg-white p-5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-[#7d8981]">Pesan terkirim</span>
                                <span class="flex h-8 w-8 items-center justify-center rounded-[10px] bg-[#e9f1fa] text-[#5880b4]"><Send :size="15" /></span>
                            </div>
                            <div class="mt-4 flex items-end justify-between">
                                <span class="text-[26px] font-semibold leading-none tracking-[-1px] text-[#27352c]">1.284</span>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-semibold text-[#d17b67]"><ArrowDownRight :size="13" /> 2,4%</span>
                            </div>
                            <p class="mb-0 mt-2 text-[10px] text-[#a0aaa3]">dibanding bulan lalu</p>
                        </article>
                        <article class="rounded-2xl border border-[#e9ede9] bg-white p-5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-[#7d8981]">Tingkat respons</span>
                                <span class="flex h-8 w-8 items-center justify-center rounded-[10px] bg-[#fdf0e8] text-[#cf8558]"><MessageCircle :size="16" /></span>
                            </div>
                            <div class="mt-4 flex items-end justify-between">
                                <span class="text-[26px] font-semibold leading-none tracking-[-1px] text-[#27352c]">37,8%</span>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-semibold text-[#4c9a70]"><ArrowUpRight :size="13" /> 4,1%</span>
                            </div>
                            <p class="mb-0 mt-2 text-[10px] text-[#a0aaa3]">dibanding bulan lalu</p>
                        </article>
                    </div>

                    <div class="mb-6 grid grid-cols-1 gap-5 xl:grid-cols-[1.65fr_1fr]">
                        <section class="rounded-2xl border border-[#e9ede9] bg-white p-5 sm:p-6">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Aktivitas pesan</h3>
                                    <p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">Ringkasan pesan selama 7 hari terakhir</p>
                                </div>
                                <button class="inline-flex items-center gap-1 rounded-lg border border-[#e9ede9] px-2.5 py-1.5 text-[10px] font-medium text-[#728077]">
                                    7 hari terakhir <ChevronDown :size="13" />
                                </button>
                            </div>
                            <div class="mt-6 flex items-end gap-3">
                                <span class="text-[27px] font-semibold tracking-[-1px] text-[#29372e]">1.284</span>
                                <span class="mb-1 text-[10px] font-medium text-[#8d9990]">pesan terkirim</span>
                                <span class="mb-1 ml-auto inline-flex items-center gap-1 text-[10px] font-semibold text-[#4c9a70]"><ArrowUpRight :size="13" /> 8,2%</span>
                            </div>
                            <div class="mt-5 grid h-[138px] grid-cols-7 items-end gap-3 border-b border-[#edf0ed] pb-0 sm:gap-5">
                                <div v-for="(item, index) in weeklyMessages" :key="item.day" class="flex h-full flex-col items-center justify-end gap-2">
                                    <div class="flex h-full w-full items-end justify-center">
                                        <span
                                            class="w-full max-w-[33px] rounded-t-md transition-all"
                                            :class="index === 5 ? 'bg-[#16845b]' : 'bg-[#dcece2]'"
                                            :style="{ height: `${item.value}%` }"
                                        />
                                    </div>
                                    <span class="mb-2 text-[10px] text-[#9ba59e]">{{ item.day }}</span>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center gap-4 text-[10px] text-[#89958c]">
                                <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-sm bg-[#16845b]" /> Pesan terkirim</span>
                                <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-sm bg-[#dcece2]" /> Rata-rata harian</span>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-[#e9ede9] bg-white p-5 sm:p-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Performa kampanye</h3>
                                    <p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">Ringkasan bulan ini</p>
                                </div>
                                <button class="rounded-lg p-1.5 text-[#96a199] hover:bg-[#f5f7f5]" aria-label="Menu performa"><MoreHorizontal :size="17" /></button>
                            </div>
                            <div class="mt-7 flex items-center gap-5">
                                <div class="relative flex h-[118px] w-[118px] shrink-0 items-center justify-center rounded-full" style="background: conic-gradient(#16845b 0deg 258deg, #dcece2 258deg 360deg)">
                                    <div class="flex h-[88px] w-[88px] flex-col items-center justify-center rounded-full bg-white">
                                        <span class="text-[21px] font-semibold tracking-[-1px] text-[#2b392f]">71%</span>
                                        <span class="mt-0.5 text-[9px] text-[#9ba59e]">terkirim</span>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 space-y-4">
                                    <div>
                                        <div class="mb-1.5 flex justify-between gap-2 text-[10px]"><span class="text-[#7b8880]">Pesan terkirim</span><span class="font-semibold text-[#38473d]">912</span></div>
                                        <div class="h-1.5 overflow-hidden rounded-full bg-[#eef2ee]"><div class="h-full w-[71%] rounded-full bg-[#16845b]" /></div>
                                    </div>
                                    <div>
                                        <div class="mb-1.5 flex justify-between gap-2 text-[10px]"><span class="text-[#7b8880]">Dibaca</span><span class="font-semibold text-[#38473d]">684</span></div>
                                        <div class="h-1.5 overflow-hidden rounded-full bg-[#eef2ee]"><div class="h-full w-[53%] rounded-full bg-[#81b99a]" /></div>
                                    </div>
                                    <div>
                                        <div class="mb-1.5 flex justify-between gap-2 text-[10px]"><span class="text-[#7b8880]">Direspons</span><span class="font-semibold text-[#38473d]">346</span></div>
                                        <div class="h-1.5 overflow-hidden rounded-full bg-[#eef2ee]"><div class="h-full w-[27%] rounded-full bg-[#bed9c7]" /></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-between border-t border-[#f0f2f0] pt-4">
                                <span class="text-[10px] text-[#99a39c]">Dari 1.284 pesan</span>
                                <Link href="/analytics" class="inline-flex items-center gap-1 text-[10px] font-semibold text-[#16845b]">Lihat analitik <ArrowRight :size="13" /></Link>
                            </div>
                        </section>
                    </div>

                    <section class="overflow-hidden rounded-2xl border border-[#e9ede9] bg-white">
                        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-5 sm:px-6">
                            <div>
                                <h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Kampanye terbaru</h3>
                                <p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">Lihat aktivitas kampanye pesan kamu</p>
                            </div>
                            <Link href="/campaigns" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#16845b]">Semua kampanye <ArrowRight :size="13" /></Link>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[620px] border-collapse text-left">
                                <thead>
                                    <tr class="border-y border-[#f0f2f0] bg-[#fbfcfb] text-[10px] font-medium text-[#98a29b]">
                                        <th class="px-6 py-3 font-medium">Nama kampanye</th>
                                        <th class="px-4 py-3 font-medium">Status</th>
                                        <th class="px-4 py-3 font-medium">Waktu</th>
                                        <th class="px-4 py-3 font-medium">Penerima</th>
                                        <th class="w-12 px-4 py-3" />
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="campaign in campaigns.slice(0, 3)" :key="campaign.name" class="border-b border-[#f2f4f2] last:border-0">
                                        <td class="px-6 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#f1f5f1] text-[#6d8373]"><component :is="campaign.icon" :size="15" /></span>
                                                <span>
                                                    <span class="block text-xs font-semibold text-[#3c4940]">{{ campaign.name }}</span>
                                                    <span class="mt-1 block text-[10px] text-[#9aa49d]">{{ campaign.detail }}</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-medium" :class="campaign.tone === 'green' ? 'bg-[#edf6ef] text-[#46815a]' : campaign.tone === 'blue' ? 'bg-[#edf3fa] text-[#5a7ea6]' : 'bg-[#f2f3f2] text-[#818b84]'">
                                                <span class="h-1.5 w-1.5 rounded-full" :class="campaign.tone === 'green' ? 'bg-[#64a477]' : campaign.tone === 'blue' ? 'bg-[#7499c1]' : 'bg-[#9ca59e]'" />
                                                {{ campaign.status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-[10px] text-[#89958c]">{{ campaign.time }}</td>
                                        <td class="px-4 py-3.5 text-[10px] font-medium text-[#65736a]">{{ campaign.detail.match(/\d+ penerima/)?.[0] ?? '—' }}</td>
                                        <td class="px-4 py-3.5"><button class="rounded-md p-1 text-[#a0aaa3] hover:bg-[#f4f6f4]" aria-label="Opsi kampanye"><MoreHorizontal :size="16" /></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mb-0 border-t border-[#f0f2f0] px-6 py-3 text-[10px] text-[#a0aaa3]">Data contoh untuk pratinjau. Kampanye belum dikirim ke WhatsApp.</p>
                    </section>
                </template>

                <template v-else-if="section === 'campaigns'">
                    <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <p class="mb-2 text-[11px] font-semibold uppercase tracking-[1.1px] text-[#829087]">Kelola pesan massal</p>
                            <h2 class="m-0 text-[27px] font-semibold tracking-[-1px] text-[#25332a]">Kampanye</h2>
                            <p class="mb-0 mt-2 text-[13px] text-[#849087]">Siapkan pesan untuk penerima yang sudah memberi izin.</p>
                        </div>
                        <button class="inline-flex h-10 items-center justify-center gap-2 self-start rounded-[10px] bg-[#16845b] px-4 text-xs font-semibold text-white hover:bg-[#116f4b] sm:self-auto" @click="showCampaignModal = true"><Plus :size="16" /> Buat kampanye</button>
                    </div>
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-[#f0e4c8] bg-[#fffbf2] px-4 py-3.5">
                        <ShieldCheck :size="17" class="mt-0.5 shrink-0 text-[#a77a2d]" />
                        <p class="m-0 text-[11px] leading-relaxed text-[#806d49]">Mode demo aktif. Kampanye yang dibuat hanya menjadi draf lokal dan tidak mengirim pesan. Kirim pesan hanya kepada kontak yang secara eksplisit menyetujui komunikasi WhatsApp.</p>
                    </div>
                    <section class="overflow-hidden rounded-2xl border border-[#e9ede9] bg-white">
                        <div class="flex items-center justify-between border-b border-[#f0f2f0] px-5 py-4 sm:px-6">
                            <div><h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Semua kampanye</h3><p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">{{ campaigns.length }} kampanye</p></div>
                            <button class="rounded-lg border border-[#e9ede9] px-3 py-2 text-[11px] font-medium text-[#728077]">Semua status <ChevronDown :size="13" class="ml-1 inline" /></button>
                        </div>
                        <div class="divide-y divide-[#f0f2f0]">
                            <article v-for="campaign in campaigns" :key="campaign.name" class="flex items-center gap-3 px-5 py-4 sm:px-6">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#f1f5f1] text-[#6d8373]"><component :is="campaign.icon" :size="16" /></span>
                                <div class="min-w-0 flex-1"><p class="m-0 truncate text-xs font-semibold text-[#3c4940]">{{ campaign.name }}</p><p class="mb-0 mt-1 text-[10px] text-[#9aa49d]">{{ campaign.detail }} · {{ campaign.time }}</p></div>
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-medium" :class="campaign.tone === 'green' ? 'bg-[#edf6ef] text-[#46815a]' : campaign.tone === 'blue' ? 'bg-[#edf3fa] text-[#5a7ea6]' : 'bg-[#f2f3f2] text-[#818b84]'">{{ campaign.status }}</span>
                                <button class="hidden rounded-md p-1 text-[#a0aaa3] hover:bg-[#f4f6f4] sm:block" aria-label="Opsi kampanye"><MoreHorizontal :size="17" /></button>
                            </article>
                        </div>
                    </section>
                </template>

                <template v-else-if="section === 'contacts'">
                    <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <p class="mb-2 text-[11px] font-semibold uppercase tracking-[1.1px] text-[#829087]">Audiens kamu</p>
                            <h2 class="m-0 text-[27px] font-semibold tracking-[-1px] text-[#25332a]">Kontak</h2>
                            <p class="mb-0 mt-2 text-[13px] text-[#849087]">Kelola kontak dan status persetujuan pesan mereka.</p>
                        </div>
                        <button class="inline-flex h-10 items-center gap-2 self-start rounded-[10px] bg-[#16845b] px-4 text-xs font-semibold text-white hover:bg-[#116f4b]" @click="showToast('Impor kontak akan tersedia setelah backend disiapkan.')"><Plus :size="16" /> Impor kontak</button>
                    </div>
                    <div class="mb-5 flex items-start gap-3 rounded-xl border border-[#dfece2] bg-[#f3f8f4] px-4 py-3.5">
                        <ShieldCheck :size="17" class="mt-0.5 shrink-0 text-[#43835a]" />
                        <p class="m-0 text-[11px] leading-relaxed text-[#55745d]">Hanya kontak dengan persetujuan aktif yang boleh menerima pesan. Hormati permintaan berhenti berlangganan dan simpan bukti persetujuan.</p>
                    </div>
                    <section class="overflow-hidden rounded-2xl border border-[#e9ede9] bg-white">
                        <div class="flex flex-col justify-between gap-3 border-b border-[#f0f2f0] px-5 py-4 sm:flex-row sm:items-center sm:px-6">
                            <div><h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Semua kontak</h3><p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">{{ filteredContacts.length }} kontak contoh</p></div>
                            <label class="flex h-9 items-center gap-2 rounded-lg border border-[#e9ede9] px-3 text-[#99a39c] sm:w-[230px]">
                                <Search :size="14" />
                                <input v-model="contactSearch" class="w-full border-0 bg-transparent p-0 text-xs text-[#455249] outline-none placeholder:text-[#aab2ac]" placeholder="Cari kontak..." aria-label="Cari kontak">
                            </label>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[650px] border-collapse text-left">
                                <thead><tr class="border-b border-[#f0f2f0] bg-[#fbfcfb] text-[10px] text-[#98a29b]"><th class="px-6 py-3 font-medium">Nama kontak</th><th class="px-4 py-3 font-medium">Grup</th><th class="px-4 py-3 font-medium">Persetujuan WhatsApp</th><th class="px-4 py-3 font-medium">Status</th></tr></thead>
                                <tbody>
                                    <tr v-for="contact in filteredContacts" :key="contact.phone" class="border-b border-[#f2f4f2] last:border-0">
                                        <td class="px-6 py-3.5"><div class="flex items-center gap-3"><span class="flex h-9 w-9 items-center justify-center rounded-full text-[10px] font-bold" :class="contact.color">{{ contact.initials }}</span><span><span class="block text-xs font-semibold text-[#3c4940]">{{ contact.name }}</span><span class="mt-1 block text-[10px] text-[#9aa49d]">{{ contact.phone }}</span></span></div></td>
                                        <td class="px-4 py-3.5 text-[11px] text-[#7c8980]">{{ contact.group }}</td>
                                        <td class="px-4 py-3.5 text-[10px] text-[#7c8980]">Persetujuan tercatat</td>
                                        <td class="px-4 py-3.5"><span class="inline-flex items-center gap-1.5 rounded-full bg-[#edf6ef] px-2.5 py-1 text-[10px] font-medium text-[#46815a]"><CheckCircle2 :size="12" /> Opt-in aktif</span></td>
                                    </tr>
                                    <tr v-if="filteredContacts.length === 0"><td colspan="4" class="px-6 py-10 text-center text-xs text-[#89958c]">Kontak tidak ditemukan.</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="mb-0 border-t border-[#f0f2f0] px-6 py-3 text-[10px] text-[#a0aaa3]">Data contoh dengan nomor yang disamarkan. Kontak belum tersimpan ke database.</p>
                    </section>
                </template>

                <template v-else-if="section === 'templates'">
                    <div class="mb-7">
                        <p class="mb-2 text-[11px] font-semibold uppercase tracking-[1.1px] text-[#829087]">Pesan yang konsisten</p>
                        <h2 class="m-0 text-[27px] font-semibold tracking-[-1px] text-[#25332a]">Template pesan</h2>
                        <p class="mb-0 mt-2 text-[13px] text-[#849087]">Siapkan format pesan untuk kampanye berikutnya.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        <article v-for="template in [
                            { title: 'Sambutan pelanggan baru', category: 'Layanan pelanggan', text: 'Halo {{nama}}, selamat datang! Senang bisa membantu. Balas STOP kapan saja jika tidak ingin menerima pesan lagi.', icon: Sparkles },
                            { title: 'Info promo bulanan', category: 'Promosi', text: 'Hai {{nama}}, ada penawaran spesial untukmu bulan ini. Lihat detailnya di {{tautan}}. Balas STOP untuk berhenti.', icon: Megaphone },
                            { title: 'Pengingat janji temu', category: 'Pengingat', text: 'Halo {{nama}}, ini pengingat jadwalmu pada {{tanggal}} pukul {{waktu}}. Balas pesan ini jika perlu menjadwalkan ulang.', icon: CalendarDays },
                        ]" :key="template.title" class="rounded-2xl border border-[#e9ede9] bg-white p-5">
                            <div class="mb-4 flex items-center justify-between"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#eaf4ee] text-[#16845b]"><component :is="template.icon" :size="17" /></span><span class="rounded-full bg-[#f4f6f4] px-2.5 py-1 text-[9px] font-medium text-[#829087]">{{ template.category }}</span></div>
                            <h3 class="m-0 text-sm font-semibold text-[#354239]">{{ template.title }}</h3>
                            <p class="mb-0 mt-3 min-h-[66px] text-[11px] leading-[1.7] text-[#859188]">{{ template.text }}</p>
                            <div class="mt-4 flex items-center justify-between border-t border-[#f0f2f0] pt-3"><span class="text-[10px] text-[#a0aaa3]">Data pratinjau</span><button class="text-[10px] font-semibold text-[#16845b]" @click="showToast('Editor template akan tersedia setelah backend disiapkan.')">Gunakan template <ArrowRight :size="12" class="ml-0.5 inline" /></button></div>
                        </article>
                    </div>
                    <p class="mt-5 text-[11px] text-[#8f9a92]">Pastikan template promosi mematuhi kebijakan WhatsApp dan hanya dikirim kepada penerima yang menyetujuinya.</p>
                </template>

                <template v-else-if="section === 'analytics'">
                    <div class="mb-7">
                        <p class="mb-2 text-[11px] font-semibold uppercase tracking-[1.1px] text-[#829087]">Pahami hasil pesanmu</p>
                        <h2 class="m-0 text-[27px] font-semibold tracking-[-1px] text-[#25332a]">Analitik</h2>
                        <p class="mb-0 mt-2 text-[13px] text-[#849087]">Ringkasan performa dari data contoh.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <article v-for="metric in [{ label: 'Pesan terkirim', value: '1.284', icon: Send }, { label: 'Dibaca', value: '684', icon: Check }, { label: 'Respons', value: '346', icon: MessageCircle }]" :key="metric.label" class="rounded-2xl border border-[#e9ede9] bg-white p-5">
                            <div class="flex items-center justify-between text-xs text-[#7d8981]">{{ metric.label }}<component :is="metric.icon" :size="16" class="text-[#16845b]" /></div>
                            <p class="mb-0 mt-4 text-[26px] font-semibold tracking-[-1px] text-[#27352c]">{{ metric.value }}</p>
                            <p class="mb-0 mt-1 text-[10px] text-[#a0aaa3]">Data contoh bulan ini</p>
                        </article>
                    </div>
                    <div class="mt-5 rounded-2xl border border-[#f0e4c8] bg-[#fffbf2] p-5 text-[11px] leading-relaxed text-[#806d49]">Analitik ini adalah ilustrasi antarmuka. Data pengiriman yang sebenarnya akan tersedia setelah backend dan WhatsApp API diintegrasikan.</div>
                </template>

                <template v-else>
                    <div class="mb-7">
                        <p class="mb-2 text-[11px] font-semibold uppercase tracking-[1.1px] text-[#829087]">Konfigurasi workspace</p>
                        <h2 class="m-0 text-[27px] font-semibold tracking-[-1px] text-[#25332a]">Pengaturan</h2>
                        <p class="mb-0 mt-2 text-[13px] text-[#849087]">Konfigurasi koneksi WhatsApp kamu.</p>
                    </div>
                    <section class="max-w-2xl rounded-2xl border border-[#e9ede9] bg-white p-6">
                        <div class="flex items-start gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#fff5e3] text-[#ad7b2c]"><LockKeyhole :size="19" /></span>
                            <div><h3 class="m-0 text-sm font-semibold text-[#354239]">WhatsApp API belum dikonfigurasi</h3><p class="mb-0 mt-2 text-xs leading-relaxed text-[#849087]">Kredensial API belum ditambahkan. Saat siap, kita dapat menghubungkan penyedia WhatsApp Business API pilihanmu melalui konfigurasi server yang aman.</p><span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-[#fff7e9] px-2.5 py-1 text-[10px] font-medium text-[#a37a36]"><span class="h-1.5 w-1.5 rounded-full bg-[#d8a84d]" /> Belum terhubung</span></div>
                        </div>
                        <div class="mt-6 rounded-xl bg-[#f7f9f7] p-4 text-[11px] leading-relaxed text-[#728077]"><span class="font-semibold text-[#4a5a4e]">Catatan keamanan:</span> API key dan token akses akan disimpan di server, tidak di browser. Jangan masukkan kredensial sampai backend integrasi disiapkan.</div>
                    </section>
                </template>

                <footer class="mt-9 flex flex-wrap items-center justify-between gap-2 border-t border-[#e9ede9] pt-5 text-[10px] text-[#a0aaa3]">
                    <span>© {{ currentYear }} SapaFlow. Dibuat untuk komunikasi yang lebih bermakna.</span>
                    <a id="panduan" href="https://www.whatsapp.com/legal/business-policy/" target="_blank" rel="noreferrer" class="inline-flex items-center gap-1 font-medium text-[#819087] hover:text-[#16845b]"><CircleHelp :size="13" /> Kebijakan WhatsApp Business</a>
                </footer>
            </main>
        </div>

        <Transition enter-active-class="transition duration-200" enter-from-class="translate-y-2 opacity-0" enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="showCampaignModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-[#18231d]/45 p-4" @click.self="showCampaignModal = false">
                <section class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <div><h2 class="m-0 text-lg font-semibold text-[#27352c]">Buat draf kampanye</h2><p class="mb-0 mt-1.5 text-xs text-[#89958c]">Siapkan pesan untuk ditinjau sebelum integrasi API tersedia.</p></div>
                        <button class="rounded-lg p-1.5 text-[#89958c] hover:bg-[#f3f6f3]" aria-label="Tutup" @click="showCampaignModal = false"><X :size="18" /></button>
                    </div>
                    <form class="space-y-4" @submit.prevent="createDraft">
                        <label class="block"><span class="mb-1.5 block text-xs font-medium text-[#58665d]">Nama kampanye</span><input v-model.trim="campaignForm.name" required maxlength="80" class="h-10 w-full rounded-lg border border-[#e2e8e3] px-3 text-sm outline-none focus:border-[#16845b] focus:ring-2 focus:ring-[#16845b]/10" placeholder="Contoh: Promo pelanggan bulan ini"></label>
                        <label class="block"><span class="mb-1.5 block text-xs font-medium text-[#58665d]">Pesan</span><textarea v-model.trim="campaignForm.message" required maxlength="1000" rows="4" class="w-full resize-y rounded-lg border border-[#e2e8e3] px-3 py-2.5 text-sm leading-relaxed outline-none focus:border-[#16845b] focus:ring-2 focus:ring-[#16845b]/10" placeholder="Tulis pesan yang relevan dan diharapkan penerima..."></textarea></label>
                        <label class="block"><span class="mb-1.5 block text-xs font-medium text-[#58665d]">Perkiraan jumlah penerima</span><input v-model.number="campaignForm.audience" required type="number" min="1" max="100000" class="h-10 w-full rounded-lg border border-[#e2e8e3] px-3 text-sm outline-none focus:border-[#16845b] focus:ring-2 focus:ring-[#16845b]/10" placeholder="0"></label>
                        <label class="flex cursor-pointer items-start gap-2.5 rounded-lg bg-[#f4f8f4] p-3 text-[11px] leading-relaxed text-[#66766b]"><input v-model="campaignForm.confirmedConsent" required type="checkbox" class="mt-0.5 accent-[#16845b]"><span>Saya memastikan penerima telah menyetujui pesan dan permintaan opt-out akan dihormati.</span></label>
                        <div class="flex justify-end gap-2 border-t border-[#f0f2f0] pt-4">
                            <button type="button" class="h-9 rounded-lg border border-[#e2e8e3] px-3.5 text-xs font-medium text-[#6f7c73] hover:bg-[#f7f9f7]" @click="showCampaignModal = false">Batal</button>
                            <button type="submit" class="h-9 rounded-lg bg-[#16845b] px-4 text-xs font-semibold text-white hover:bg-[#116f4b]">Simpan draf</button>
                        </div>
                    </form>
                </section>
            </div>
        </Transition>

        <Transition enter-active-class="transition duration-200" enter-from-class="translate-y-2 opacity-0" enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="toastMessage" class="fixed bottom-5 right-5 z-[70] flex items-center gap-2.5 rounded-xl bg-[#25372b] px-4 py-3 text-xs font-medium text-white shadow-lg">
                <CheckCircle2 :size="16" class="text-[#8ed2a5]" /> {{ toastMessage }}
            </div>
        </Transition>
    </div>
</template>
