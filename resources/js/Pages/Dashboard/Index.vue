<template>
    <AppLayout title="Inicio">
        <div class="py-12">
            <div class="lg:max-w-7xl mx-auto sm:px-6 lg:px-8">
                <span class="text-sm font-bold">
                    Bienvenid@
                    {{ $page.props.auth.user.name }}
                </span>
                
                <!-- <div class="mt-7 w-1/3 relative">
                    <input v-model="quoteSearch" class="input pr-9" type="text" placeholder="Buscar cotización">
                    <i class="fa-solid fa-magnifying-glass text-[#9A9A9A] text-xs absolute right-4 top-2"></i>
                </div> -->

                <!-- <el-tabs v-model="activeTab" class="mt-10">
                    <el-tab-pane name="1">
                        <template #label>
                            <div class="flex items-center">
                                <i class="fa-regular fa-square-check mr-1"></i>
                                <span>Cotizaciones ({{ quotes?.length }})</span>
                            </div>
                        </template>
                        <div v-if="loading" class="flex justify-center items-center py-10">
                            <i class="fa-solid fa-spinner fa-spin text-4xl text-primary"></i>
                        </div>
                        <div v-else>
                            <p v-if="quotes?.length" class="text-gray66 text-[11px]">{{ quotes?.length }} de {{
                                totalQuotes }} elementos
                            </p>
                            <Quotes :quotes="quotes" />
                            <p v-if="quotes?.length" class="text-gray66 text-[11px]">{{ quotes?.length }} de {{
                                totalQuotes }} elementos
                            </p>
                            <p v-if="loadingItems" class="text-xs my-4 text-center">
                                Cargando <i class="fa-sharp fa-solid fa-circle-notch fa-spin ml-2 text-primary"></i>
                            </p>
                            <button v-else-if="totalQuotes > 30 && quotes?.length < totalQuotes && quotes?.length"
                                @click="fetchQuotesByPage" class="w-full text-primary my-4 text-xs mx-auto underline ml-6">Cargar más
                                elementos</button>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane name="2">
                        <template #label>
                            <div class="flex items-center">
                                <i class="fa-solid fa-pen-ruler mr-1"></i>
                                <span>Diseños ({{ designs?.length }})</span>
                            </div>
                        </template>
                        <div v-if="loading" class="flex justify-center items-center py-10">
                            <i class="fa-solid fa-spinner fa-spin text-4xl text-primary"></i>
                        </div>
                        <Designs v-else :designs="designs" />
                    </el-tab-pane>
                </el-tabs> -->
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Quotes from "../Quote/Index.vue";
import Designs from "../Design/Index.vue";
import axios from 'axios';

export default {
    data() {
        return {
            activeTab: '1',
            quoteSearch: null,
            quotes: null,
            designs: null,
            loading: false,
            loadingItems: false,
            currentPage: 1,
        }
    },
    components:{
        AppLayout,
        Designs,    
        Quotes,
    },
    props:{
        totalQuotes: Number
    },
    methods:{
        async fetchQuotes() {
            try {
                this.loading = true;
                const response = await axios.get(route('quotes.fetch'));
                if ( response.status === 200 ) {
                    this.quotes = response.data.items;
                }
            } catch (error) {
             console.log(error);   
            } finally {
                this.loading = false;
            }
        },
        async fetchDesigns() {
            try {
                this.loading = true;
                const response = await axios.get(route('designs.fetch'));
                if ( response.status === 200 ) {
                    this.designs = response.data.items;
                }
            } catch (error) {
             console.log(error);   
            } finally {
                this.loading = false;
            }
        },
        async fetchQuotesByPage() {
            try {
                this.loadingItems = true;
                const response = await axios.get(route('quotes.get-by-page', this.currentPage));

                if (response.status === 200) {
                    this.quotes = [...this.quotes, ...response.data.items];
                    this.currentPage++;
                }
            } catch (error) {
                console.log(error)
            } finally {
                this.loadingItems = false;
            }
        },
    },
    mounted() {
        this.fetchQuotes(); 
        this.fetchDesigns(); 
    }
}
</script>
