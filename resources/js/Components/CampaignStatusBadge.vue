<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
});

const statusDetails = computed(() => ({
    draft: { label: 'Draf', classes: 'bg-[#f2f3f2] text-[#818b84]', dot: 'bg-[#9ca59e]' },
    scheduled: { label: 'Terjadwal', classes: 'bg-[#edf3fa] text-[#5a7ea6]', dot: 'bg-[#7499c1]' },
    sending: { label: 'Mengirim', classes: 'bg-[#fff7e9] text-[#a37a36]', dot: 'bg-[#d8a84d]' },
    sent: { label: 'Selesai', classes: 'bg-[#edf6ef] text-[#46815a]', dot: 'bg-[#64a477]' },
    completed: { label: 'Selesai', classes: 'bg-[#edf6ef] text-[#46815a]', dot: 'bg-[#64a477]' },
    failed: { label: 'Gagal', classes: 'bg-[#fdf0ed] text-[#b46b5d]', dot: 'bg-[#d17b67]' },
})[props.status] ?? {
    label: props.status,
    classes: 'bg-[#f2f3f2] text-[#818b84]',
    dot: 'bg-[#9ca59e]',
});
</script>

<template>
    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-medium" :class="statusDetails.classes">
        <span class="h-1.5 w-1.5 rounded-full" :class="statusDetails.dot" />
        {{ statusDetails.label }}
    </span>
</template>
