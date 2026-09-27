<script setup>
import { FileText, Megaphone, MoreHorizontal, Send } from '@lucide/vue';
import { Link } from '@inertiajs/vue3';
import CampaignStatusBadge from './CampaignStatusBadge.vue';

defineProps({
    campaigns: {
        type: Array,
        default: () => [],
    },
});

const campaignIcons = {
    draft: FileText,
    scheduled: Megaphone,
    sending: Send,
    sent: Send,
    completed: Send,
    failed: FileText,
};
</script>

<template>
    <section class="overflow-hidden rounded-2xl border border-[#e9ede9] bg-white">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-5 sm:px-6">
            <div>
                <h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Kampanye terbaru</h3>
                <p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">Lihat aktivitas kampanye pesan kamu</p>
            </div>
            <Link href="/campaigns" class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#16845b]">Semua kampanye <span aria-hidden="true">→</span></Link>
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
                    <tr v-for="campaign in campaigns" :key="campaign.id" class="border-b border-[#f2f4f2] last:border-0">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#f1f5f1] text-[#6d8373]">
                                    <component :is="campaignIcons[campaign.status] ?? FileText" :size="15" />
                                </span>
                                <span>
                                    <span class="block text-xs font-semibold text-[#3c4940]">{{ campaign.name }}</span>
                                    <span class="mt-1 block text-[10px] text-[#9aa49d]">Kampanye WhatsApp</span>
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5"><CampaignStatusBadge :status="campaign.status" /></td>
                        <td class="px-4 py-3.5 text-[10px] text-[#89958c]">{{ campaign.time }}</td>
                        <td class="px-4 py-3.5 text-[10px] font-medium text-[#65736a]">{{ campaign.recipientCount.toLocaleString('id-ID') }}</td>
                        <td class="px-4 py-3.5">
                            <button class="rounded-md p-1 text-[#a0aaa3] hover:bg-[#f4f6f4]" aria-label="Opsi kampanye">
                                <MoreHorizontal :size="16" />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="campaigns.length === 0">
                        <td colspan="5" class="px-6 py-10 text-center text-xs text-[#89958c]">Belum ada kampanye.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="mb-0 border-t border-[#f0f2f0] px-6 py-3 text-[10px] text-[#a0aaa3]">Data kampanye dimuat dari database. Tidak ada pesan yang dikirim.</p>
    </section>
</template>
