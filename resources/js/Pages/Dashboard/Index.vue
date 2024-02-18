<template>
    <AppLayout title="Inicio">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                 <figure class="flex items-center space-x-5">
                    <img class="object-cover bg-no-repeat size-9 rounded-full mt-1" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" />
                    <span class="text-sm font-bold">{{ $page.props.auth.user.name }}</span>
                </figure>
                
                <div class="mt-7 w-1/3 relative">
                    <input v-model="quoteSearch" class="input pr-9" type="text" placeholder="Buscar cotización">
                    <i class="fa-solid fa-magnifying-glass text-[#9A9A9A] text-xs absolute right-4 top-2"></i>
                </div>

                <el-tabs v-model="activeTab" class="mt-10">
                    <el-tab-pane name="1">
                        <template #label>
                            <div class="flex items-center">
                                <i class="fa-regular fa-square-check mr-1"></i>
                                <span>Cotizaciones ({{ '3' }})</span>
                            </div>
                        </template>
                        <Quotes />
                    </el-tab-pane>
                    <el-tab-pane name="2">
                        <template #label>
                            <div class="flex items-center">
                                <i class="fa-solid fa-pen-ruler mr-1"></i>
                                <span>Diseños ({{ '3' }})</span>
                            </div>
                        </template>
                        <Designs />
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Quotes from "./Tabs/Quotes.vue";
import Designs from "./Tabs/Designs.vue";

export default {
    data() {
        return {
            activeTab: '1',
            quoteSearch: null,
        }
    },
    components:{
        AppLayout,
        Designs,    
        Quotes,
    },
    props:{

    },
    methods:{

    }
}
</script>
