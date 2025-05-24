<template>
    <AppLayout title="Inicio">
        <main class="p-3 lg:py-10">
            <Loading v-if="loading" />

            <div v-else class="lg:max-w-7xl mx-auto sm:px-6 lg:px-2">
                <span
                    class="text-lg font-bold motion-preset-blur-left motion-delay-[100ms] bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">
                    Bienvenid@ {{ $page.props.auth.user.name }}
                </span>
                <section class="lg:grid grid-cols-2 gap-3">
                    <article class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-5 self-start">
                        <!-- KPI Cotizaciones -->
                        <div
                            class="motion-preset-blur-right motion-delay-[300ms] border border-[#D9D9D9] dark:border-[#605D5D] rounded-2xl py-6 flex items-center shadow-md relative">
                            <img draggable="false" class="absolute -left-1 h-28 dark:hidden"
                                src="@/../../public/images/quoteCardDecoration.webp" alt="Logo">
                            <img draggable="false" class="absolute -left-1 h-28 dark:block hidden"
                                src="@/../../public/images/quoteCardDecoration_dark.webp" alt="Logo">
                            <div class="flex-1 pl-24 *:dark:text-white">
                                <h3 class="text-gray-600 text-sm">Cotizaciones totales</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ quotes }}</p>
                            </div>
                            <button @click="$inertia.get(route('quotes.index'))"
                                class="bg-[#E8E8E8] dark:bg-[#383838] rounded-full py-1 px-3 text-[#575757] dark:text-[#C7C7C7] text-xs absolute right-4 bottom-2">
                                Ver más
                            </button>
                        </div>
                        <!-- KPI Productos Pendientes -->
                        <div
                            class="motion-preset-blur-left motion-delay-[300ms] border border-[#D9D9D9] dark:border-[#605D5D] rounded-2xl py-6 flex items-center shadow-md relative">
                            <img draggable="false" class="absolute -left-1 h-28 dark:hidden"
                                src="@/../../public/images/productCardDecoration.webp" alt="Logo">
                            <img draggable="false" class="absolute -left-1 h-28 dark:block hidden"
                                src="@/../../public/images/productCardDecoration_dark.webp" alt="Logo">
                            <div class="flex-1 pl-24 *:dark:text-white">
                                <h3 class="text-gray-600 text-sm">Mis productos</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ totalProducts }}</p>
                            </div>
                            <button @click="$inertia.get(route('catalog-product-company.index'))"
                                class="bg-[#E8E8E8] dark:bg-[#383838] rounded-full py-1 px-3 text-[#575757] dark:text-[#C7C7C7] text-xs absolute right-4 bottom-2">
                                Ver más
                            </button>
                        </div>
                        <div
                            class="col-span-full border border-[#D9D9D9] dark:border-[#605D5D] rounded-2xl shadow-md motion-scale-in-0 motion-opacity-in-0 motion-blur-in-[5px] motion-ease-spring-spring motion-delay-[400ms]">
                            <h1
                                class="p-5 border-b border-[#D9D9D9] text-black dark:text-white flex items-center space-x-4">
                                <Gear />
                                <span class="font-bold">Órdenes en proceso de producción</span>
                            </h1>
                            <div class="py-5 px-4 space-y-4">
                                <!-- progreso de ordenes de producción -->
                                <div v-for="(sale, index) in sales_in_progress" :key="index">
                                    <div
                                        class="flex items-center justify-between bg-[#E6EBFD] dark:bg-[#2C2C2C] rounded-full pr-1 pl-2">
                                        <p class="text-[#3C3C3C] dark:text-[#D2D2D2] font-bold">{{
                                            'COT-' + String(sale.quote_id).padStart(4, '0') }}</p>
                                        <Link v-if="sale.quote_id" :href="route('quotes.show', sale.quote_id )"
                                            class="my-1 flex items-center justify-center size-6 bg-white text-[#1C1B1F] dark:bg-[#151515] dark:text-white rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25" />
                                        </svg>
                                        </Link>
                                    </div>
                                    <p class="text-end text-[#616161] dark:text-[#B4B2B2] mt-1 italic capitalize">
                                        {{ formatDate(sale.authorized_at) }}</p>
                                    <p class="text-[#414141] dark:text-[#A8A8A8] font-bold">Progreso</p>
                                    <div class="w-full bg-[#DFDFDF] rounded-full h-[7px] overflow-hidden mt-1">
                                        <div class="h-full rounded-full bg-gradient-to-r from-[#0355B5] to-[#01254F] dark:from-[#005D4B] dark:to-[#02BBA6]"
                                            :style="{ width: sale.progress + '%' }"></div>
                                    </div>
                                    <p class="text-end font-bold dark:text-white mt-1">{{ sale.progress }}%</p>
                                </div>
                                <p v-if="!sales_in_progress.length" class="text-center text-sm dark:text-white mt-1">
                                    No hay ninguna orden en proceso de producción.
                                </p>
                            </div>
                        </div>
                    </article>
                </section>
                <section v-if="suggestedProducts"
                    class="motion-scale-in-0 motion-opacity-in-0 motion-blur-in-[5px] motion-ease-spring-spring motion-delay-[600ms]">
                    <div class="mt-5 border border-[#D9D9D9] dark:border-[#605D5D] rounded-2xl px-8 py-5">
                        <h1 class="mb-4 text-black dark:text-white flex items-center space-x-4">
                            <svg width="39" height="29" viewBox="0 0 39 29" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M28 9C31.5147 5.09476 27.9853 8.90524 31.5 5M11 8C7.68054 3.70423 11.3195 8.29577 8 4M34 16.5H38M5 16.5H1M20 6V1M19.4802 10.4992C19.5225 10.396 19.5945 10.3078 19.6871 10.2457C19.7797 10.1836 19.8887 10.1504 20.0002 10.1504C20.1117 10.1504 20.2206 10.1836 20.3132 10.2457C20.4058 10.3078 20.4779 10.396 20.5202 10.4992L22.6452 15.6102C22.6849 15.7058 22.7503 15.7887 22.8341 15.8495C22.9179 15.9104 23.0169 15.947 23.1202 15.9552L28.6382 16.3972C29.1372 16.4372 29.3392 17.0602 28.9592 17.3852L24.7552 20.9872C24.6766 21.0544 24.6181 21.1419 24.586 21.2402C24.5539 21.3384 24.5494 21.4436 24.5732 21.5442L25.8582 26.9292C25.884 27.0373 25.8772 27.1505 25.8387 27.2547C25.8002 27.3589 25.7317 27.4494 25.6418 27.5146C25.5519 27.5799 25.4447 27.6171 25.3337 27.6214C25.2227 27.6258 25.1129 27.5972 25.0182 27.5392L20.2932 24.6542C20.2049 24.6003 20.1036 24.5718 20.0002 24.5718C19.8968 24.5718 19.7954 24.6003 19.7072 24.6542L14.9822 27.5402C14.8874 27.5982 14.7776 27.6268 14.6666 27.6224C14.5556 27.6181 14.4484 27.5809 14.3585 27.5156C14.2686 27.4504 14.2001 27.3599 14.1616 27.2557C14.1231 27.1515 14.1163 27.0383 14.1422 26.9302L15.4272 21.5442C15.451 21.4436 15.4466 21.3384 15.4145 21.2401C15.3824 21.1418 15.3238 21.0543 15.2452 20.9872L11.0412 17.3852C10.9565 17.313 10.8952 17.2174 10.8649 17.1103C10.8347 17.0033 10.8369 16.8897 10.8713 16.7839C10.9056 16.6781 10.9706 16.5849 11.058 16.516C11.1454 16.4472 11.2513 16.4058 11.3622 16.3972L16.8802 15.9552C16.9834 15.947 17.0824 15.9104 17.1662 15.8495C17.25 15.7887 17.3154 15.7058 17.3552 15.6102L19.4802 10.4992Z"
                                    stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="font-bold">Productos recomendados ({{ suggestedProducts.length }})</span>
                        </h1>
                        <el-carousel :interval="5000" arrow="always" indicator-position="none" loop>
                            <el-carousel-item v-for="(group, index) in groupedData" :key="index">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                    <SuggestedProductCard v-for="item in group" :key="item.id" :catalogProduct="item"
                                        :suggestedProducts="suggestedProducts" />
                                </div>
                            </el-carousel-item>
                        </el-carousel>
                    </div>
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
import { format } from 'date-fns';
import { es } from 'date-fns/locale';
import { Link } from '@inertiajs/vue3';

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
        Gear,
        Link,
    },
    props: {
        sales_in_progress: {
            type: Array,
            default: () => []
        },
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
        formatDate(date) {
            return date ? format(new Date(date), 'MMMM yyyy', { locale: es }) : null;
        },
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
                if (response.status === 200) {
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
    height: 320px;
    /* Ajusta la altura según sea necesario */
}
</style>
