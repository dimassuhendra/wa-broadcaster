<script setup>
import { computed } from 'vue';
import { ArrowDownRight, ArrowUpRight } from '@lucide/vue';

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    value: {
        type: Number,
        required: true,
    },
    icon: {
        type: Object,
        required: true,
    },
    iconClass: {
        type: String,
        default: 'bg-[#eaf4ee] text-[#16845b]',
    },
    change: {
        type: Number,
        default: null,
    },
    changeUnit: {
        type: String,
        default: '%',
    },
    changeLabel: {
        type: String,
        default: 'dibanding bulan lalu',
    },
    suffix: {
        type: String,
        default: '',
    },
    positiveIsGood: {
        type: Boolean,
        default: true,
    },
});

const formattedValue = computed(() => {
    const value = new Intl.NumberFormat('id-ID', {
        maximumFractionDigits: 1,
    }).format(props.value);

    return `${value}${props.suffix}`;
});

const formattedChange = computed(() => {
    if (props.change === null) {
        return null;
    }

    const value = new Intl.NumberFormat('id-ID', {
        maximumFractionDigits: 1,
        signDisplay: 'always',
    }).format(props.change);

    return props.changeUnit === 'pp' ? `${value} pp` : `${value}%`;
});

const changeIsPositive = computed(() => (
    props.positiveIsGood ? props.change >= 0 : props.change < 0
));
</script>

<template>
    <article class="rounded-2xl border border-[#e9ede9] bg-white p-5">
        <div class="flex items-center justify-between">
            <span class="text-xs font-medium text-[#7d8981]">{{ label }}</span>
            <span class="flex h-8 w-8 items-center justify-center rounded-[10px]" :class="iconClass">
                <component :is="icon" :size="16" />
            </span>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <span class="text-[26px] font-semibold leading-none tracking-[-1px] text-[#27352c]">{{ formattedValue }}</span>
            <span
                v-if="formattedChange !== null"
                class="inline-flex items-center gap-0.5 text-[10px] font-semibold"
                :class="changeIsPositive ? 'text-[#4c9a70]' : 'text-[#d17b67]'"
            >
                <component :is="changeIsPositive ? ArrowUpRight : ArrowDownRight" :size="13" />
                {{ formattedChange }}
            </span>
        </div>
        <p class="mb-0 mt-2 text-[10px] text-[#a0aaa3]">{{ changeLabel }}</p>
    </article>
</template>
