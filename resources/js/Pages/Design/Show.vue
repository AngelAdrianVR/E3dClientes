<template>
    <AppLayout title="Autorización de diseño">
        <div class="lg:flex">
            <section class="w-full">
                <div class="w-full flex justify-between items-center px-10 mt-5">
                    <h1 class="text-lg font-bold">AUTORIZACIÓN DE DISEÑO</h1>
                    <div class="flex items-center">
                        <p class="font-bold mr-3">Versión {{ design_authorization.data.version }}</p>
                    </div>
                </div>
                <!-- content -->
                <div class="text-xs grid grid-cols-2 gap-x-7 mt-5 px-7">
                    <!-- imagen -->
                    <figure v-if="design_authorization.data.product_media?.length > 0"
                        class="w-full flex items-center justify-center h-[410px] border border-[#D9D9D9] rounded-lg">
                        <img class="object-cover"
                            :src="procesarUrlImagen(design_authorization.data.product_media[0].original_url)" alt="">
                    </figure>
                    <!-- <button @click="viewTest(design_authorization.data.product_media[0].original_url)">ver imagen</button> -->
                    <!-- información del diseño -->
                    <div class="grid grid-cols-2 gap-1 text-sm self-start">
                        <p class="text-[#9A9A9A] ">Nombre del producto:</p>
                        <p>{{ design_authorization.data.name }}</p>
                        <p class="text-[#9A9A9A] ">Color:</p>
                        <p>{{ design_authorization.data.color }}</p>
                        <p class="text-[#9A9A9A] ">Material:</p>
                        <p>{{ design_authorization.data.material ?? '--' }}</p>
                        <p class="text-[#9A9A9A] ">Técnica de impresión:</p>
                        <p>{{ design_authorization.data.engrave_method }}</p>
                        <p class="text-[#9A9A9A] ">Vendedor:</p>
                        <p>{{ design_authorization.data.seller?.name }}</p>
                        <p class="text-[#9A9A9A] ">Logística:</p>
                        <p>{{ design_authorization.data.logistic ?? '--' }}</p>

                        <p class="text-[#9A9A9A]  mt-2 col-span-full">Datos del cliente</p>
                        <p class="text-[#9A9A9A] ">Sucursal:</p>
                        <p>{{ design_authorization.data.company_branch?.name }}</p>
                        <p class="text-[#9A9A9A] ">Contacto:</p>
                        <p>{{ contact?.name }}</p>
                        <p class="text-[#9A9A9A] ">Puesto:</p>
                        <p>{{ contact?.charge }}</p>
                        <p class="text-[#9A9A9A] ">correo:</p>
                        <p>{{ contact?.email }}</p>
                        <p class="text-[#9A9A9A] ">Teléfono:</p>
                        <p>{{ contact?.phone }}</p>
                        <p class="text-[#9A9A9A] ">Fecha de aceptación de diseño:</p>
                        <p>{{ contact?.responded_at ?? '--' }}</p>

                        <div class="w-96 relative">
                            <p class="text-[#9A9A9A] mt-16">Firma de autorización: _______________________________</p>
                            <figure @click="showSideOptions = false"
                                class="w-32 absolute right-20 top-1 bg-gray-100 rounded-md"
                                v-if="design_authorization.data.signature_media?.length > 0 && design_authorization.data.design_accepted">
                                <img :src="procesarUrlImagenLocal(design_authorization.data.signature_media[0].original_url)">
                            </figure>
                            <div @click="showSideOptions = true" v-else
                                class="absolute right-12 top-3 border border-dashed border-green-500 text-green-500 rounded-md py-5 px-7 cursor-pointer">
                                Agrega tu firma aquí </div>
                        </div>
                    </div>
                </div>
                <footer class="lg:px-7 mt-1">
                    <h1 class="text-primary text-lg font-bold">Importante</h1>
                    <p class="font-bold">Se solicita una revisión cuidadosa del diseño, los colores y el texto. Una vez
                        autorizado, cualquier omisión será responsabilidad de la persona que lo firme</p>
                    <p class="text-sm text-gray-500">*Los logotipos y marcas mostrados en este formato tienen un
                        propósito exclusivamente ilustrativo, ya que los tonos de los grabados e impresiones pueden
                        variar
                        dependiendo del producto o lote.</p>
                    <p class="text-sm text-gray-500">*Los colores de la pantalla puede variar dependiendo del
                        dispositivo en que se visualicen. </p>
                </footer>
            </section>
            <!-- Seccion de firma -->
            <section v-if="showSideOptions" class="lg:w-[30%] py-7 px-2 border-l border-gray-500 bg-gray-100 relative">
                <button @click="showSideOptions = false;"
                    class="text-xs text-white bg-primary size-5 rounded-full absolute top-1 -left-[12px]">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <p class="text-sm">
                    Por favor, revisa el documento detenidamente. Si todo esta correcto, firma y da click en 'agregar'.
                    De lo contrario, puede rechazar y especificar el motivo.</p>
                <div class="mt-7">
                    <el-radio-group v-model="responseOptions">
                        <el-radio-button label="Dibujar" />
                        <el-radio-button label="Firma guardada" />
                        <el-radio-button label="Rechazar" />
                    </el-radio-group>
                    <!-- Dibujar -->
                    <div v-if="responseOptions === 'Dibujar'" class="mt-4">
                        <p class="text-gray-400 text-xs ml-2 mb-1">Dibuja tu firma en el siguiente recuadro</p>
                        <CanvasDraw :saveDrawUrl="'designs-store-signature'" :width="350" :offsetX="7" :offsetY="184"
                            :itemId="design_authorization.data.id" />
                    </div>
                    <!-- Firma guardada -->
                    <div v-if="responseOptions === 'Firma guardada'" class="mt-4">
                        <InputSignature :saveSignatureUrl="'designs-store-signature'"
                            :itemId="design_authorization.data.id" />
                    </div>
                    <!-- Rechazar -->
                    <div v-if="responseOptions === 'Rechazar'" class="mt-4 text-center">
                        <p class="text-gray-400 text-xs mb-3">Después de haber sido rechazada la cotización puedes
                            reconsiderar y firmar para aceptarla si así lo deseas</p>
                        <PrimaryButton @click="rejectDesignModal = true"
                            v-if="design_authorization.data.status.label !== 'Rechazado'">Rechazar</PrimaryButton>
                        <div v-else>
                            <p class="text-primary text-center">Rechazado</p>
                            <p class="text-center text-sm mt-4">Motivo de rechazo: <strong>{{
                                design_authorization.data.rejected_razon }}</strong></p>
                        </div>
                    </div>
                </div>
                <p class="mt-20 text-sm text-gray-400">
                    Puedes ocultar las opciones de firma haciendo clic en la
                    "X" de la esquina superior derecha del documento. Una vez ocultas, simplemente presiona Ctrl + P
                    para imprimir el documento.
                </p>
            </section>
        </div>
        <Modal :show="rejectDesignModal" @close="rejectDesignModal = false">
            <div class="p-5 relative">
                <h2 class="font-bold">Formulario de rechazo de Diseño</h2>
                <i @click="rejectDesignModal = false"
                    class="fa-solid fa-xmark cursor-pointer w-5 h-5 rounded-full border border-black flex items-center justify-center absolute right-3 top-3"></i>
                <p class="text-sm text-gray-600">Tu retroalimentación nos permite ajustar nuestros diseños para
                    satisfacer
                    mejor tus necesidades y expectativas.</p>

                <form class="mt-5 mb-2" @submit.prevent="rejectDesign">
                    <div class="mt-3">
                        <InputLabel value="Motivo de rechazo*" class="ml-3 mb-1" />
                        <el-input v-model="rejected_razon" :autosize="{ minRows: 3, maxRows: 5 }" type="textarea"
                            :maxlength="200" show-word-limit clearable />
                    </div>

                    <div class="flex justify-end space-x-3 pt-5 pb-1 py-2">
                        <CancelButton class="!py-1" @click="rejectDesignModal = false; rejected_razon = null">Cancelar
                        </CancelButton>
                        <PrimaryButton class="!py-1">Enviar</PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AppLayout>
