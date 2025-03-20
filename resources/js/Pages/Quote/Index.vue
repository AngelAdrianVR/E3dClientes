<template>
    <AppLayout title="Lista de Cotizaciones">
        <el-table :data="quotes" @row-click="handleRowClick" max-height="670" style="width: 90%"
            :row-class-name="tableRowClassName" class="mx-auto mt-10">
            <el-table-column prop="folio" label="Folio" width="130">
                <template #default="scope">
                    <span>{{'COT-' + String(scope.row.id).padStart(4, '0') }}</span>
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