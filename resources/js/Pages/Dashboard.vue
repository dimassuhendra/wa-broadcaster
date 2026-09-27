<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {
    Megaphone,
    MessageCircle,
    Plus,
    Send,
    UsersRound,
} from '@lucide/vue';
import AppLayout from '../Layouts/AppLayout.vue';
import CampaignPerformanceCard from '../Components/CampaignPerformanceCard.vue';
import DashboardActivityChart from '../Components/DashboardActivityChart.vue';
import DashboardNotice from '../Components/DashboardNotice.vue';
import RecentCampaignsTable from '../Components/RecentCampaignsTable.vue';
import StatCard from '../Components/StatCard.vue';

defineOptions({
    layout: AppLayout,
});

defineProps({
    dashboard: {
        type: Object,
        required: true,
    },
});

const todayLabel = new Intl.DateTimeFormat('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
}).format(new Date());
</script>

<template>
    <Head title="Ringkasan — SapaFlow" />

    <div>
        <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <div class="mb-2 flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#16845b]" />
                    <p class="m-0 text-[11px] font-semibold uppercase tracking-[1.15px] text-[#829087]">{{ todayLabel }}</p>
                </div>
                <h2 class="m-0 text-[25px] font-semibold tracking-[-1px] text-[#25332a] sm:text-[29px]">Selamat datang, SapaFlow Studio <span class="ml-1">👋</span></h2>
                <p class="mb-0 mt-2 text-[13px] text-[#849087]">Ini yang terjadi di workspace kamu hari ini.</p>
            </div>
            <Link
                href="/campaigns"
                class="inline-flex h-10 items-center justify-center gap-2 self-start rounded-[10px] bg-[#16845b] px-4 text-xs font-semibold text-white shadow-sm shadow-[#16845b]/15 transition hover:bg-[#116f4b] sm:self-auto"
            >
                <Plus :size="16" /> Buat kampanye
            </Link>
        </div>

        <DashboardNotice
            title="Mode demo — WhatsApp API belum terhubung"
            message="Angka pada dashboard diambil dari database. Kampanye hanya disiapkan untuk pratinjau dan tidak ada pesan yang dikirim."
        />

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <StatCard
                label="Total kontak"
                :value="dashboard.stats.contacts.value"
                :change="dashboard.stats.contacts.change"
                :icon="UsersRound"
                change-label="kontak baru vs bulan lalu"
            />
            <StatCard
                label="Kampanye aktif"
                :value="dashboard.stats.activeCampaigns"
                :icon="Megaphone"
                icon-class="bg-[#f2edf9] text-[#8a69b2]"
                change-label="terjadwal atau sedang dikirim"
            />
            <StatCard
                label="Pesan terkirim"
                :value="dashboard.stats.sentMessages.value"
                :change="dashboard.stats.sentMessages.change"
                :icon="Send"
                icon-class="bg-[#e9f1fa] text-[#5880b4]"
            />
            <StatCard
                label="Tingkat respons"
                :value="dashboard.stats.responseRate.value"
                :change="dashboard.stats.responseRate.change"
                change-unit="pp"
                suffix="%"
                :icon="MessageCircle"
                icon-class="bg-[#fdf0e8] text-[#cf8558]"
            />
        </div>

        <div class="mb-6 grid grid-cols-1 gap-5 xl:grid-cols-[1.65fr_1fr]">
            <DashboardActivityChart :activity="dashboard.weeklyMessages" />
            <CampaignPerformanceCard :performance="dashboard.campaignPerformance" />
        </div>

        <RecentCampaignsTable :campaigns="dashboard.recentCampaigns" />
    </div>
</template>
