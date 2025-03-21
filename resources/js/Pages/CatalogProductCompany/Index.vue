<template>
    <AppLayout :title="'Mis Productos'">
        <main class="p-3 md:p-10">
            <h1 class="font-bold md:text-2xl motion-preset-blur-left motion-delay-[600ms] bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">Mis productos</h1>
            <span class="motion-preset-focus motion-delay-[1200ms]">Productos que adquieres con nosotros</span>

            <section class="my-5 mx-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                <CatalogProductCard class="motion-scale-in-0 motion-opacity-in-0' motion-blur-in-[5px] motion-ease-spring-spring" v-for="item in catalog_products_company" :key="item.id" :catalogProductCompany="item" />
            </section>

            <!-- carousel de productos recomendados -->
            <section class="mt-24 mx-3">
                <h1 class="font-bold md:text-2xl motion-preset-blur-left motion-delay-[600ms] bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">RECOMENDACIONES</h1>
                <span class="motion-preset-focus motion-delay-[1200ms]">Productos que podrían interesarte</span>
                <Carousel :data="catalog_products_company" class="mt-5" />
            </section>
        </main>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import CatalogProductCard from '@/Components/MyComponents/CatalogProductCard.vue';
import Carousel from '@/Components/MyComponents/FlyonUI/Carousel.vue';

export default {
data() {
    return {

    }
},
components:{
Carousel,
AppLayout,
CatalogProductCard,
},
props:{
catalog_products_company: Array,
},
methods:{

}
}
</script>
