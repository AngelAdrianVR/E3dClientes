<template>
    <div class="lg:flex">
        <!-- seccion de cotizacione -->
        <section class="">
            <Head :title="quote.data.folio" />
            <!-- logo -->
            <div class="text-center">
                <ApplicationLogo class="h-auto w-3/12 inline-block" />
            </div>

            <!-- content -->
            <div class="text-xs">

                <!-- header -->
                <div>
                    <p class="flex items-center justify-end ml-auto font-bold mr-6 text-xs text-gray-700">
                        Guadalajara, Jalisco {{ quote.data.created_at }}
                    </p>
                    <p class="w-11/12 text-lg mx-auto font-bold text-gray-700">
                        {{ quote.data.companyBranch?.name }}
                    </p>
                    <p class="w-11/12 mx-auto font-bold mt-2 text-gray-700">
                        Estimado(a) {{ quote.data.receiver }} - {{ quote.data.department }}
                    </p>
                    <p class="w-11/12 mx-auto my-2 pb-2 text-gray-700">
                        Por medio de la presente reciba un cordial saludo y a su vez le proporciono la cotización que nos
                        solicitó,
                        con base en la plática sostenida con ustedes y sabiendo de sus condiciones del producto a aplicar:
                    </p>
                </div>

                <!-- table -->
                <table class="rounded-t-lg m-2 w-11/12 mx-auto bg-gray-300 text-gray-800" style="font-size: 10.2px;">
                    <thead>
                        <tr class="text-left border-b-2 border-gray-400">
                            <th class="px-2 py-1">Concepto</th>
                            <th class="px-2 py-1">Notas</th>
                            <th class="px-2 py-1">$ unitario</th>
                            <th class="px-2 py-1">Unidades</th>
                            <th class="px-2 py-1 text-right">Total sin IVA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in quote.data.products" :key="index" class="bg-gray-200 text-gray-700 uppercase">
                            <td class="px-2 py-px">{{ item?.name }}</td>
                            <td class="px-2 py-px">{{ item?.pivot.notes ?? '--' }}</td>
                            <td class="px-2 py-px">{{ item?.pivot.price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }} {{ quote.data.currency }}</td>
                            <td class="px-2 py-px">{{ item?.pivot.quantity.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }} {{ item.measure_unit }}</td>
                            <td class="px-2 py-px text-right">{{ (item.pivot.quantity * item.pivot.price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }} {{ quote.data.currency }}
                            </td>
                        </tr>
                    </tbody>
                    <!-- <tfoot>
                        <tr>
                            <td class="text-center border-t-2 border-gray-400 py-1 font-bold" colspan="6">
                                TOTAL SIN IVA: {{ quote.data.total.number_format }} {{ quote.data.currency }}
                            </td>
                        </tr>
                    </tfoot> -->
                </table>

                <!-- Images -->
                <div class="w-11/12 mx-auto my-3 grid grid-cols-3 gap-4 ">
                    <template v-for="item in quote.data.products" :key="item.id">
                        <div v-if="item.pivot.show_image" class="bg-gray-200 rounded-t-xl rounded-b-md border" style="font-size: 8px;">
                            <img class="rounded-t-xl max-h-52 mx-auto" :src="item.media[0]?.original_url">
                            <p class="py-px px-1 uppercase text-gray-600">{{ item?.name }}</p>
                        </div>
                    </template>
                </div>

                <div class="flex justify-between items-center mx-10 mt-9">
                    <!-- goodbyes -->
                    <p class="my-2 pb-2 text-gray-700">
                        Sin más por el momento y en espera de su preferencia,
                        quedo a sus órdenes para cualquier duda o comentario.
                        Folio de cotización: <span class="font-bold bg-yellow-100">{{ quote.data.folio }}</span>
                    </p>
                    
                    <!-- signature -->
                    <div @click="showSideOptions = true" class="mr-7 relative cursor-pointer">
                        <p class="text-gray-500">Firma de autorización:  _________________________________ </p>
                        <figure class="w-32 absolute right-5 -top-[55px] border border-dashed border-green-500" v-if="quote.data.media?.length > 0">
                            <img :src="quote.data.media[0].original_url" alt="">
                        </figure>
                        <div v-else class="absolute right-0 -top-12 border border-dashed border-green-500 text-green-500 rounded-md py-5 px-7"> Agrega tu firma aquí </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="w-11/12 mx-auto border border-gray-500 px-3 pb-1 mt-1 rounded-xl text-gray-500 leading-normal uppercase"
                    style="font-size: 10.5px;">
                    <h2 class="text-center font-extrabold">IMPORTANTE <i class="fas fa-exclamation-circle text-amber-500"></i>
                    </h2>
                    <ol class="list-decimal mx-2 mb-2">
                        <li v-if="quote.data.notes !== '--'" class="font-bold text-blue-500">{{ quote.data.notes }}</li>
                        <li>PRECIOS ANTES DE IVA</li>
                        <li>COSTO DE HERRAMENTAL: <span class="font-bold text-blue-500" :class="quote.data.tooling_cost_stroked ? 'line-through' : ''">{{ quote.data.tooling_cost }} {{
                            quote.data.tooling_currency }}</span></li>
                        <li>TIEMPO DE ENTREGA PARA LA PRIMER PRODUCCIÓN <span class="font-bold text-blue-500">{{
                            quote.data.first_production_days }}</span>.
                            EL TIEMPO CORRE UNA VEZ PAGANDO EL 100% DEL HERRAMENTAL Y EL 50% DE LOS PRODUCTOS.</li>
                        <li>FLETES Y ACARREOS CORREN POR CUENTA DEL CLIENTE: <span class="font-bold text-blue-500">{{
                            quote.data.freight_cost }} {{ !isNaN(quote.data.freight_cost) ? quote.data.currency : '' }}</span></li>
                        <li>PRECIOS EN <span class="font-bold text-blue-500">{{ quote.data.currency }}</span></li>
                        <li>COTIZACIÓN VÁLIDA POR 21 DÍAS. EL PRODUCTO ESTÁ SUJETO A LA REVISIÓN DEL DISEÑO FINAL, PRUEBAS Y
                            SUBSECUENTE APROBACIÓN</li>
                    </ol>
                    PAGOS.- POR TRANSFERENCIA BANCARIA O DEPÓSITO ENVIARSE A MARIBEL@EMBLEMAS3D.COM O
                    ASISTENTE.DIRECTOR@EMBLEMAS3D.COM <br>
                    NO SE ACEPTAN PAGOS EN EFECTIVO, TODOS LOS CHEQUES DEBEN USAR NOMBRE DE: EMBLEMS 3D USA 
                    SA DE CV. Y SELLO PARA ABONO EN CUENTA DEL BENEFICIARIO
                </div>

                <!-- banks -->
                <div class="grid grid-cols-2 gap-0 text-xs mt-1 font-semibold" style="font-size: 10px;">
                    <div class="bg-sky-600 text-white p-1 flex justify-between rounded-l-xl">
                        <span>BANORTE M.N.</span>
                        <span>CUENTA: 1180403344</span>
                        <span>CLABE: 072 320 011804033446</span>
                    </div>
                    <div class="bg-red-600 text-white p-1 flex justify-between rounded-r-xl">
                        <span>BANORTE USD</span>
                        <span>CUENTA: 1181103856</span>
                        <span>CLABE: 072 320 011811038560</span>
                    </div>
                </div>

                <!-- Author -->
                <div class="mt-1 text-gray-700 flex justify-around" style="font-size: 11px;">
                    <div>
                        Creado por: {{ quote.data.user?.name }} &nbsp;&nbsp;
                        Tel: {{ quote.data.user?.employee_properties?.phone }} &nbsp;&nbsp;
                        correo: {{ quote.data.user?.email }} &nbsp;&nbsp;
                    </div>
                    <div>
                        Autorizado por:
                        <span v-if="quote.data.authorized_user_name != 'No autorizado'" class="text-green-600">{{ quote.data.authorized_user_name }}</span>
                        <!-- No authorized Banner -->
                        <span v-else class="text-amber-500">Sin autorización</span>

                        <div v-if="quote.data.authorized_user_name == 'No autorizado'" class="absolute left-28 top-1/3 text-red-700 text-5xl border-4 border-red-700 p-6">
                            <i class="fas fa-exclamation"></i>
                            <span class="ml-2">SIN AUTORIZACIÓN</span>
                        </div>

                    </div>
                </div>

                <!-- footer -->
                <footer class="text-gray-400 w-11/12 mx-auto mt-3" style="font-size: 11px;">
                    <div class="grid grid-cols-3 gap-x-4">
                        <div class="text-center text-sm font-bold">
                            Especialistas en
                            Emblemas de alta calidad
                        </div>
                        <div>
                            <i class="fas fa-mobile-alt"></i> 333 46 46 485 <br>
                            <i class="fas fa-phone-alt"></i> (33) 38 33 82 09
                        </div>
                        <div>
                            <i class="fas fa-globe"></i> www.emblemas<b class="text-sky-600">3</b><b class="text-red-600">d</b>.com
                            <br>
                            <i class="fas fa-envelope"></i> j.sherman@emblemas<b class="text-sky-600">3</b><b
                                class="text-red-600">d</b>.com
                        </div>
                    </div>
                    <div class="flex">
                        <p class="mt-3 leading-tight mr-1" style="font-size: 10px;">
                            Emblemas de alta calidad, somos los mejores fabricantes. Ramo automotríz,
                            electrodomésticos, electrónica, textíl, calzado, muebles y juguetes.
                            En división electrónica, somos desarrolladores de tecnología. Conoce
                            nuestras nuevas memorias USB personalizadas desde el molde, son exclusivos.
                            En división automotríz somos fabricantes especialistas en emblemas cromados,
                            porta placas, llaveros, porta documentos, placas de estireno. Lo nuevo,
                            LLAVERO USB, diseño original y personalizado, todo con molde único para tu
                            empresa (personalización total y exclusiva).
                        </p>
                        <!-- <x-company-shield width="50" /> -->
                    </div>
                </footer>
            </div>
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
                    <CanvasDraw :saveDrawUrl="'quotes-store-signature'" :width="350" :height="200" :offsetX="7" :offsetY="180" :itemId="quote.data.id" />
                </div>

                <!-- Firma guardada -->
                <div v-if="responseOptions === 'Firma guardada'" class="mt-4">
                    <InputSignature :saveSignatureUrl="'quotes-store-signature'" :itemId="quote.data.id" />
                </div>

                <!-- Rechazar -->
                <div v-if="responseOptions === 'Rechazar'" class="mt-4 text-center w-[350px]">
                    <p class="text-gray-400 text-xs mb-3">Después de haber sido rechazada la cotización puedes reconsiderar y firmar para aceptarla si así lo deseas</p>
                    <PrimaryButton @click="rejectQuoteModal = true" v-if="quote.data.status.label !== 'Rechazado'">Rechazar</PrimaryButton>
                    <div v-else>
                        <p class="text-primary text-center">Rechazado</p>
                        <p class="text-center text-sm mt-4">Motivo de rechazo: <strong>{{ quote.data.rejected_razon }}</strong></p>
                    </div>
                </div>

            </div>
                <p class="absolute bottom-5 text-sm text-gray-400">Puedes ocultar las opciones de firma haciendo clic en la "X" de la esquina superior derecha del documento. Una vez ocultas, simplemente presiona Ctrl + P para imprimir el documento.</p>
        </section>
    </div>

    <!-- -------------- Modal starts----------------------- -->
      <Modal :show="rejectQuoteModal" @close="rejectQuoteModal = false">
        <div class="p-5 relative">
            <h2 class="font-bold">Formulario de rechazo de la cotización</h2>
            <i @click="rejectQuoteModal = false"
            class="fa-solid fa-xmark cursor-pointer w-5 h-5 rounded-full border border-black flex items-center justify-center absolute right-3 top-3"></i>
            <p class="text-sm text-gray-600">Ayúdanos a mejorar nuestro servicio proporcionándonos comentarios sobre por qué estás rechazando la cotización</p>

          <form class="mt-5 mb-2" @submit.prevent="rejectQuote">
            <div class="mt-3">
              <InputLabel value="Motivo de rechazo*" class="ml-3 mb-1" />
              <el-input v-model="rejected_razon" :autosize="{ minRows: 3, maxRows: 5 }" type="textarea" :maxlength="200"
                show-word-limit clearable />
            </div>

            <div class="flex justify-end space-x-3 pt-5 pb-1 py-2">
              <CancelButton class="!py-1" @click="rejectQuoteModal = false; form.reset()">Cancelar</CancelButton>
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
            rejectQuoteModal: false,
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
        quote: Object
    },
    methods:{
        async rejectQuote() {
                try {
                    const response = await axios.put(route('quotes.reject', this.quote.data.id), {rejected_razon: this.rejected_razon});

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