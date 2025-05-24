<template>
    <main @click="$inertia.get(route('catalog-products.show', catalogProduct.id))"
        class="border border-[#D9D9D9] dark:border-[#6B6767] rounded-2xl h-72 p-2 mb-7 transition-all ease-linear duration-200 hover:shadow-md shadow-gray-600/50">
        <figure class="bg-[#F2F2F2] dark:bg-[#202020] p-2 rounded-2xl h-44 flex justify-center items-center">
            <img v-if="catalogProduct.media?.length" :src="procesarUrlImagen(catalogProduct.media[0]?.original_url)"
                :alt="catalogProduct.name" class="object-contain h-full">
            <div v-else class="flex flex-col items-center text-gray-400">
                <i class="fa-solid fa-image text-6xl"></i>
                <small class="font-bold">Sin imagen</small>
            </div>
        </figure>

        <!-- Información del producto -->
        <section class="py-2 flex flex-col justify-between h-24 mx-5">
            <h1 class="font-bold text-center text-sm truncate w-full px-4">{{ catalogProduct.name }}</h1>
            <div class="flex items-center justify-center">
                <PrimaryButton @click.stop="showQuoteModal = true">Solicitar cotización</PrimaryButton>
            </div>
        </section>
    </main>

    <Modal :show="showQuoteModal" @close="showQuoteModal = false">
        <div class="p-5 relative">
            <h2 class="font-bold">Solicitar cotización</h2>
            <i @click="showQuoteModal = false"
                class="fa-solid fa-xmark w-5 h-5 hover:text-red-600 rounded-full flex items-center justify-center absolute right-3 top-3"></i>
            <p class="text-sm text-gray-600">Indica la cantidad deseada y envía tu solicitud. Nos pondremos en contacto
                contigo pronto.</p>
            <form class="mt-5 mb-2" @submit.prevent="StoreQuoteRequest">
                <div class="mt-3 overflow-auto h-96">
                    <CheckboxImage :data="suggestedProducts"
                        @update:selectedProducts="form.selectedProducts = $event" />
                </div>
                <div class="mt-3">
                    <InputLabel value="Notas adicionales" class="ml-3 mb-1" />
                    <el-input v-model="form.notes" :autosize="{ minRows: 3, maxRows: 5 }" type="textarea"
                        :maxlength="200" show-word-limit clearable />
                </div>
                <InputError v-for="(item, index) in Object.values(form.errors)" :key="index" :message="item"
                    class="mt-2" />
                <div class="flex justify-end space-x-3 pt-5 pb-1 py-2">
                    <CancelButton class="!py-1"
                        @click="showQuoteModal = false; form.notes = null; form.selectedProducts = [];">Cancelar
                    </CancelButton>
                    <PrimaryButton :disabled="form.processing || form.selectedProducts.length === 0" class="!py-1">
                        <i v-if="form.processing" class="fa-sharp fa-solid fa-circle-notch fa-spin mr-2 text-white"></i>
                        Enviar solicitud
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>

</template>

<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CancelButton from "@/Components/MyComponents/CancelButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import CheckboxImage from "@/Components/MyComponents/FlyonUI/CheckboxImage.vue";
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import InputError from '../InputError.vue';

export default {
    data() {
        const form = useForm({
            notes: null,
            selectedProducts: [] // Aquí se guardarán los productos con cantidad seleccionada
        });

        return {
            form,
            showQuoteModal: false
        }
    },
    components: {
        PrimaryButton,
        CheckboxImage,
        CancelButton,
        InputLabel,
        Modal,
        InputError,
    },
    props: {
        catalogProduct: Object,
        suggestedProducts: Array,
    },
    methods: {
        StoreQuoteRequest() {
            this.form.post(route("quotes.store"), {
                preserveScroll: true,
                onSuccess: () => {
                    this.$notify({
                        title: "Éxito",
                        message: "Solicitud enviada",
                        type: "success",
                    });
                    // this.$notyf.success('Solicitud enviada correctamente'); configurar toasts de FlyoinUI
                    this.showQuoteModal = false;
                    this.form.reset();
                },
                onError: (errors) => {
                    // this.$notyf.error('Algo salió mal'); configurar toasts de FlyoinUI
                    console.error("Error al enviar la cotización:", errors);
                },
            });
        },
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