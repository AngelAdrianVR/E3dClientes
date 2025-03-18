<template>
    <AppLayout title="Lista de Cotizaciones">
        <el-table :data="quotes" @row-click="handleRowClick" max-height="670" style="width: 90%"
            :row-class-name="tableRowClassName" class="mx-auto mt-10">
            <el-table-column prop="folio" label="Folio" width="130">
                <template #default="scope">
                    <span>{{'COT-' + String(scope.row.id).padStart(4, '0') }}</span>
                </template>
            </el-table-column>
            <el-table-column prop="authorized_at" label="Fecha de creación" />
            <el-table-column prop="responded_at" label="Fecha de respuesta" />
            <el-table-column label="Productos" width="210">
                <template v-slot="scope">
                    <span>{{scope.row.catalog_products.map(product => product.name).join(', ')}}</span>
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="Estatus" />
        </el-table>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';


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
        handleRowClick(row) {
            const url = this.route('quotes.show', row);
            window.open(url, '_blank');
        },
        tableRowClassName({ row, rowIndex }) {
            return 'cursor-pointer text-xs';
        },
    }
}
</script>