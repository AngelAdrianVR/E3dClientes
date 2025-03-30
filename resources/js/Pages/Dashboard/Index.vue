<template>
    <AppLayout title="Inicio">
        <main class="p-3 lg:py-10">
            <Loading v-if="loading" />
            
            <div v-else class="lg:max-w-7xl mx-auto sm:px-6 lg:px-8">
                <span class="text-base font-bold motion-preset-blur-left motion-delay-[200ms] bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">
                    Bienvenid@ {{ $page.props.auth.user.name }}
                </span>

                <section class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <!-- KPI Cotizaciones -->
                    <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
                        <div class="flex-1">
                            <h3 class="text-gray-600 text-sm font-semibold">Total Cotizaciones</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ quotes }}</p>
                        </div>
                        <div class="bg-blue-200 text-gray-500 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                    </div>

                    <!-- KPI Productos Pendientes -->
                    <div class="bg-white shadow-md rounded-lg p-6 flex items-center">
                        <div class="flex-1">
                            <h3 class="text-gray-600 text-sm font-semibold">Productos</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ totalProducts }}</p>
                        </div>
                        <div class="bg-red-200 text-gray-500 p-3 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                        </div>
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
import Loading from '@/Components/MyComponents/Loading.vue';
import VueApexCharts from "vue3-apexcharts";
import axios from 'axios';

export default {
    data() {
        return {
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
        AppLayout,
        Loading
    },
    props: {
    },
    mounted() {
        this.fetchTotalProducts();
        this.fetchQuotes();
    },
    methods: {
        async fetchTotalProducts() {
            try {
                this.loading = true;
                const response = await axios.get(route('catalog-product-company.fetch-all'));
                this.totalProducts = response.data.items;
            } catch (error) {
                console.error("Error al obtener productos pendientes", error);
            } finally {
                // this.loading = false;
            }
        },
        async fetchQuotes() {
            try {
                // this.loading = true;
                const response = await axios.get(route('quotes.fetch-all'));
                this.quotes = response.data.items;
            } catch (error) {
                console.error("Error al obtener productos pendientes", error);
            } finally {
                this.loading = false;
            }
        }
    }
};
</script>
