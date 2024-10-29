<template>
    <div v-if="quotes?.length" class="w-full mx-auto text-sm mt-7">
        <div class="text-center text-base grid grid-cols-4 mb-3">
            <div class="font-bold pb-3 pl-2 text-left">Nombre</div>
            <div class="font-bold pb-3 text-left">Fecha de envío</div>
            <div class="font-bold pb-3 text-left">Fecha de respuesta</div>
            <div class="font-bold pb-3 text-left">Estado</div>
        </div>
        <div class="overflow-auto h-[300]">
            <div @click="$inertia.get(route('quotes.show', quote.id))" v-for="(quote, index) in quotes" :key="index" class="mb-2 grid grid-cols-4 border rounded-full items-center relative py-2 cursor-pointer hover:border-primary">
                <div class="grid grid-cols-2 items-center">
                    <img class="mx-auto w-5 object-contain" src="@/../../public/images/pdf.png" alt="">
                    <p class="font-bold">{{ quote.folio }}</p>
                </div>
                <div class="">{{ quote.authorized_at }}</div>
                <div class="">{{ quote.responded_at ?? '--' }}</div>
                <div :class="quote.status.color" class="flex items-center space-x-2"><p>{{ quote.status.label }}</p><span v-html="quote.status.icon"></span></div>
            </div>
        </div>
    </div>

    <el-empty v-else :image-size="200" description="No hay cotizaciónes registradas" />
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
        quotes: Object
    },
    methods:{

    }
}
</script>