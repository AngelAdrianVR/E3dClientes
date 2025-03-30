<template>
    <AppLayout title="Autorización de diseño">
        <div class="lg:flex min-h-[94vh]">
            <section class="w-full">
                <div class="w-full flex justify-between items-center px-10 mt-5">
                    <div class="flex items-center justify-start space-x-1">
                        <Back />
                        <h1 class="text-lg font-bold">AUTORIZACIÓN DE DISEÑO</h1>
                    </div>
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
                                class="w-32 absolute right-20 top-0 bg-gray-100 rounded-md"
                                v-if="design_authorization.data.signature_media?.length > 0 && design_authorization.data.design_accepted">
                                <img class="select-none" :draggable="false"
                                    :src="procesarUrlImagenLocal(design_authorization.data.signature_media[0].original_url)">
                            </figure>
                            <button @click="drawerVisible = true" type="button" v-show="!design_authorization.data.design_accepted"
                                class="absolute right-12 top-3 border border-dashed cursor-pointer border-green-500 text-green-500 rounded-md py-5 px-7"
                                aria-haspopup="dialog" aria-expanded="false" aria-controls="overlay-end-example"
                                data-overlay="#overlay-end-example">Agrega tu firma aquí</button>
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
            <el-drawer
                v-model="drawerVisible"
                title="Firma de aprobación"
                direction="rtl"
                :size="drawerSize"
            >
                <!-- Contenido del drawer -->
                <div>
                    <p class="text-sm text-gray-600 mt-5">
                        Por favor, revisa el documento detenidamente y si todo esta correcto, aprueba.
                        De lo contrario, puedes rechazar y especificar el motivo.
                    </p>
                    <el-radio-group v-model="responseOptions" class="mt-10">
                        <el-radio-button label="Dibujar firma" />
                        <el-radio-button label="Subir imagen" />
                    </el-radio-group>
                    <!-- Dibujar -->
                    <div v-show="responseOptions === 'Dibujar firma'" class="mt-4">
                        <CanvasDraw 
                            :saveDrawUrl="'designs-store-signature'" 
                            :width="328" 
                            :height="200" 
                            :offsetX="offsetX"
                            :offsetY="offsetY"
                            ref="canvasDraw" 
                            :itemId="design_authorization.data.id" 
                        />
                    </div>
                    <!-- Firma guardada -->
                    <div v-show="responseOptions === 'Subir imagen'" class="mt-4">
                        <InputSignature :saveSignatureUrl="'designs-store-signature'"
                            :itemId="design_authorization.data.id" ref="InputSignature" />
                    </div>
                    <div v-if="design_authorization.data.rejected_razon" class="mt-3">
                        <p class="text-primary text-center bg-red-100 rounded-md">Rechazado</p>
                        <p class="text-sm mt-2">
                            <b>Motivo: &nbsp;</b>
                            <span> {{ design_authorization.data.rejected_razon }} </span>
                        </p>
                    </div>
                </div>
                <div class="mt-5 space-x-3 justify-end flex">
                    <button v-if="!design_authorization.data.rejected_razon" @click="drawerVisible = false; rejectDesignModal = true" type="button"
                        class="btn btn-soft bg-primary text-white hover:bg-primary">
                        Rechazar
                    </button>
                    <div class="tooltip">
                        <button @click="sendApproval" type="button"
                            class="btn btn-soft bg-green-700 text-white hover:bg-green-700"
                            :disabled="$refs.canvasDraw?.loading || $refs.InputSignature?.loading || (!$refs.canvasDraw?.lineas.length > 0 && !$refs.InputSignature?.signature)">
                            <span v-if="$refs.canvasDraw?.loading || $refs.InputSignature?.loading"
                                class="loading loading-spinner"></span>
                            Aprobar
                        </button>
                        <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                            <span class="tooltip-body py-2">
                                Al hacer clic, se usará la firma registrada para aprobar la cotización.
                            </span>
                        </span>
                    </div>
                </div>
            </el-drawer>
        </div>
        <Modal :show="rejectDesignModal" @close="rejectDesignModal = false" max-width="lg">
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
                    <div class="flex justify-end space-x-2 pt-5 pb-1 py-2">
                        <button @click="rejectDesignModal = false; rejected_razon = null" type="button"
                            class="btn btn-soft btn-secondary" :disabled="loading">
                            Cancelar
                        </button>
                        <button class="btn btn-soft bg-primary text-white hover:bg-primary" :disabled="loading">
                            <span v-if="loading" class="loading loading-spinner"></span>
                            Enviar
                        </button>
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
import Back from '@/Components/MyComponents/Back.vue';

export default {
    data() {
        return {
            contact: null,
            rejected_razon: null,
            showSideOptions: false,
            rejectDesignModal: false,
            loading: false,
            responseOptions: 'Dibujar firma',
            drawerVisible: false,
            drawerSize: '50%', // Tamaño inicial del drawer
            offsetX: 23, // Valores por defecto (xl) de canvadrawer para la firma
            offsetY: 285, // Valores por defecto (xl) de canvadrawer para la firma
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
        Back,
    },
    props: {
        design_authorization: Object
    },
    methods: {
        async rejectDesign() {
            this.loading = true;
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
                this.loading = false;
                console.log(err);
            }
        },
        async sendApproval() {
            if (this.$refs.canvasDraw?.lineas.length > 0) {
                await this.$refs.canvasDraw.guardarComoObjetoImagen();
            } else {
                await this.$refs.InputSignature.saveImageAsSignature();
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
        updateDrawerSize() {
            const width = window.innerWidth;
            if (width < 550) {
                this.drawerSize = "90%"; // sm
                this.offsetX = 20;
                this.offsetY = 250;
            } else if (width < 900) {
                this.drawerSize = "65%"; // md
                this.offsetX = 20;
                this.offsetY = 450;
            } else if (width < 1280) {
                this.drawerSize = "40%"; // lg
                this.offsetX = 23;
                this.offsetY = 265;
            } else if (width > 1680) {
                this.drawerSize = "25%"; // lg
                this.offsetX = 23;
                this.offsetY = 245;
            } else {
                this.drawerSize = "25%"; // xl
                this.offsetX = 23;
                this.offsetY = 265;
            }
        },
    },
    mounted() {
        // Ajustar tamaño del drawer de element-plus
        this.updateDrawerSize(); // Actualiza tamaño al cargar
        window.addEventListener("resize", this.updateDrawerSize);

        //Guardar la informacion del contacto
        this.contact = this.design_authorization.data.company_branch.contacts.find(contact => contact.id === this.design_authorization.data.contact_id);
    },
    beforeUnmount() {
        window.removeEventListener("resize", this.updateDrawerSize);
    },
}
</script>