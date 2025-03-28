<template>
    <div>
        <!-- seccion de cotizacione -->
        <section>
            <Head :title="quote.data.folio" />
            <!-- logo -->
            <div class="text-center">
                <ApplicationLogo class="h-auto w-3/12 inline-block" />
            </div>
            <div class="text-xs">
                <!-- header -->
                <div>
                    <p class="flex items-center justify-end ml-auto font-bold mr-6 text-xs text-gray-700 dark:text-white">
                        Guadalajara, Jalisco {{ quote.data.created_at }}
                    </p>
                    <p class="w-11/12 text-lg mx-auto font-bold text-gray-700 dark:text-white">
                        {{ quote.data.companyBranch?.name }}
                    </p>
                    <p class="w-11/12 mx-auto font-bold mt-2 text-gray-700 dark:text-white">
                        Estimado(a) {{ quote.data.receiver }} - {{ quote.data.department }}
                    </p>
                    <p class="w-11/12 mx-auto my-2 pb-2 text-gray-700 dark:text-white">
                        Por medio de la presente reciba un cordial saludo y a su vez le proporciono la cotización que
                        nos
                        solicitó,
                        con base en la plática sostenida con ustedes y sabiendo de sus condiciones del producto a
                        aplicar:
                    </p>
                </div>

                <!-- table -->
                <table class="rounded-t-lg m-2 w-11/12 mx-auto bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white" style="font-size: 10.2px;">
                    <thead>
                        <tr class="text-left border-b-2 border-gray-400">
                            <th class="px-2 py-1 w-10">Aprobados</th>
                            <th class="px-2 py-1">Concepto</th>
                            <th class="px-2 py-1">Notas</th>
                            <th class="px-2 py-1">$ unitario</th>
                            <th class="px-2 py-1">Unidades</th>
                            <th class="px-2 py-1 text-right">Total sin IVA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in quote.data.products" :key="index" class="text-gray-700 uppercase"
                            :class="approvedProducts.includes(item.id) ? 'bg-green-200 dark:bg-green-400' : 'bg-gray-200'">
                            <td class="px-2 py-px">
                                <input type="checkbox" :value="item.id" v-model="approvedProducts"
                                    :disabled="quote.data.quote_acepted"
                                    class="size-4 text-green-600 bg-gray-100 border-gray-400 rounded-sm focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 disabled:cursor-not-allowed disabled:opacity-75">
                            </td>
                            <td class="px-2 py-px">{{ item?.name }}</td>
                            <td class="px-2 py-px">{{ item?.pivot.notes ?? '--' }}</td>
                            <td class="px-2 py-px">{{ item?.pivot.price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                }} {{ quote.data.currency }}</td>
                            <td class="px-2 py-px">{{ item?.pivot.quantity.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,
                                ",") }} {{ item.measure_unit }}</td>
                            <td class="px-2 py-px text-right">{{ (item.pivot.quantity *
                                item.pivot.price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }} {{
                                    quote.data.currency }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot v-if="approvedProducts.length > 0">
                        <tr>
                            <td class="text-end pr-2 py-px" colspan="6">
                                TOTAL PRODUCTOS SIN IVA: {{
                                    calculateApprovedTotal().toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }} {{
                                    quote.data.currency }}
                            </td>
                        </tr>
                        <tr v-if="!isNaN(quote.data.freight_cost)">
                            <td class="text-end pr-2 py-px" colspan="6">
                                COSTO DE FLETE: {{
                                    parseFloat(quote.data.freight_cost).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}
                                {{
                                    quote.data.currency }}
                            </td>
                        </tr>
                        <tr v-if="!isNaN(quote.data.tooling_cost)">
                            <td class="text-end pr-2 py-px" colspan="6">
                                COSTO DE HERRAMENTAL: {{
                                    parseFloat(quote.data.tooling_cost).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",") }}
                                {{
                                    quote.data.tooling_currency }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-end pr-2 py-px font-bold" colspan="6">
                                TOTAL SIN IVA: {{
                                    (
                                        calculateApprovedTotal() +
                                        (!isNaN(quote.data.freight_cost) ? parseFloat(quote.data.freight_cost) : 0) +
                                        (!isNaN(quote.data.tooling_cost) ? parseFloat(quote.data.tooling_cost) : 0)
                                    ).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                }} {{ quote.data.currency }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Images -->
                <div class="w-11/12 mx-auto my-3 grid grid-cols-3 gap-4 ">
                    <!-- <template v-for="item in quote.data.products" :key="item.id">
                        <div v-if="item.pivot.show_image" class="bg-gray-200 rounded-t-xl rounded-b-md border" style="font-size: 8px;">
                            <img class="rounded-t-xl max-h-52 mx-auto" :src="procesarUrlImagen(item.media[0]?.original_url)">
                            <p class="py-px px-1 uppercase text-gray-600">{{ item?.name }}</p>
                        </div>
                    </template> -->
                    <template v-for="(item, productIndex) in quote.data.products" :key="item.id">
                        <div v-if="item.pivot.show_image" class="bg-gray-200 dark:bg-gray-500 rounded-t-xl rounded-b-md border"
                            style="font-size: 8px;">
                            <img class="rounded-t-xl max-h-52 mx-auto cursor-pointer"
                                :src="procesarUrlImagen(item.media[currentImages[productIndex]]?.original_url)"
                                @click="openImageInNewTab(item.media[currentImages[productIndex]]?.original_url)">
                            <!-- selector de imagen cuando son varias -->
                            <div v-if="item.media?.length > 1" class="my-3 flex items-center justify-center space-x-3">
                                <i @click="currentImages[productIndex] = index"
                                    v-for="(image, index) in item.media?.length" :key="index"
                                    :class="index == currentImages[productIndex] ? 'text-black' : 'text-white'"
                                    class="fa-solid fa-circle text-[7px] cursor-pointer"></i>
                            </div>
                            <p class="py-px px-1 uppercase text-gray-600 dark:text-white">{{ item.name }}</p>
                        </div>
                    </template>
                </div>

                <div class="flex justify-between items-center mx-10 mt-16">
                    <!-- goodbyes -->
                    <p class="my-2 pb-2 text-gray-700 dark:text-white">
                        Sin más por el momento y en espera de su preferencia,
                        quedo a sus órdenes para cualquier duda o comentario.
                        Folio de cotización: <span class="font-bold bg-yellow-100 dark:bg-yellow-900">{{ quote.data.folio }}</span>
                    </p>
                    <!-- signature -->
                    <div class="mr-7 relative">
                        <p class="text-gray-500 dark:text-white">Firma de autorización: _________________________________ </p>
                        <figure class="w-32 absolute right-5 -top-[66px] bg-gray-100 rounded-lg"
                            v-if="quote.data.signature_media?.length && quote.data.quote_acepted">
                            <img :src="procesarUrlImagenLocal(quote.data.signature_media[0].original_url)" :draggable="false" class="select-none">
                        </figure>
                        <button type="button" v-show="approvedProducts.length && !quote.data.quote_acepted"
                            class="absolute right-0 -top-12 border border-dashed cursor-pointer border-green-500 text-green-500 rounded-md py-5 px-7"
                            aria-haspopup="dialog" aria-expanded="false" aria-controls="overlay-end-example"
                            data-overlay="#overlay-end-example">Agrega tu firma aquí</button>
                    </div>
                </div>
                <!-- Notes -->
                <div class="w-11/12 mx-auto border border-gray-500 px-3 pb-1 mt-1 rounded-xl text-gray-500 dark:text-gray-400 leading-normal uppercase"
                    style="font-size: 10.5px;">
                    <h2 class="text-center font-extrabold">IMPORTANTE <i
                            class="fas fa-exclamation-circle text-amber-500"></i>
                    </h2>
                    <ol class="list-decimal mx-2 mb-2">
                        <li v-if="quote.data.notes !== '--'" class="font-bold text-blue-500">{{ quote.data.notes }}</li>
                        <li>PRECIOS ANTES DE IVA</li>
                        <li>COSTO DE HERRAMENTAL: <span class="font-bold text-blue-500"
                                :class="quote.data.tooling_cost_stroked ? 'line-through' : ''">{{
                                    quote.data.tooling_cost }} {{
                                    quote.data.tooling_currency }}</span></li>
                        <li>TIEMPO DE ENTREGA PARA LA PRIMER PRODUCCIÓN <span class="font-bold text-blue-500">{{
                            quote.data.first_production_days }}</span>.
                            EL TIEMPO CORRE UNA VEZ PAGANDO EL 100% DEL HERRAMENTAL Y EL 50% DE LOS PRODUCTOS.</li>
                        <li>FLETES Y ACARREOS CORREN POR CUENTA DEL CLIENTE: <span class="font-bold text-blue-500">{{
                            quote.data.freight_cost }} {{ !isNaN(quote.data.freight_cost) ? quote.data.currency : ''
                                }}</span></li>
                        <li>PRECIOS EN <span class="font-bold text-blue-500">{{ quote.data.currency }}</span></li>
                        <li>COTIZACIÓN VÁLIDA POR 21 DÍAS. EL PRODUCTO ESTÁ SUJETO A LA REVISIÓN DEL DISEÑO FINAL,
                            PRUEBAS Y
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
                <div class="mt-1 text-gray-700 dark:text-white flex justify-around" style="font-size: 11px;">
                    <div>
                        Creado por: {{ quote.data.user?.name }} &nbsp;&nbsp;
                        Tel: {{ quote.data.user?.employee_properties?.phone }} &nbsp;&nbsp;
                        correo: {{ quote.data.user?.email }} &nbsp;&nbsp;
                    </div>
                    <div>
                        Autorizado por:
                        <span v-if="quote.data.authorized_user_name != 'No autorizado'" class="text-green-600">{{
                            quote.data.authorized_user_name }}</span>
                        <!-- No authorized Banner -->
                        <span v-else class="text-amber-500">Sin autorización</span>

                        <div v-if="quote.data.authorized_user_name == 'No autorizado'"
                            class="absolute left-28 top-1/3 text-red-700 text-5xl border-4 border-red-700 p-6">
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
                            <i class="fas fa-globe"></i> www.emblemas<b class="text-sky-600">3</b><b
                                class="text-red-600">d</b>.com
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
        <div id="overlay-end-example" class="overlay overlay-open:translate-x-0 drawer drawer-end hidden" role="dialog"
            tabindex="-1">
            <div class="drawer-header">
                <h3 class="drawer-title">Firma de aprobación</h3>
                <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3" aria-label="Close"
                    data-overlay="#overlay-end-example">
                    <span class="icon-[tabler--x] size-5"></span>
                </button>
            </div>
            <div class="drawer-body">
                <p class="text-sm">
                    Por favor, revisa la cotización detenidamente y selecciona el/los productos
                    que deseas y si todo esta correcto, aprueba.
                    De lo contrario, puedes rechazar y especificar el motivo.
                </p>
                <el-radio-group v-model="responseOptions" class="mt-10">
                    <el-radio-button label="Dibujar firma" />
                    <el-radio-button label="Subir imagen" />
                </el-radio-group>
                <!-- Dibujar -->
                <div v-show="responseOptions === 'Dibujar firma'" class="mt-4">
                    <CanvasDraw :saveDrawUrl="'quotes-store-signature'" :width="328" :height="200" :offsetX="23"
                        ref="canvasDraw" :offsetY="241" :itemId="quote.data.id" :approvedProducts="approvedProducts" />
                </div>
                <!-- Firma guardada -->
                <div v-show="responseOptions === 'Subir imagen'" class="mt-4">
                    <InputSignature :saveSignatureUrl="'quotes-store-signature'" :itemId="quote.data.id"
                        :approvedProducts="approvedProducts" ref="InputSignature" />
                </div>
                <div v-if="quote.data.rejected_razon" class="mt-3">
                    <p class="text-primary text-center bg-red-100 rounded-md">Rechazado</p>
                    <p class="text-sm mt-2">
                        <b>Motivo: &nbsp;</b>
                        <span> {{ quote.data.rejected_razon }} </span>
                    </p>
                </div>
            </div>
            <div class="drawer-footer">
                <button v-if="!quote.data.rejected_razon" @click="rejectQuoteModal = true" type="button"
                    data-overlay="#overlay-end-example" class="btn btn-soft bg-primary text-white hover:bg-primary">
                    Rechazar
                </button>
                <div class="tooltip">
                    <button @click="sendApproval" type="button"
                        class="btn btn-soft bg-green-700 text-white hover:bg-green-700"
                        :disabled="$refs.canvasDraw?.loading || $refs.InputSignature?.loading || (!$refs.canvasDraw?.lineas.length > 0 && !$refs.InputSignature?.signature)">
                        <span v-if="$refs.canvasDraw?.loading || $refs.InputSignature?.loading" class="loading loading-spinner"></span>
                        Aprobar
                    </button>
                    <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                        <span class="tooltip-body py-2">
                            Al hacer clic, se usará la firma registrada para aprobar la cotización.
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <Modal :show="rejectQuoteModal" @close="rejectQuoteModal = false" max-width="lg">
        <div class="p-5 relative">
            <h2 class="font-bold">Formulario de rechazo de la cotización</h2>
            <i @click="rejectQuoteModal = false"
                class="fa-solid fa-xmark cursor-pointer w-5 h-5 rounded-full border border-black flex items-center justify-center absolute right-3 top-3"></i>
            <p class="text-sm text-gray-600">Ayúdanos a mejorar nuestro servicio proporcionándonos comentarios sobre por
                qué
                estás rechazando la cotización</p>
            <form class="mt-5 mb-2" @submit.prevent="rejectQuote">
                <div class="mt-3">
                    <InputLabel value="Motivo de rechazo*" class="ml-3 mb-1" />
                    <el-input v-model="rejected_razon" :autosize="{ minRows: 3, maxRows: 5 }" type="textarea"
                        :maxlength="300" show-word-limit clearable />
                </div>
                <div class="flex justify-end space-x-2 mt-3">
                    <button @click="rejectQuoteModal = false; rejected_razon = null" type="button"
                        class="btn btn-soft btn-secondary" :disabled="loading">
                        Cancelar
                    </button>
                    <button class="btn btn-soft bg-primary text-white hover:bg-primary"
                        :disabled="loading">
                        <span v-if="loading" class="loading loading-spinner"></span>
                        Enviar
                    </button>
                </div>
            </form>
        </div>
    </Modal>
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
            currentImages: [], // Array to store current image index for each product
            rejected_razon: null,
            showSideOptions: false,
            rejectQuoteModal: false,
            loading: false,
            responseOptions: 'Dibujar firma',
            imagesUrl: [],
            approvedProducts: !!this.quote.data.quote_acepted ? this.quote.data.approved_products : this.quote.data.products.map(p => p.id),
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
    methods: {
        async rejectQuote() {
            this.loading = true;
            try {
                const response = await axios.put(route('quotes.reject', this.quote.data.id), { rejected_razon: this.rejected_razon });

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
        openImageInNewTab(url) {
            const newUrl = this.procesarUrlImagen(url);
            window.open(newUrl, '_blank');
        },
        calculateApprovedTotal() {
            return this.quote.data.products
                .filter(product => this.approvedProducts.includes(product.id))
                .reduce((total, product) => total + (product.pivot.quantity * product.pivot.price), 0);
        },
        scrollToOptions() {
            this.showSideOptions = true;
            this.$nextTick(() => {
                this.$refs.sideOptions.scrollIntoView({ behavior: 'smooth' });
            });
        },
        // Método para procesar la URL de la imagen
        procesarUrlImagen(originalUrl) {
            // Reemplaza la parte inicial de la URL
            const nuevaUrl = originalUrl?.replace('https://clientes-emblems3d.dtw.com.mx', 'http://www.intranetemblems3d.dtw.com.mx');
            // const nuevaUrl = originalUrl?.replace('http://localhost:8000', 'http://www.intranetemblems3d.dtw.com.mx'); // para hacer pruebas en local
            return nuevaUrl;
        },
        procesarUrlImagenLocal(originalUrl) {
            // Reemplaza la parte inicial de la URL
            const nuevaUrl = originalUrl.replace('https://clientes-emblems3d.dtw.com.mx', 'http://www.intranetemblems3d.dtw.com.mx');
            // const nuevaUrl = originalUrl?.replace('http://localhost:8000', 'https://clientes-emblems3d.dtw.com.mx');  // para hacer pruebas en local
            return nuevaUrl;
        },
    },
    mounted() {
        // Initialize currentImages array with default values for each product
        this.currentImages = this.quote.data.products.map(() => 0);
    },
}
</script>