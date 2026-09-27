<script setup>
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppHeader from '../Components/AppHeader.vue';
import AppSidebar from '../Components/AppSidebar.vue';

const page = usePage();
const mobileMenuOpen = ref(false);
const section = computed(() => page.props.section ?? 'dashboard');

function closeMobileMenu() {
    mobileMenuOpen.value = false;
}
</script>

<template>
    <div class="min-h-screen bg-[#f7f8f6] text-[#202923]">
        <div
            v-if="mobileMenuOpen"
            class="fixed inset-0 z-40 bg-[#18231d]/35 lg:hidden"
            @click="closeMobileMenu"
        />

        <AppSidebar
            :section="section"
            :mobile-open="mobileMenuOpen"
            @close="closeMobileMenu"
        />

        <div class="min-h-screen lg:pl-[258px]">
            <AppHeader :section="section" @toggle-sidebar="mobileMenuOpen = !mobileMenuOpen" />

            <main class="mx-auto max-w-[1440px] px-5 pb-10 pt-7 sm:px-8 sm:pt-9">
                <slot />

                <footer class="mt-9 flex flex-wrap items-center justify-between gap-2 border-t border-[#e9ede9] pt-5 text-[10px] text-[#a0aaa3]">
                    <span>© {{ new Date().getFullYear() }} SapaFlow. Dibuat untuk komunikasi yang lebih bermakna.</span>
                    <a
                        href="https://www.whatsapp.com/legal/business-policy/"
                        target="_blank"
                        rel="noreferrer"
                        class="inline-flex items-center gap-1 font-medium text-[#819087] hover:text-[#16845b]"
                    >
                        Kebijakan WhatsApp Business
                    </a>
                </footer>
            </main>
        </div>
    </div>
</template>
