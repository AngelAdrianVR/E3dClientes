<template>
    <AppLayout title="Lista de Cotizaciones">
        <h1 class="font-bold lg:text-2xl mx-2 lg:mx-20 mt-5  motion-preset-blur-left motion-delay-[100ms] bg-gradient-to-r from-secondary to-error bg-clip-text text-transparent w-fit">
            Lista de cotizaciones
        </h1>
        <el-table :data="quotes" @row-click="handleRowClick" max-height="670" style="width: 90%"
            :row-class-name="tableRowClassName" class="mx-auto mt-10">
            <el-table-column prop="folio" label="Folio" width="130">
                <template #default="scope">
                    <div class="flex items-center space-x-2">
                        <el-tooltip v-if="scope.row.early_payment_discount" placement="top">
                            <template #content>
                                <p v-if="scope.row.early_paid_at">
                                    Descuento de pago por anticipado aplicado: %{{ scope.row.discount }}
                                </p>
                                <p v-else>
                                    Descuento de pago por anticipado: %{{ scope.row.discount }}
                                </p>
                            </template>
                            <svg v-if="scope.row.early_paid_at" width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.15527 0C9.57271 0.953908 11.3524 2.23003 12.2705 7.08301C12.6323 7.25786 13.0912 6.56184 13.9756 5.11523C18.2995 13.5451 15.0251 18.2319 10.4346 18.3633H5.97461C2.30572 18.3633 -0.842483 14.5589 0.203125 8.52539C1.11282 9.8512 1.48847 9.99154 2.04004 9.70605C0.85957 5.11534 8.598 4.06604 7.15527 0ZM10.5732 6.05664C10.3949 5.99143 10.1997 6.06337 10.1035 6.21875L10.0684 6.29102C9.30024 8.39053 8.70097 10.0296 8.10156 11.668L6.13379 17.0459C6.05912 17.25 6.16407 17.4761 6.36816 17.5508C6.54675 17.616 6.74192 17.5436 6.83789 17.3877L6.87305 17.3164C7.64129 15.2165 8.24131 13.5771 8.84082 11.9385L10.8076 6.56055C10.8817 6.35675 10.7769 6.13129 10.5732 6.05664ZM10.7002 12.0654C9.68635 12.0656 8.86447 12.8885 8.86426 13.9023C8.86438 14.9163 9.6863 15.7381 10.7002 15.7383C11.7141 15.7381 12.537 14.9163 12.5371 13.9023C12.5369 12.8885 11.714 12.0656 10.7002 12.0654ZM10.7002 12.8525C11.2794 12.8528 11.7498 13.3231 11.75 13.9023C11.7499 14.4816 11.2795 14.951 10.7002 14.9512C10.1209 14.951 9.65149 14.4816 9.65137 13.9023C9.65158 13.3231 10.121 12.8528 10.7002 12.8525ZM6.50293 7.60645C5.48915 7.60667 4.6673 8.42863 4.66699 9.44238C4.66699 10.4564 5.48897 11.2791 6.50293 11.2793C7.5169 11.2791 8.33984 10.4564 8.33984 9.44238C8.33954 8.42862 7.51671 7.60666 6.50293 7.60645ZM6.50293 8.39355C7.08207 8.39377 7.55243 8.86326 7.55273 9.44238C7.55273 10.0218 7.08226 10.492 6.50293 10.4922C5.9236 10.492 5.4541 10.0218 5.4541 9.44238C5.45441 8.86326 5.92379 8.39378 6.50293 8.39355Z" fill="#22c55e"/>
                            </svg>
                            <svg v-else width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.15527 0C9.57271 0.953908 11.3524 2.23003 12.2705 7.08301C12.6323 7.25786 13.0912 6.56184 13.9756 5.11523C18.2995 13.5451 15.0251 18.2319 10.4346 18.3633H5.97461C2.30572 18.3633 -0.842483 14.5589 0.203125 8.52539C1.11282 9.8512 1.48847 9.99154 2.04004 9.70605C0.85957 5.11534 8.598 4.06604 7.15527 0ZM10.5732 6.05664C10.3949 5.99143 10.1997 6.06337 10.1035 6.21875L10.0684 6.29102C9.30024 8.39053 8.70097 10.0296 8.10156 11.668L6.13379 17.0459C6.05912 17.25 6.16407 17.4761 6.36816 17.5508C6.54675 17.616 6.74192 17.5436 6.83789 17.3877L6.87305 17.3164C7.64129 15.2165 8.24131 13.5771 8.84082 11.9385L10.8076 6.56055C10.8817 6.35675 10.7769 6.13129 10.5732 6.05664ZM10.7002 12.0654C9.68635 12.0656 8.86447 12.8885 8.86426 13.9023C8.86438 14.9163 9.6863 15.7381 10.7002 15.7383C11.7141 15.7381 12.537 14.9163 12.5371 13.9023C12.5369 12.8885 11.714 12.0656 10.7002 12.0654ZM10.7002 12.8525C11.2794 12.8528 11.7498 13.3231 11.75 13.9023C11.7499 14.4816 11.2795 14.951 10.7002 14.9512C10.1209 14.951 9.65149 14.4816 9.65137 13.9023C9.65158 13.3231 10.121 12.8528 10.7002 12.8525ZM6.50293 7.60645C5.48915 7.60667 4.6673 8.42863 4.66699 9.44238C4.66699 10.4564 5.48897 11.2791 6.50293 11.2793C7.5169 11.2791 8.33984 10.4564 8.33984 9.44238C8.33954 8.42862 7.51671 7.60666 6.50293 7.60645ZM6.50293 8.39355C7.08207 8.39377 7.55243 8.86326 7.55273 9.44238C7.55273 10.0218 7.08226 10.492 6.50293 10.4922C5.9236 10.492 5.4541 10.0218 5.4541 9.44238C5.45441 8.86326 5.92379 8.39378 6.50293 8.39355Z" fill="#BC0B0B"/>
                            </svg>
                        </el-tooltip>
                        <span>{{'COT-' + String(scope.row.id).padStart(4, '0') }}</span>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="authorized_at" label="Fecha de creación">
                <template #default="scope">
                    <span>{{ formatDate(scope.row.authorized_at) }}</span>
                </template>
            </el-table-column>
            <el-table-column prop="responded_at" label="Fecha de respuesta">
                <template #default="scope">
                    <span>{{ formatDate(scope.row.responded_at) ?? 'No has dado respuesta' }}</span>
                </template>
            </el-table-column>
            <el-table-column label="Productos" width="300">
                <template v-slot="scope">
                    <p v-for="(p, index) in scope.row.catalog_products.map(product => product.name)" :key="index">
                        • {{ p }}
                    </p>
                </template>
            </el-table-column>
            <el-table-column label="Estatus">
                <template #default="scope">
                    <span :class="getStatus(scope.row).color" v-html="getStatus(scope.row).icon"></span>
                    <span :class="getStatus(scope.row).color">{{ getStatus(scope.row).label }}</span>
                </template>
            </el-table-column>
        </el-table>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { format } from 'date-fns';

export default {
    data() {
        return {
        }
    },
    components: {
        AppLayout,
    },
    props: {
        quotes: Array,
    },
    methods: {
        formatDate(date) {
            return date ? format(new Date(date), 'dd MMM, yyyy') : null;
        },
        handleRowClick(row) {
            const url = this.route('quotes.show', row);
            window.open(url, '_blank');
        },
        tableRowClassName({ row, rowIndex }) {
            return 'cursor-pointer text-xs';
        },
        getStatus(row) {
            let status = {
                label: 'Pendiente',
                color: 'text-amber-500',
                icon: '<i class="fa-regular fa-clock mr-1"></i>',
            };

            if (row.quote_acepted === 1) {
                status = {
                    label: 'Autorizado',
                    color: 'text-green-500',
                    icon: '<i class="fa-solid fa-check mr-1"></i>',
                };
            } else if (row.quote_acepted === 0) {
                status = {
                    label: 'Rechazado',
                    color: 'text-red-500',
                    icon: '<i class="fa-solid fa-xmark mr-1"></i>',
                };
            }

            return status;
        },
    }
}
</script>