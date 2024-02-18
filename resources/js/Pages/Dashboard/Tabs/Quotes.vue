<template>
    <div v-if="true" class="w-full mx-auto text-sm mt-7">
        <div class="text-center text-base grid grid-cols-5 mb-3">
            <div class="font-bold pb-3 pl-2 text-left col-span-2">Nombre</div>
            <div class="font-bold pb-3 text-left">Fecha de envío</div>
            <div class="font-bold pb-3 text-left">Fecha de respuesta</div>
            <div class="font-bold pb-3 text-left">Estado</div>
        </div>
        <div class="overflow-auto h-[300]">
            <div v-for="(sale, index) in saleProducts" :key="index" class="mb-2 grid grid-cols-5 border rounded-full items-center relative">
                <!-- <div class="grid grid-cols-2 items-center col-span-2">
                    <img class="mx-auto w-16 object-contain" :src="sale.product.imageCover[0]?.original_url" alt="">
                    <p class="font-bold">{{ sale.product.name }}</p>
                </div>
                <div class="w-24">${{ sale.product.public_price }}</div>
                <div>
                    <el-input-number v-model="sale.quantity" :min="1" :max="sale.product.current_stock" :precision="2" />
                </div>
                <div class="text-[#5FCB1F] font-bold">${{ sale.product.public_price * sale.quantity }}</div>
                <div class="absolute right-5">
                    <el-popconfirm confirm-button-text="Si" cancel-button-text="No" icon-color="#C30303" title="¿Continuar?" @confirm="deleteItem(sale.product.id)">
                        <template #reference>
                            <i class="fa-regular fa-trash-can mr-2 text-primary cursor-pointer p-2 hover:bg-gray-100 rounded-full"></i>
                        </template>
                    </el-popconfirm>
                </div> -->
            </div>
        </div>
    </div>
    <p class="text-center text-gray-500 text-sm mt-14" v-if="false">No hay cotizaciones registradas</p>
</template>

<script>

export default {
    data() {
        return {
        }
    },
    components:{
    },
    props:{

    },
    methods:{

    }
}
</script>