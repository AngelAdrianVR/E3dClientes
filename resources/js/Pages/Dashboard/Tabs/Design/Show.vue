<template>
    <div class="lg:flex">
        <!-- seccion de cotizacione -->
            <Head :title="design_authorization.data.name" />
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
                <figure v-if="design_authorization.data.product_media?.length > 0" class="w-full flex items-center justify-center h-[600px] border border-[#D9D9D9] rounded-lg">
                    <img class="object-cover" :src="design_authorization.data.product_media[0].original_url" alt="">
                </figure>
                
                <!-- información del diseño -->
                <div class="grid grid-cols-2 gap-x-2 text-sm self-start">
                    <p class="text-[#9A9A9A] my-2">Nombre del producto:</p>
                    <p class="my-2">{{ design_authorization.data.name }}</p>
                    <p class="text-[#9A9A9A] my-2">Color:</p>
                    <p class="my-2">{{ design_authorization.data.color }}</p>
                    <p class="text-[#9A9A9A] my-2">Material:</p>
                    <p class="my-2">{{ design_authorization.data.material ?? '--' }}</p>
                    <p class="text-[#9A9A9A] my-2">Técnica de impresión:</p>
                    <p class="my-2">{{ design_authorization.data.engrave_method }}</p>
                    <p class="text-[#9A9A9A] my-2">Vendedor:</p>
                    <p class="my-2">{{ design_authorization.data.seller?.name }}</p>
                    <p class="text-[#9A9A9A] my-2">Logística:</p>
                    <p class="my-2">{{ design_authorization.data.logistic ?? '--' }}</p>

                    <p class="text-[#9A9A9A] my-2 mt-7 col-span-full">Datos del cliente</p>
                    <p class="text-[#9A9A9A] my-2">Sucursal:</p>
                    <p class="my-2">{{ design_authorization.data.company_branch?.name }}</p>
                    <p class="text-[#9A9A9A] my-2">Contacto:</p>
                    <p class="my-2">{{ contact?.name }}</p>
                    <p class="text-[#9A9A9A] my-2">Puesto:</p>
                    <p class="my-2">{{ contact?.charge }}</p>
                    <p class="text-[#9A9A9A] my-2">correo:</p>
                    <p class="my-2">{{ contact?.email }}</p>
                    <p class="text-[#9A9A9A] my-2">Teléfono:</p>
                    <p class="my-2">{{ contact?.phone }}</p>
                    <p class="text-[#9A9A9A] my-2">Fecha de aceptación de diseño:</p>
                    <p class="my-2">{{ contact?.responded_at ?? '--' }}</p>

                    <div class="w-96 relative">
                        <p class="text-[#9A9A9A] mt-16">Firma de autorización: _______________________________</p>
                        <figure @click="showSideOptions = true" class="w-32 absolute right-20 top-0 border border-dashed border-green-500 cursor-pointer" v-if="design_authorization.data.signature_media?.length > 0">
                            <img :src="design_authorization.data.signature_media[0].original_url" alt="">
                        </figure>
                        <div @click="showSideOptions = true" v-else class="absolute right-12 top-3 border border-dashed border-green-500 text-green-500 rounded-md py-5 px-7 cursor-pointer"> Agrega tu firma aquí </div>
                    </div>
                </div>
            </div>  

            <footer class="lg:p-7 border-b border-[#9A9A9A]">
                <h1 class="text-primary text-lg font-bold">Importante</h1>
                <p class="font-bold">Se solicita una revisión cuidadosa del diseño, los colores y el texto. Una vez autorizado, cualquier omisión será responsabilidad de la persona que lo firme</p>
                <p class="text-sm text-gray-500">*Los logotipos y marcas mostrados en este formato tienen un propósito exclusivamente ilustrativo, ya que los tonos de los grabados e impresiones pueden variar dependiendo del producto o lote.</p>
                <p class="text-sm text-gray-500">*Los colores de la pantalla puede variar dependiendo del dispositivo en que se visualicen. </p>
            </footer>
        </section>

        <!-- Seccion de firma -->
        <section v-if="showSideOptions" class="lg:w-[25%] h-screen py-7 px-2 border-l border-gray-500 bg-gray-100 relative">
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
                    <CanvasDraw :saveDrawUrl="'design-authorizations-store-signature'" :width="350" :offsetX="7" :offsetY="184" :itemId="design_authorization.data.id" />
                </div>

                <!-- Firma guardada -->
                <div v-if="responseOptions === 'Firma guardada'" class="mt-4">
                    <InputSignature :saveSignatureUrl="'authorizations-store-signature'" :itemId="design_authorization.data.id" />
                </div>

                <!-- Rechazar -->
                <div v-if="responseOptions === 'Rechazar'" class="mt-4 text-center">
                    <p class="text-gray-400 text-xs mb-3">Después de haber sido rechazada la cotización puedes reconsiderar y firmar para aceptarla si así lo deseas</p>
                    <PrimaryButton @click="rejectDesignModal = true" v-if="design_authorization.data.status.label !== 'Rechazado'">Rechazar</PrimaryButton>
                    <div v-else>
                        <p class="text-primary text-center">Rechazado</p>
                        <p class="text-center text-sm mt-4">Motivo de rechazo: <strong>{{ design_authorization.data.rejected_razon }}</strong></p>
                    </div>
                </div>
            </div>
            <p class="absolute bottom-5 text-sm text-gray-400">Puedes ocultar las opciones de firma haciendo clic en la "X" de la esquina superior derecha del documento. Una vez ocultas, simplemente presiona Ctrl + P para imprimir el documento.</p>
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
              <CancelButton class="!py-1" @click="rejectDesignModal = false; rejected_razon = null">Cancelar</CancelButton>
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
        Head
    },
    props: {
        design_authorization: Object
    },
    methods:{
        async rejectDesign() {
                try {
                    const response = await axios.put(route('design-authorizations.reject', this.design_authorization.data.id), {rejected_razon: this.rejected_razon});

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
    mounted() {
    //Guardar la informacion del contacto
    this.contact = this.design_authorization.data.company_branch.contacts.find(contact => contact.id === this.design_authorization.data.contact_id);
}
}
</script>