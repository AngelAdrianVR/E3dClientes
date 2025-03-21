<template>
    <AppLayout :title="catalog_product.name">
        <main class="p-3 md:p-10">
            {{ catalog_product }}
        </main>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
data() {
    return {

    }
},
components:{
AppLayout
},
props:{
catalog_product: Object
},
methods:{

}
}
</script>
