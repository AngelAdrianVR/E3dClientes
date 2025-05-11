<template>
    <AppLayout title="Inicio">
        <main class="p-3 lg:py-10">
            <Loading v-if="loading" />
            
            <div v-else class="lg:max-w-7xl mx-auto sm:px-6 lg:px-2">
                <span class="text-lg font-bold motion-preset-blur-left motion-delay-[200ms] bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">
                    Bienvenid@ {{ $page.props.auth.user.name }}
                </span>

                <section class="lg:grid grid-cols-2 gap-3">
                    <article class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-5 self-start">
                        <!-- KPI Cotizaciones -->
                        <div class="border border-[#D9D9D9] dark:border-[#605D5D] rounded-2xl py-6 flex items-center shadow-md relative">
                            <img draggable="false" class="absolute -left-1 h-28 dark:hidden" src="@/../../public/images/quoteCardDecoration.webp" alt="Logo">
                            <img draggable="false" class="absolute -left-1 h-28 dark:block hidden" src="@/../../public/images/quoteCardDecoration_dark.webp" alt="Logo">
                            <div class="flex-1 pl-24 *:dark:text-white">
                                <h3 class="text-gray-600 text-sm">Cotizaciones totales</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ quotes }}</p>
                            </div>
                        </div>

                        <!-- KPI Productos Pendientes -->
                        <div class="border border-[#D9D9D9] dark:border-[#605D5D] rounded-2xl py-6 flex items-center shadow-md relative">
                            <img draggable="false" class="absolute -left-1 h-28 dark:hidden" src="@/../../public/images/productCardDecoration.webp" alt="Logo">
                            <img draggable="false" class="absolute -left-1 h-28 dark:block hidden" src="@/../../public/images/productCardDecoration_dark.webp" alt="Logo">
                            <div class="flex-1 pl-24 *:dark:text-white">
                                <h3 class="text-gray-600 text-sm">Mis productos</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ totalProducts }}</p>
                            </div>
                            <button @click="$inertia.get(route('catalog-product-company.index'))" class="bg-[#E8E8E8] dark:bg-[#383838] rounded-full py-1 px-3 text-[#575757] dark:text-[#C7C7C7] text-xs absolute right-4 bottom-2">
                                Descubrir más
                            </button>
                        </div>

                        <div class="col-span-full border border-[#D9D9D9] dark:border-[#605D5D] rounded-2xl shadow-md">
                            <h1 class="p-5 border-b border-[#D9D9D9] text-black dark:text-white flex items-center space-x-4">
                                <Gear />
                                <span>Ordenes en proceso de producción</span>
                            </h1>

                            <div class="py-5 px-4">

                            </div>

                        </div>
                    </article>

                    <article v-if="suggestedProducts">
                        <div class="mx-auto w-[90%] py-5">
                            <el-carousel
                                :interval="4000"
                                arrow="always"
                                indicator-position="outside"
                                loop
                            >
                                <el-carousel-item v-for="(group, index) in groupedData" :key="index">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <SuggestedProductCard v-for="item in group" :key="item.id" :catalogProduct="item" :suggestedProducts="suggestedProducts" />
                                    </div>
                                </el-carousel-item>
                            </el-carousel>
                        </div>
                    </article>
                </section>

                

                <!-- <section class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-gray-600 text-sm font-semibold">Ventas Últimos 6 Meses</h3>
                        <apexchart type="area" height="300" :options="areaChartOptions" :series="areaChartData"></apexchart>
                    </div>
                    
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-gray-600 text-sm font-semibold">Distribución de Productos</h3>
                        <apexchart type="donut" height="300" :options="pieChartOptions" :series="pieChartData"></apexchart>
                    </div>
                </section> -->
            </div>
        </main>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Gear from '@/Components/MyComponents/Icons/Gear.vue';
import Loading from '@/Components/MyComponents/Loading.vue';
import VueApexCharts from "vue3-apexcharts";
import SuggestedProductCard from '@/Components/MyComponents/SuggestedProductCard.vue';
import axios from 'axios';

export default {
    data() {
        return {
            suggestedProducts: null, //informacion de productos sugeridos
            loading: false,
            totalProducts: 0,
            quotes: 0,

            areaChartOptions: {
                chart: {
                    type: 'area'
                },
                xaxis: {
                    categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun']
                },
                colors: ['#3b82f6']
            },
            areaChartData: [{
                name: 'Ventas',
                data: [150, 200, 180, 220, 170, 210]
            }],
            pieChartOptions: {
                labels: ['Electrónica', 'Ropa', 'Hogar', 'Otros']
            },
            pieChartData: [30, 25, 20, 25]
        };
    },
    components: {
        apexchart: VueApexCharts,
        SuggestedProductCard,
        AppLayout,
        Loading,
        Gear
    },
    props: {
        
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
    mounted() {
        this.fetchSuggestedProducts();
        this.fetchTotalProducts();
        this.fetchQuotes();
    },
    methods: {
        async fetchTotalProducts() {
            try {
                const response = await axios.get(route('catalog-product-company.fetch-all'));
                this.totalProducts = response.data.items;
            } catch (error) {
                console.error("Error al obtener productos pendientes", error);
            }
        },
        async fetchQuotes() {
            try {
                const response = await axios.get(route('quotes.fetch-all'));
                this.quotes = response.data.items;
            } catch (error) {
                console.error("Error al obtener productos pendientes", error);
            } finally {
                this.loading = false;
            }
        },
        async fetchSuggestedProducts() {
            this.loading = true;
            try {
                const response = await axios.get(route('catalog-product-company.get-suggested'));
                if ( response.status === 200 ) {
                    this.suggestedProducts = response.data.suggested_products;
                }
            } catch (error) {
                console.log(error)
            }
        }
    }
};
</script>

<style scoped>
.el-carousel__item {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 320px; /* Ajusta la altura según sea necesario */
}
</style>
