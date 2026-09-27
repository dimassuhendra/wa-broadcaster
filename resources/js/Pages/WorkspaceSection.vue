<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import {
    CalendarDays,
    Check,
    CheckCircle2,
    ChevronDown,
    FileText,
    LockKeyhole,
    Megaphone,
    MessageCircle,
    MoreHorizontal,
    Plus,
    Search,
    Send,
    ShieldCheck,
    Sparkles,
    X,
} from '@lucide/vue';
import AppLayout from '../Layouts/AppLayout.vue';
import CampaignStatusBadge from '../Components/CampaignStatusBadge.vue';
import DashboardNotice from '../Components/DashboardNotice.vue';
import PageHeading from '../Components/PageHeading.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    section: {
        type: String,
        required: true,
    },
});

const pageTitles = {
    campaigns: 'Kampanye',
    contacts: 'Kontak',
    templates: 'Template pesan',
    analytics: 'Analitik',
    settings: 'Pengaturan',
};

const currentTitle = computed(() => pageTitles[props.section] ?? 'Workspace');
const contactSearch = ref('');
const showCampaignModal = ref(false);
const toastMessage = ref('');
const campaignForm = ref({
    name: '',
    message: '',
    audience: 0,
    confirmedConsent: false,
});
let toastTimeout;

const campaigns = ref([
    { id: 1, name: 'Promo akhir bulan', detail: 'Pelanggan loyal · 128 penerima', time: 'Hari ini, 10.42', status: 'sent', recipientCount: 128 },
    { id: 2, name: 'Pengingat jadwal', detail: 'Pelanggan aktif · 64 penerima', time: 'Hari ini, 09.15', status: 'scheduled', recipientCount: 64 },
    { id: 3, name: 'Sambutan pelanggan baru', detail: 'Pelanggan baru · 32 penerima', time: 'Kemarin, 15.20', status: 'draft', recipientCount: 32 },
]);

const contacts = [
    { name: 'Andi Pratama', phone: '+62 812-••••-1204', group: 'Pelanggan loyal', initials: 'AP', color: 'bg-[#e4efe8] text-[#24744d]' },
    { name: 'Dina Maharani', phone: '+62 813-••••-8702', group: 'Pelanggan aktif', initials: 'DM', color: 'bg-[#f5e9db] text-[#a46934]' },
    { name: 'Rizky Saputra', phone: '+62 857-••••-3401', group: 'Pelanggan baru', initials: 'RS', color: 'bg-[#e6e9f4] text-[#5466a6]' },
    { name: 'Nadia Putri', phone: '+62 878-••••-5210', group: 'Pelanggan aktif', initials: 'NP', color: 'bg-[#f3e5ed] text-[#9b5780]' },
];

const filteredContacts = computed(() => {
    const query = contactSearch.value.toLowerCase().trim();

    return contacts.filter((contact) =>
        `${contact.name} ${contact.phone} ${contact.group}`.toLowerCase().includes(query),
    );
});

const templates = [
    { title: 'Sambutan pelanggan baru', category: 'Layanan pelanggan', text: 'Halo {{nama}}, selamat datang! Senang bisa membantu. Balas STOP kapan saja jika tidak ingin menerima pesan lagi.', icon: Sparkles },
    { title: 'Info promo bulanan', category: 'Promosi', text: 'Hai {{nama}}, ada penawaran spesial untukmu bulan ini. Lihat detailnya di {{tautan}}. Balas STOP untuk berhenti.', icon: Megaphone },
    { title: 'Pengingat janji temu', category: 'Pengingat', text: 'Halo {{nama}}, ini pengingat jadwalmu pada {{tanggal}} pukul {{waktu}}. Balas pesan ini jika perlu menjadwalkan ulang.', icon: CalendarDays },
];

const analyticsMetrics = [
    { label: 'Pesan terkirim', value: '1.284', icon: Send },
    { label: 'Dibaca', value: '684', icon: Check },
    { label: 'Respons', value: '346', icon: MessageCircle },
];

