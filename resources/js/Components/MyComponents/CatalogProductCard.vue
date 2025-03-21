<template>
    <main @click="$inertia.get(route('catalog-product-company.show', catalogProductCompany.id))" class="border border-[#D9D9D9] rounded-2xl h-72 p-2 transition-all ease-linear duration-200 hover:shadow-2xl shadow-gray-600/50">
        <figure class="bg-[#F2F2F2] p-2 rounded-2xl h-44 flex justify-center items-center relative group">
            <img v-if="catalogProductCompany.catalog_product.media?.length" 
                :src="procesarUrlImagen(catalogProductCompany.catalog_product.media[0]?.original_url)" 
                :alt="catalogProductCompany.catalog_product.name" class="object-contain h-full">

            <div v-else class="flex flex-col items-center text-gray-300">
                <i class="fa-solid fa-image text-4xl"></i>
                <small class="font-bold">Sin imagen</small>
            </div>

            <!-- Ícono de lupa -->
            <!-- <div v-if="catalogProductCompany.catalog_product.media?.length"
                class="absolute inset-0 flex justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <div class="bg-black/20 p-2 rounded-full size-12 flex items-center justify-center">
                    <i class="fa-solid fa-search text-white text-2xl"></i>
                </div>
            </div> -->
        </figure>

        <!-- Información del producto -->
        <section class="py-2 flex flex-col justify-between h-24 mx-5">
            <h1 class="font-bold text-center text-sm truncate w-full px-4">{{ catalogProductCompany.catalog_product.name }}</h1>

            <div v-if="isSugestedProduct" class="flex items-center justify-center">
                <PrimaryButton>Solicitar cotización</PrimaryButton>
            </div>

            <h2 v-else class="text-right">
                <span>P.U.</span>
                <strong class="mx-3">${{ catalogProductCompany.new_price?.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}</strong>
                <span>{{ catalogProductCompany.new_currency === '$MXN' ? 'MXN' : 'USD' }}</span>
            </h2>
        </section>
    </main>
</template>

<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';

export default {
data() {
    return {

    }
},
components:{
PrimaryButton,
},
props:{
catalogProductCompany: Object,
isSugestedProduct: {
    type: Boolean,
    default: false
}
},
methods:{
    // Método para procesar la URL de la imagen y se muestre correctamente
    procesarUrlImagen(originalUrl) {
        // Reemplaza la parte inicial de la URL
        const nuevaUrl = originalUrl?.replace('https://clientes-emblems3d.dtw.com.mx', 'http://www.intranetemblems3d.dtw.com.mx');
        // const nuevaUrl = originalUrl?.replace('http://localhost:8000', 'http://www.intranetemblems3d.dtw.com.mx'); // para hacer pruebas en local
        return nuevaUrl;
    },
    procesarUrlImagenLocal(originalUrl) {
        // Reemplaza la parte inicial de la URL
        // const nuevaUrl = originalUrl.replace('https://clientes-emblems3d.dtw.com.mx', 'http://www.intranetemblems3d.dtw.com.mx');
        const nuevaUrl = originalUrl?.replace('http://localhost:8000', 'https://clientes-emblems3d.dtw.com.mx'); // para hacer pruebas en local
        return nuevaUrl;
    },
}
}
</script>