</template>
<script>

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import CanvasDraw from '@/Components/MyComponents/CanvasDraw.vue';
import InputSignature from '@/Components/MyComponents/InputSignature.vue';
import CancelButton from "@/Components/MyComponents/CancelButton.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    data() {
        return {
            contact: null,
            rejected_razon: null,
            showSideOptions: false,
            rejectDesignModal: false,
            responseOptions: 'Dibujar',
        };
    },
    components: {
        ApplicationLogo,
        PrimaryButton,
        InputSignature,
        CanvasDraw,
        CancelButton,
        InputLabel,
        InputError,
        Modal,
        AppLayout,
    },
    props: {
        design_authorization: Object
    },
    methods: {
        async rejectDesign() {
            try {
                const response = await axios.put(route('designs.reject', this.design_authorization.data.id), { rejected_razon: this.rejected_razon });

                if (response.status == 200) {
                    this.$notify({
                        title: 'Éxito',
                        message: 'Se rechazó la cotización',
                        type: 'success'
                    });
                    location.reload();
                }
            } catch (err) {
                this.$notify({
                    title: 'Algo salió mal',
                    message: 'El campo "motivo de rechazo" es obligatorio',
                    type: 'error'
                });
                console.log(err);
            }
        },
        // Método para procesar la URL de la imagen
        procesarUrlImagen(originalUrl) {
            // Reemplaza la parte inicial de la URL
            const nuevaUrl = originalUrl.replace('https://clientes-emblems3d.dtw.com.mx', 'http://www.intranetemblems3d.dtw.com.mx');
            // const nuevaUrl = originalUrl.replace('http://localhost:8000', 'http://www.intranetemblems3d.dtw.com.mx'); //para hacer pruebas en local
            return nuevaUrl;
        },
        procesarUrlImagenLocal(originalUrl) {
            // Reemplaza la parte inicial de la URL
            // const nuevaUrl = originalUrl.replace('https://clientes-emblems3d.dtw.com.mx', 'http://www.intranetemblems3d.dtw.com.mx');
            const nuevaUrl = originalUrl.replace('http://localhost:8000', 'https://clientes-emblems3d.dtw.com.mx'); //para hacer pruebas en local
            return nuevaUrl;
        },
    },
    mounted() {
        //Guardar la informacion del contacto
        this.contact = this.design_authorization.data.company_branch.contacts.find(contact => contact.id === this.design_authorization.data.contact_id);
    }
}
</script>