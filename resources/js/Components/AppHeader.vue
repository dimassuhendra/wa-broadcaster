<script setup>
import { computed } from 'vue';
import {
    Bell,
    ChevronDown,
    Menu,
    Search,
} from '@lucide/vue';

const props = defineProps({
    section: {
        type: String,
        default: 'dashboard',
    },
});

defineEmits(['toggle-sidebar']);

const pageTitles = {
    dashboard: 'Ringkasan',
    campaigns: 'Kampanye',
    contacts: 'Kontak',
    templates: 'Template pesan',
    analytics: 'Analitik',
    settings: 'Pengaturan',
};

const currentTitle = computed(() => pageTitles[props.section] ?? 'Ringkasan');
</script>

<template>
    <header class="sticky top-0 z-30 flex h-[76px] items-center justify-between border-b border-[#e9ede9] bg-white/95 px-5 backdrop-blur sm:px-8">
        <div class="flex items-center gap-3">
            <button
                class="rounded-lg p-2 text-[#718078] hover:bg-[#f3f6f3] lg:hidden"
                aria-label="Buka navigasi"
                @click="$emit('toggle-sidebar')"
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
</template>