function createDraft() {
    if (!campaignForm.value.confirmedConsent) {
        return;
    }

    campaigns.value.unshift({
        id: Date.now(),
        name: campaignForm.value.name,
        detail: `Daftar penerima · ${campaignForm.value.audience} penerima`,
        time: 'Baru saja',
        status: 'draft',
        recipientCount: campaignForm.value.audience,
    });
    showCampaignModal.value = false;
    campaignForm.value = { name: '', message: '', audience: 0, confirmedConsent: false };
    showToast('Pratinjau draf berhasil dibuat. Draf ini belum disimpan.');
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
    <div>
        <Head :title="`${currentTitle} — SapaFlow`" />

        <template v-if="section === 'campaigns'">
            <PageHeading
                eyebrow="Kelola pesan massal"
                title="Kampanye"
                description="Siapkan pesan untuk penerima yang sudah memberi izin."
            >
                <template #actions>
                    <button
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-[10px] bg-[#16845b] px-4 text-xs font-semibold text-white hover:bg-[#116f4b]"
                        @click="showCampaignModal = true"
                    >
                        <Plus :size="16" /> Buat kampanye
                    </button>
                </template>
            </PageHeading>
            <DashboardNotice
                title="Mode demo aktif"
                message="Daftar ini masih menggunakan data pratinjau. Draf yang dibuat hanya berlaku selama halaman terbuka dan tidak mengirim pesan."
                action-label="Pengaturan API"
            />
            <section class="overflow-hidden rounded-2xl border border-[#e9ede9] bg-white">
                <div class="flex items-center justify-between border-b border-[#f0f2f0] px-5 py-4 sm:px-6">
                    <div>
                        <h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Semua kampanye</h3>
                        <p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">{{ campaigns.length }} kampanye pratinjau</p>
                    </div>
                    <span class="rounded-lg border border-[#e9ede9] px-3 py-2 text-[11px] font-medium text-[#728077]">Semua status <ChevronDown :size="13" class="ml-1 inline" /></span>
                </div>
                <div class="divide-y divide-[#f0f2f0]">
                    <article v-for="campaign in campaigns" :key="campaign.id" class="flex items-center gap-3 px-5 py-4 sm:px-6">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#f1f5f1] text-[#6d8373]">
                            <component :is="campaign.status === 'scheduled' ? CalendarDays : campaign.status === 'sent' ? Send : FileText" :size="16" />
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="m-0 truncate text-xs font-semibold text-[#3c4940]">{{ campaign.name }}</p>
                            <p class="mb-0 mt-1 text-[10px] text-[#9aa49d]">{{ campaign.detail }} · {{ campaign.time }}</p>
                        </div>
                        <CampaignStatusBadge :status="campaign.status" />
                        <button class="hidden rounded-md p-1 text-[#a0aaa3] hover:bg-[#f4f6f4] sm:block" aria-label="Opsi kampanye"><MoreHorizontal :size="17" /></button>
                    </article>
                </div>
            </section>
        </template>

        <template v-else-if="section === 'contacts'">
            <PageHeading
                eyebrow="Audiens kamu"
                title="Kontak"
                description="Kelola kontak dan status persetujuan pesan mereka."
            >
                <template #actions>
                    <button
                        class="inline-flex h-10 items-center gap-2 rounded-[10px] bg-[#16845b] px-4 text-xs font-semibold text-white hover:bg-[#116f4b]"
                        @click="showToast('Impor kontak akan tersedia setelah backend disiapkan.')"
                    >
                        <Plus :size="16" /> Impor kontak
                    </button>
                </template>
            </PageHeading>
            <div class="mb-5 flex items-start gap-3 rounded-xl border border-[#dfece2] bg-[#f3f8f4] px-4 py-3.5">
                <ShieldCheck :size="17" class="mt-0.5 shrink-0 text-[#43835a]" />
                <p class="m-0 text-[11px] leading-relaxed text-[#55745d]">Hanya kontak dengan persetujuan aktif yang boleh menerima pesan. Hormati permintaan berhenti berlangganan dan simpan bukti persetujuan.</p>
            </div>
            <section class="overflow-hidden rounded-2xl border border-[#e9ede9] bg-white">
                <div class="flex flex-col justify-between gap-3 border-b border-[#f0f2f0] px-5 py-4 sm:flex-row sm:items-center sm:px-6">
                    <div>
                        <h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Kontak pratinjau</h3>
                        <p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">{{ filteredContacts.length }} kontak contoh</p>
                    </div>
                    <label class="flex h-9 items-center gap-2 rounded-lg border border-[#e9ede9] px-3 text-[#99a39c] sm:w-[230px]">
                        <Search :size="14" />
                        <input v-model="contactSearch" class="w-full border-0 bg-transparent p-0 text-xs text-[#455249] outline-none placeholder:text-[#aab2ac]" placeholder="Cari kontak..." aria-label="Cari kontak">
                    </label>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px] border-collapse text-left">
                        <thead>
                            <tr class="border-b border-[#f0f2f0] bg-[#fbfcfb] text-[10px] text-[#98a29b]">
                                <th class="px-6 py-3 font-medium">Nama kontak</th>
                                <th class="px-4 py-3 font-medium">Grup</th>
                                <th class="px-4 py-3 font-medium">Persetujuan WhatsApp</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="contact in filteredContacts" :key="contact.phone" class="border-b border-[#f2f4f2] last:border-0">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full text-[10px] font-bold" :class="contact.color">{{ contact.initials }}</span>
                                        <span>
                                            <span class="block text-xs font-semibold text-[#3c4940]">{{ contact.name }}</span>
                                            <span class="mt-1 block text-[10px] text-[#9aa49d]">{{ contact.phone }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-[11px] text-[#7c8980]">{{ contact.group }}</td>
                                <td class="px-4 py-3.5 text-[10px] text-[#7c8980]">Persetujuan tercatat</td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-[#edf6ef] px-2.5 py-1 text-[10px] font-medium text-[#46815a]"><CheckCircle2 :size="12" /> Opt-in aktif</span>
                                </td>
                            </tr>
                            <tr v-if="filteredContacts.length === 0">
                                <td colspan="4" class="px-6 py-10 text-center text-xs text-[#89958c]">Kontak tidak ditemukan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="mb-0 border-t border-[#f0f2f0] px-6 py-3 text-[10px] text-[#a0aaa3]">Data pratinjau dengan nomor yang disamarkan; belum terhubung ke database.</p>
            </section>
        </template>

        <template v-else-if="section === 'templates'">
            <PageHeading
                eyebrow="Pesan yang konsisten"
                title="Template pesan"
                description="Siapkan format pesan untuk kampanye berikutnya."
            />
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <article v-for="template in templates" :key="template.title" class="rounded-2xl border border-[#e9ede9] bg-white p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#eaf4ee] text-[#16845b]"><component :is="template.icon" :size="17" /></span>
                        <span class="rounded-full bg-[#f4f6f4] px-2.5 py-1 text-[9px] font-medium text-[#829087]">{{ template.category }}</span>
                    </div>
                    <h3 class="m-0 text-sm font-semibold text-[#354239]">{{ template.title }}</h3>
                    <p class="mb-0 mt-3 min-h-[66px] text-[11px] leading-[1.7] text-[#859188]">{{ template.text }}</p>
                    <div class="mt-4 flex items-center justify-between border-t border-[#f0f2f0] pt-3">
                        <span class="text-[10px] text-[#a0aaa3]">Data pratinjau</span>
                        <button class="text-[10px] font-semibold text-[#16845b]" @click="showToast('Editor template akan tersedia setelah backend disiapkan.')">Gunakan template</button>
                    </div>
                </article>
            </div>
            <p class="mt-5 text-[11px] text-[#8f9a92]">Pastikan template promosi mematuhi kebijakan WhatsApp dan hanya dikirim kepada penerima yang menyetujuinya.</p>
        </template>

        <template v-else-if="section === 'analytics'">
            <PageHeading
                eyebrow="Pahami hasil pesanmu"
                title="Analitik"
                description="Ringkasan performa dari data pratinjau."
            />
            <div class="grid gap-4 sm:grid-cols-3">
                <article v-for="metric in analyticsMetrics" :key="metric.label" class="rounded-2xl border border-[#e9ede9] bg-white p-5">
                    <div class="flex items-center justify-between text-xs text-[#7d8981]">
                        {{ metric.label }} <component :is="metric.icon" :size="16" class="text-[#16845b]" />
                    </div>
                    <p class="mb-0 mt-4 text-[26px] font-semibold tracking-[-1px] text-[#27352c]">{{ metric.value }}</p>
                    <p class="mb-0 mt-1 text-[10px] text-[#a0aaa3]">Data pratinjau</p>
                </article>
            </div>
            <DashboardNotice
                title="Data analitik belum terhubung"
                message="Statistik ini hanya ilustrasi antarmuka. Data pengiriman yang sebenarnya akan tersedia setelah backend dan WhatsApp API diintegrasikan."
                action-label="Pengaturan API"
            />
        </template>

        <template v-else>
            <PageHeading
                eyebrow="Konfigurasi workspace"
                title="Pengaturan"
                description="Konfigurasi koneksi WhatsApp kamu."
            />
            <section class="max-w-2xl rounded-2xl border border-[#e9ede9] bg-white p-6">
                <div class="flex items-start gap-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#fff5e3] text-[#ad7b2c]"><LockKeyhole :size="19" /></span>
                    <div>
                        <h3 class="m-0 text-sm font-semibold text-[#354239]">WhatsApp API belum dikonfigurasi</h3>
                        <p class="mb-0 mt-2 text-xs leading-relaxed text-[#849087]">Kredensial API belum ditambahkan. Saat siap, WhatsApp Business API bisa dihubungkan melalui konfigurasi server yang aman.</p>
                        <span class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-[#fff7e9] px-2.5 py-1 text-[10px] font-medium text-[#a37a36]"><span class="h-1.5 w-1.5 rounded-full bg-[#d8a84d]" /> Belum terhubung</span>
                    </div>
                </div>
                <div class="mt-6 rounded-xl bg-[#f7f9f7] p-4 text-[11px] leading-relaxed text-[#728077]"><span class="font-semibold text-[#4a5a4e]">Catatan keamanan:</span> API key dan token akses akan disimpan di server, tidak di browser.</div>
            </section>
        </template>

        <Transition enter-active-class="transition duration-200" enter-from-class="translate-y-2 opacity-0" enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="showCampaignModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-[#18231d]/45 p-4" @click.self="showCampaignModal = false">
                <section class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
                    <div class="mb-5 flex items-start justify-between gap-4">
                        <div>
                            <h2 class="m-0 text-lg font-semibold text-[#27352c]">Buat draf kampanye</h2>
                            <p class="mb-0 mt-1.5 text-xs text-[#89958c]">Siapkan pesan untuk ditinjau; draf belum disimpan ke database.</p>
                        </div>
                        <button class="rounded-lg p-1.5 text-[#89958c] hover:bg-[#f3f6f3]" aria-label="Tutup" @click="showCampaignModal = false"><X :size="18" /></button>
                    </div>
                    <form class="space-y-4" @submit.prevent="createDraft">
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-medium text-[#58665d]">Nama kampanye</span>
                            <input v-model.trim="campaignForm.name" required maxlength="80" class="h-10 w-full rounded-lg border border-[#e2e8e3] px-3 text-sm outline-none focus:border-[#16845b] focus:ring-2 focus:ring-[#16845b]/10" placeholder="Contoh: Promo pelanggan bulan ini">
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-medium text-[#58665d]">Pesan</span>
                            <textarea v-model.trim="campaignForm.message" required maxlength="1000" rows="4" class="w-full resize-y rounded-lg border border-[#e2e8e3] px-3 py-2.5 text-sm leading-relaxed outline-none focus:border-[#16845b] focus:ring-2 focus:ring-[#16845b]/10" placeholder="Tulis pesan yang relevan dan diharapkan penerima..." />
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-medium text-[#58665d]">Perkiraan jumlah penerima</span>
                            <input v-model.number="campaignForm.audience" required type="number" min="1" max="100000" class="h-10 w-full rounded-lg border border-[#e2e8e3] px-3 text-sm outline-none focus:border-[#16845b] focus:ring-2 focus:ring-[#16845b]/10" placeholder="0">
                        </label>
                        <label class="flex cursor-pointer items-start gap-2.5 rounded-lg bg-[#f4f8f4] p-3 text-[11px] leading-relaxed text-[#66766b]">
                            <input v-model="campaignForm.confirmedConsent" required type="checkbox" class="mt-0.5 accent-[#16845b]">
                            <span>Saya memastikan penerima telah menyetujui pesan dan permintaan opt-out akan dihormati.</span>
                        </label>
                        <div class="flex justify-end gap-2 border-t border-[#f0f2f0] pt-4">
                            <button type="button" class="h-9 rounded-lg border border-[#e2e8e3] px-3.5 text-xs font-medium text-[#6f7c73] hover:bg-[#f7f9f7]" @click="showCampaignModal = false">Batal</button>
                            <button type="submit" class="h-9 rounded-lg bg-[#16845b] px-4 text-xs font-semibold text-white hover:bg-[#116f4b]">Pratinjau draf</button>
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
