<template>
    <div class="lg:flex">
        <!-- seccion de cotizacione -->
            <Head :title="design.data.name" />
        <section class="w-full">
            <!-- logo -->
            <div class="w-full flex justify-between items-center p-5 border-b-2">
                <div class="w-52">
                    <ApplicationLogo />
                </div>
                <h1 class="text-xl font-bold">FORMATO DE AUTORIZACIÓN</h1>
                <div class="flex items-center">
                    <p class="font-bold mr-3">Versión</p>
                </div>
            </div>

            <!-- content -->
            <div class="text-xs grid grid-cols-2 gap-x-7 mt-10 px-7">   
                <!-- imagen -->
                <figure class="w-full h-[600px] border border-[#D9D9D9] rounded-lg">
                    <img :src="design.data.media?.find(file => file.collection_name === 'results')?.original_url" alt="">
                </figure>
                
                <!-- información del diseño -->
                <div class="grid grid-cols-2 gap-x-2 text-sm self-start">
                    <p class="text-[#9A9A9A] my-2">Nombre del producto:</p>
                    <p>{{ design.data.name }}</p>
                    <p class="text-[#9A9A9A] my-2">Color:</p>
                    <p>{{ 'metal' }}</p>
                    <p class="text-[#9A9A9A] my-2">Material:</p>
                    <p>{{ 'solidchrome' }}</p>
                    <p class="text-[#9A9A9A] my-2">Técnica de impresión:</p>
                    <p>{{ '-' }}</p>
                    <p class="text-[#9A9A9A] my-2">Vendedor:</p>
                    <p>{{ design.data.user.name }}</p>
                    <p class="text-[#9A9A9A] my-2">Fecha de autorización:</p>
                    <p>{{ design.data.authorized_at }}</p>
                    <p class="text-[#9A9A9A] my-2">Logística:</p>
                    <p>{{ '-' }}</p>

                    <p class="text-[#9A9A9A] my-2 mt-7 col-span-full">Datos del cliente:</p>
                    <p class="text-[#9A9A9A] my-2">Nombre de contacto:</p>
                    <p>{{ design.data.contact_name }}</p>
                    <p class="text-[#9A9A9A] my-2">Puesto:</p>
                    <p>{{ 'puesto' }}</p>
                    <p class="text-[#9A9A9A] my-2">Empresa:</p>
                    <p>{{ 'Empresa' }}</p>

                    <div class="w-96 relative">
                        <p class="text-[#9A9A9A] mt-16">Firma de autorización: _________________________________</p>
                        <figure @click="showSideOptions = true" class="w-32 absolute right-4 -top-[70px] border border-dashed border-green-500" v-if="design.data.media?.find(file => file.collection_name === 'signarute')">
                            <img :src="quote.data.media[0].original_url" alt="">
                        </figure>
                        <div @click="showSideOptions = true" v-else class="absolute right-12 top-3 border border-dashed border-green-500 text-green-500 rounded-md py-5 px-7 cursor-pointer"> Agrega tu firma aquí </div>
                    </div>
                </div>
            </div>  
        </section>

        <!-- Seccion de firma -->
        <section v-if="showSideOptions" class="w-[25%] h-screen py-7 px-2 border-l border-gray-500 bg-gray-100 relative">
            <i @click="showSideOptions = false;" class="fa-solid fa-xmark text-xs text-white bg-primary py-1 px-[7px] rounded-full absolute top-1 -left-[12px] cursor-pointer"></i>
            <p class="text-sm">Por favor, revisa el documento detenidamente. Si todo esta correcto, firme y envíe. De lo contrario, puede rechazar y especificar el motivo.</p>

            <div class="mt-7">
                <el-radio-group v-model="responseOptions">
                    <el-radio-button label="Dibujar" />
                    <el-radio-button label="Firma guardada" />
                    <el-radio-button label="Rechazar" />
                </el-radio-group>
                
                <!-- Dibujar -->
                <div v-if="responseOptions === 'Dibujar'" class="mt-4">
                    <p class="text-gray-400 text-xs ml-2 mb-1">Dibuja tu firma en el siguiente recuadro</p>
                    <CanvasDraw :saveDrawUrl="'designs-store-signature'" :width="350" :offsetX="7" :offsetY="184" :itemId="design.data.id" />
                </div>

                <!-- Firma guardada -->
                <div v-if="responseOptions === 'Firma guardada'" class="mt-4">
                    <InputSignature :quoteId="design.data.id" />
                </div>

                <!-- Rechazar -->
                <!-- <div v-if="responseOptions === 'Rechazar'" class="mt-4 text-center">
                    <p class="text-gray-400 text-xs mb-3">Después de haber sido rechazada la cotización puedes reconsiderar y firmar para aceptarla si así lo deseas</p>
                    <PrimaryButton @click="rejectDesignModal = true" v-if="design.data.status.label !== 'Rechazado'">Rechazar</PrimaryButton>
                    <div v-else>
                        <p class="text-primary text-center">Rechazado</p>
                        <p class="text-center text-sm mt-4">Motivo de rechazo: <strong>{{ design.data.rejected_razon }}</strong></p>
                    </div>
                </div> -->
            </div>
        </section>
    </div>

    <!-- -------------- Modal starts----------------------- -->
      <Modal :show="rejectDesignModal" @close="rejectDesignModal = false">
        <div class="p-5 relative">
            <h2 class="font-bold">Formulario de rechazo de Diseño</h2>
            <i @click="rejectDesignModal = false"
            class="fa-solid fa-xmark cursor-pointer w-5 h-5 rounded-full border border-black flex items-center justify-center absolute right-3 top-3"></i>
            <p class="text-sm text-gray-600">Tu retroalimentación nos permite ajustar nuestros diseños para satisfacer mejor tus necesidades y expectativas.</p>

          <form class="mt-5 mb-2" @submit.prevent="rejectDesign">
            <div class="mt-3">
              <InputLabel value="Motivo de rechazo*" class="ml-3 mb-1" />
              <el-input v-model="rejected_razon" :autosize="{ minRows: 3, maxRows: 5 }" type="textarea" :maxlength="200"
                show-word-limit clearable />
            </div>

            <div class="flex justify-end space-x-3 pt-5 pb-1 py-2">
              <CancelButton class="!py-1" @click="rejectDesignModal = false; form.reset()">Cancelar</CancelButton>
              <PrimaryButton class="!py-1">Enviar</PrimaryButton>
            </div>
          </form>
        </div>
      </Modal>
      <!-- --------------------------- Modal ends ------------------------------------ -->
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
import { Head } from '@inertiajs/vue3';

export default {
    data() {
        return {
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
        Head
    },
    props: {
        design: Object
    },
    methods:{
        async rejectDesign() {
                try {
                    const response = await axios.put(route('designs.reject', this.design.data.id), {rejected_razon: this.rejected_razon});

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
    },
}
</script>