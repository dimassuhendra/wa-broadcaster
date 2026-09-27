<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowRight, LockKeyhole } from '@lucide/vue';

defineProps({
    title: {
        type: String,
        required: true,
    },
    message: {
        type: String,
        required: true,
    },
    actionLabel: {
        type: String,
        default: 'Hubungkan API',
    },
    actionHref: {
        type: String,
        default: '/settings',
    },
});
</script>

<template>
    <div class="mb-6 flex items-start gap-3 rounded-xl border border-[#f0e4c8] bg-[#fffbf2] px-4 py-3.5">
        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#f8edcf] text-[#a77a2d]">
            <LockKeyhole :size="15" />
        </span>
        <div class="flex-1">
            <p class="mb-0 text-xs font-semibold text-[#655333]">{{ title }}</p>
            <p class="mb-0 mt-1 text-[11px] leading-relaxed text-[#8e7c59]">{{ message }}</p>
        </div>
        <Link
            :href="actionHref"
            class="hidden items-center gap-1 self-center whitespace-nowrap text-[11px] font-semibold text-[#9a7330] sm:flex"
        >
            {{ actionLabel }} <ArrowRight :size="13" />
        </Link>
    </div>
</template>
