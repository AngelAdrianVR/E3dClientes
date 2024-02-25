<template>
    <AppLayout title="Inicio">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                 <!-- <figure class="flex items-center space-x-5"> -->
                    <!-- <img class="object-cover bg-no-repeat size-9 rounded-full mt-1" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" /> -->
                    <span class="text-sm font-bold">{{ $page.props.auth.user.name }}</span>
                <!-- </figure> -->
                
                <!-- <div class="mt-7 w-1/3 relative">
                    <input v-model="quoteSearch" class="input pr-9" type="text" placeholder="Buscar cotización">
                    <i class="fa-solid fa-magnifying-glass text-[#9A9A9A] text-xs absolute right-4 top-2"></i>
                </div> -->

                <el-tabs v-model="activeTab" class="mt-10">
                    <el-tab-pane name="1">
                        <template #label>
                            <div class="flex items-center">
                                <i class="fa-regular fa-square-check mr-1"></i>
                                <span>Cotizaciones ({{ quotes?.length }})</span>
                            </div>
                        </template>
                        <!-- estado de carga -->
                        <div v-if="loading" class="flex justify-center items-center py-10">
                            <i class="fa-solid fa-spinner fa-spin text-4xl text-primary"></i>
                        </div>
                        <Quotes v-else :quotes="quotes" />
                    </el-tab-pane>
                    <el-tab-pane name="2">
                        <template #label>
                            <div class="flex items-center">
                                <i class="fa-solid fa-pen-ruler mr-1"></i>
                                <span>Diseños ({{ designs?.length }})</span>
                            </div>
                        </template>
                        <!-- estado de carga -->
                        <div v-if="loading" class="flex justify-center items-center py-10">
                            <i class="fa-solid fa-spinner fa-spin text-4xl text-primary"></i>
                        </div>
                        <Designs v-else :designs="designs" />
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Quotes from "./Tabs/Quote/Quotes.vue";
import Designs from "./Tabs/Design/Designs.vue";
import axios from 'axios';

export default {
    data() {
        return {
            activeTab: '1',
            quoteSearch: null,
            quotes: null,
            designs: null,
            loading: false,
        }
    },
    components:{
        AppLayout,
        Designs,    
        Quotes,
    },
    props:{},
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
                const response = await axios.get(route('design-authorizations.fetch'));
                if ( response.status === 200 ) {
                    this.designs = response.data.items;
                }
            } catch (error) {
             console.log(error);   
            } finally {
                this.loading = false;
            }
        },
    },
    mounted() {
        this.fetchQuotes(); 
        this.fetchDesigns(); 
    }
}
</script>
