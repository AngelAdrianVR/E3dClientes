<template>
    <AppLayout :title="'Mis Productos'">
        <main class="p-3 md:p-10">
            <h1 class="font-bold text-lg">Mis productos</h1>
            <span>Productos que adquieres con nosotros</span>

            <section class="my-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                <CatalogProductCard v-for="item in catalog_products_company" :key="item.id" :catalogProductCompany="item" />
            </section>
        </main>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import CatalogProductCard from '@/Components/MyComponents/CatalogProductCard.vue';

export default {
data() {
    return {

    }
},
components:{
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
