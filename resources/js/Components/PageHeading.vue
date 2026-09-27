<script setup>
defineProps({
    eyebrow: {
        type: String,
        default: '',
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p v-if="eyebrow" class="mb-2 text-[11px] font-semibold uppercase tracking-[1.1px] text-[#829087]">{{ eyebrow }}</p>
            <h2 class="m-0 text-[27px] font-semibold tracking-[-1px] text-[#25332a]">{{ title }}</h2>
            <p v-if="description" class="mb-0 mt-2 text-[13px] text-[#849087]">{{ description }}</p>
        </div>
        <div v-if="$slots.actions" class="self-start sm:self-auto">
            <slot name="actions" />
        </div>
    </div>
</template>
