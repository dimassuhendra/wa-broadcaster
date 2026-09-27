<script setup>
import { ArrowUpRight, ChevronDown } from '@lucide/vue';

defineProps({
    activity: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <section class="rounded-2xl border border-[#e9ede9] bg-white p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h3 class="m-0 text-sm font-semibold text-[#2e3a32]">Aktivitas pesan</h3>
                <p class="mb-0 mt-1 text-[11px] text-[#9aa49d]">Ringkasan pesan selama 7 hari terakhir</p>
            </div>
            <span class="inline-flex items-center gap-1 rounded-lg border border-[#e9ede9] px-2.5 py-1.5 text-[10px] font-medium text-[#728077]">
                7 hari terakhir <ChevronDown :size="13" />
            </span>
        </div>
        <div class="mt-6 flex items-end gap-3">
            <span class="text-[27px] font-semibold tracking-[-1px] text-[#29372e]">{{ activity.total.toLocaleString('id-ID') }}</span>
            <span class="mb-1 text-[10px] font-medium text-[#8d9990]">pesan terkirim</span>
            <span class="mb-1 ml-auto text-[10px] font-medium text-[#8d9990]">7 hari terakhir</span>
        </div>
        <div class="mt-5 grid h-[138px] grid-cols-7 items-end gap-3 border-b border-[#edf0ed] pb-0 sm:gap-5">
            <div v-for="day in activity.days" :key="day.label" class="flex h-full flex-col items-center justify-end gap-2">
                <div class="flex h-full w-full items-end justify-center">
                    <span
                        class="w-full max-w-[33px] rounded-t-md transition-all"
                        :class="day.isToday ? 'bg-[#16845b]' : 'bg-[#dcece2]'"
                        :style="{ height: `${day.height}%` }"
                        :title="`${day.label}: ${day.total} pesan`"
                    />
                </div>
                <span class="mb-2 text-[10px] text-[#9ba59e]">{{ day.label }}</span>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-4 text-[10px] text-[#89958c]">
            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-sm bg-[#16845b]" /> Hari ini</span>
            <span class="inline-flex items-center gap-1.5"><span class="h-2 w-2 rounded-sm bg-[#dcece2]" /> Hari lainnya</span>
        </div>
    </section>
</template>
