<template>
    <AppLayout :title="'Mis Productos'">
        <main class="p-3 lg:p-7">
            <!-- carousel de productos recomendados -->
            <Loading v-if="loadingSuggestedProducts" />
            <section v-else-if="suggestedProducts?.length" class="py-5 mx-3">
                <h1
                    class="font-bold md:text-2xl motion-preset-blur-left motion-delay-[100ms] bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">
                    RECOMENDACIONES</h1>
                <span class="motion-preset-focus motion-delay-[700ms]">Productos que podrían interesarte ({{
                    suggestedProducts?.length }})</span>
                <div class="py-5 motion-scale-in-0 motion-opacity-in-0 motion-blur-in-[5px] motion-ease-spring-spring motion-delay-[1000ms]">
                    <el-carousel :interval="5000" arrow="always" indicator-position="none" loop>
                        <el-carousel-item v-for="(group, index) in groupedData" :key="index">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <SuggestedProductCard v-for="item in group" :key="item.id" :catalogProduct="item"
                                    :suggestedProducts="suggestedProducts" />
                            </div>
                        </el-carousel-item>
                    </el-carousel>
                </div>
            </section>
            <h1
                class="mt-5 font-bold md:text-2xl motion-preset-blur-left motion-delay-[600ms] bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">
                Mis productos</h1>
            <span class="motion-preset-focus motion-delay-[1200ms]">Productos que adquieres con nosotros ({{
                catalog_products_company.length }})</span>
            <section v-if="catalog_products_company.length"
                class="my-5 mx-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                <CatalogProductCard
                    class="motion-scale-in-0 motion-opacity-in-0 motion-blur-in-[5px] motion-ease-spring-spring"
                    v-for="item in catalog_products_company" :key="item.id" :catalogProductCompany="item" />
            </section>
            <p class="text-gray-500 text-center" v-else>No tienes productos registrados</p>

        </main>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Loading from '@/Components/MyComponents/Loading.vue';
import CatalogProductCard from '@/Components/MyComponents/CatalogProductCard.vue';
import SuggestedProductCard from '@/Components/MyComponents/SuggestedProductCard.vue';
import axios from 'axios';

export default {
    data() {
        return {
            suggestedProducts: null, //informacion de productos sugeridos
            loadingSuggestedProducts: false
        }
    },
    computed: {
        groupedData() {
            const itemsPerSlide = window.innerWidth >= 1024 ? 4 : 1; // 4 en escritorio, 1 en móvil
            const groups = [];
            for (let i = 0; i < this.suggestedProducts?.length; i += itemsPerSlide) {
                groups.push(this.suggestedProducts?.slice(i, i + itemsPerSlide));
            }
            return groups;
        }
    },
    components: {
        Loading,
        AppLayout,
        CatalogProductCard,
        SuggestedProductCard,
    },
    props: {
        catalog_products_company: Array,
    },
    methods: {
        async fetchSuggestedProducts() {
            this.loadingSuggestedProducts = true;
            try {
                const response = await axios.get(route('catalog-product-company.get-suggested'));
                if (response.status === 200) {
                    this.suggestedProducts = response.data.suggested_products;
                }
            } catch (error) {
                console.log(error)
            } finally {
                this.loadingSuggestedProducts = false;
            }
        }
    },
    mounted() {
        this.fetchSuggestedProducts();
    }
}
</script>

<style scoped>
.el-carousel__item {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 320px;
    /* Ajusta la altura según sea necesario */
}
</style>
