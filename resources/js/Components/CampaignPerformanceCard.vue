<script setup>
import { computed } from 'vue';
import { ArrowRight, MoreHorizontal } from '@lucide/vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    performance: {
        type: Object,
        required: true,
    },
});

const metrics = computed(() => [
    {
        label: 'Terkirim',
        count: props.performance.sent,
        percent: props.performance.sent > 0 ? 100 : 0,
        barClass: 'bg-[#16845b]',
    },
    {
        label: 'Dibaca',
        count: props.performance.read,
        percent: props.performance.sent > 0
            ? Math.round(props.performance.read / props.performance.sent * 100)
            : 0,
        barClass: 'bg-[#81b99a]',
    },
    {
        label: 'Direspons',
        count: props.performance.replied,
        percent: props.performance.sent > 0
            ? Math.round(props.performance.replied / props.performance.sent * 100)
            : 0,
        barClass: 'bg-[#bed9c7]',
    },
]);

const ringStyle = computed(() => ({
    background: `conic-gradient(#16845b 0deg ${props.performance.readRate * 3.6}deg, #dcece2 ${props.performance.readRate * 3.6}deg 360deg)`,
}));
</script>

<template>
    <section class="rounded-2xl border border-[#e9ede9] bg-white p-5 sm:p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Performa kampanye</h3>
                <p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">Ringkasan bulan ini</p>
            </div>
            <button class="rounded-lg p-1.5 text-[#96a199] hover:bg-[#f5f7f5]" aria-label="Menu performa">
                <MoreHorizontal :size="17" />
            </button>
        </div>
        <div class="mt-7 flex items-center gap-5">
            <div
                class="relative flex h-[118px] w-[118px] shrink-0 items-center justify-center rounded-full"
                :style="ringStyle"
            >
                <div class="flex h-[88px] w-[88px] flex-col items-center justify-center rounded-full bg-white">
                    <span class="text-[21px] font-semibold tracking-[-1px] text-[#2b392f]">{{ performance.readRate }}%</span>
                    <span class="mt-0.5 text-[9px] text-[#9ba59e]">dibaca</span>
                </div>
            </div>
            <div class="min-w-0 flex-1 space-y-4">
                <div v-for="metric in metrics" :key="metric.label">
                    <div class="mb-1.5 flex justify-between gap-2 text-[10px]">
                        <span class="text-[#7b8880]">{{ metric.label }}</span>
                        <span class="font-semibold text-[#38473d]">{{ metric.count.toLocaleString('id-ID') }}</span>
                    </div>
                    <div class="h-1.5 overflow-hidden rounded-full bg-[#eef2ee]">
                        <div class="h-full rounded-full" :class="metric.barClass" :style="{ width: `${metric.percent}%` }" />
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-between border-t border-[#f0f2f0] pt-4">
            <span class="text-[10px] text-[#99a39c]">{{ performance.responseRate }}% tingkat respons</span>
            <Link href="/analytics" class="inline-flex items-center gap-1 text-[10px] font-semibold text-[#16845b]">
                Lihat analitik <ArrowRight :size="13" />
            </Link>
        </div>
    </section>
</template>
