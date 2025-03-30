<template>
    <AppLayout :title="catalog_product.name">
        <main class="p-3 md:p-10">
            <header class="flex items-center justify-start space-x-1">
                <Back />
                <span>Detalles del producto</span>
            </header>

            <body class="my-9 lg:w-[85%] mx-auto md:grid grid-cols-2 gap-7">
                <figure class="rounded-2xl bg-[#F2F2F2] p-4 h-72 flex justify-center items-center relative group">
                    <img v-if="catalog_product.media?.length" 
                        :src="procesarUrlImagen(catalog_product.media[0]?.original_url)" 
                        :alt="catalog_product.name" class="object-contain h-full">

                    <div v-else class="flex flex-col items-center text-gray-300">
                        <i class="fa-solid fa-image text-7xl"></i>
                        <small class="font-bold text-xl">Sin imagen</small>
                    </div>

                    <!-- Ícono de lupa -->
                    <div v-if="catalog_product.media?.length"
                        class="absolute inset-0 flex justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="bg-black/20 p-2 rounded-full size-12 flex items-center justify-center">
                            <i class="fa-solid fa-search text-white text-2xl"></i>
                        </div>
                    </div>
                </figure>

                <section class="my-4">
                    <h1 class="font-bold md:text-lg text-center bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">{{ catalog_product.name }}</h1>
                    <div class="grid grid-cols-3 gap-1 text-sm text-[#575757]">
                        <span>Número de parte</span>
                        <span class="col-span-2">{{ catalog_product.part_number }}</span>
                        <span>Descripción</span>
                        <span class="col-span-2">{{ catalog_product.description ?? '-' }}</span>
                        <span>Unidades de medida</span>
                        <span class="col-span-2">{{ catalog_product.measure_unit }}</span>
                        <h2 v-if="catalog_product.large || catalog_product.height || catalog_product.width" class="my-2 font-bold col-span-full text-lg bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">Dimensiones</h2>
                        <span v-if="catalog_product.large">Largo</span>
                        <span v-if="catalog_product.large" class="col-span-2">{{ catalog_product.large }}</span>
                        <span v-if="catalog_product.height">Alto</span>
                        <span v-if="catalog_product.height" class="col-span-2">{{ catalog_product.height }}</span>
                        <span v-if="catalog_product.width">Ancho</span>
                        <span v-if="catalog_product.width" class="col-span-2">{{ catalog_product.width }}</span>
                    </div>
                </section>
            </body>
        </main>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Back from '@/Components/MyComponents/Back.vue';

export default {
data() {
    return {

    }
},
components:{
AppLayout,
Back
},
props:{
catalog_product: Object
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
