<template>
    <div
        id="centered"
        data-carousel='{ "loadingClasses": "opacity-0", "isCentered": false, "isInfiniteLoop": true, "isAutoPlay": true, "slidesQty": { "xs": 1, "lg": 4, "xl": 5 } }'
        class="relative w-full"
    >
        <div class="carousel h-80">
            <div class="carousel-body h-full opacity-0">
                <!-- Slide 1 -->
                <div v-for="item in data" :key="item.id" class="carousel-slide px-1 w-80">
                    <SuggestedProductCard :catalogProduct="item" :suggestedProducts="data" />
                </div>
            </div>
        </div>

        <!-- Previous Slide -->
        <button type="button" class="carousel-prev">
            <span class="size-9.5 bg-base-100 flex items-center justify-center rounded-full shadow">
            <span class="icon-[tabler--chevron-left] size-5 cursor-pointer rtl:rotate-180"></span>
            </span>
            <span class="sr-only">Previous</span>
        </button>
        <!-- Next Slide -->
        <button type="button" class="carousel-next">
            <span class="sr-only">Next</span>
            <span class="size-9.5 bg-base-100 flex items-center justify-center rounded-full shadow">
            <span class="icon-[tabler--chevron-right] size-5 cursor-pointer rtl:rotate-180"></span>
            </span>
        </button>
        </div>
</template>

<script>
import SuggestedProductCard from '@/Components/MyComponents/SuggestedProductCard.vue';
import { nextTick } from 'vue';

export default {
data() {
    return {

    }
},
components:{
SuggestedProductCard
},
props:{
    data: Array
},
methods: {
    initCarousel() {
        const carousel = document.querySelector('[data-carousel]');
        if (carousel) {
            carousel.removeAttribute('data-carousel-initialized');
            setTimeout(() => {
                carousel.setAttribute('data-carousel', JSON.stringify({
                    loadingClasses: "opacity-0",
                    isCentered: false,
                    isInfiniteLoop: true,
                    isAutoPlay: true,
                    slidesQty: { xs: 1, lg: 4, xl: 5 }
                }));
            }, 100);
        }
    }
},
watch: {
    data: {
        handler() {
            nextTick(() => {
                this.initCarousel();
            });
        },
        deep: true,
        immediate: true
    }
},
}
</script>