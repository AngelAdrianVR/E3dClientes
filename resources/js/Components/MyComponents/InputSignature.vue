<template>
  <div class="inline">
    <figure @click="triggerImageInput"
      class="flex flex-col items-center justify-center rounded-lg border border-[#9A9A9A] w-[328px] h-52 cursor-pointer relative bg-white">
      <i v-if="signature && canDelete" @click.stop="clearImage" class="fa-solid fa-xmark absolute p-1 top-1 right-1 z-10 text-sm"></i>
      <div class="flex items-center">
        <p v-if="!signature" class="text-xs text-gray-400">Sube una imagen</p>
        <i v-if="!signature" class="fa-solid fa-cloud-arrow-up text-xs text-gray-400 ml-2"></i>
      </div>
      <!-- <p v-if="!signature" class="text-xs text-gray-400 text-center mt-3">Esta se guardará y la podrás utilizar para firmar próximos documentos</p> -->
      <img v-if="signature" :src="signature" :alt="alt" class="w-full h-full object-contain bg-no-repeat rounded-md opacity-50" />
      <input ref="fileInput" type="file" @change="handleImageUpload" class="hidden" />
    </figure>
    <div class="flex justify-end mt-2 space-x-1">
        <el-popconfirm confirm-button-text="Si" cancel-button-text="No" icon-color="#C30303" title="Al agregar la firma se mandará la aprobación de los productos seleccionados ¿Continuar?"
            @confirm="saveImageAsSignature">
            <template #reference>
                <PrimaryButton :disabled="!signature || loading">
                  <i v-if="loading" class="fa-solid fa-circle-notch fa-spin mr-2"></i>
                  Agregar
                </PrimaryButton>
            </template>
        </el-popconfirm>
    </div>
  </div>
</template>

<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';

export default {
  data() {
    return {
      signature: null,
      formData: {
        file: null,
      },
      loading: false,
    };
  },
  components:{
    PrimaryButton
  },
  props: {
    alt: {
      type: String,
      default: "Vista previa no disponible",
    },
    canDelete: {
      type: Boolean,
      default: true,
    },
    imageUrl: {
      type: String,
      default: null,
    },
    saveSignatureUrl: {
      type: String,
      default: null 
    },
    approvedProducts: {
      type: Array,
      default: [] 
    },
    itemId: Number
  },
  watch: {
    imageUrl: {
      immediate: true,
      handler(newImageUrl) {
        this.signature = newImageUrl;
      },
    },
  },
  emits: ['imagen', 'cleared'],
  methods: {
    triggerImageInput() {
      this.$refs.fileInput.click();
    },
    handleImageUpload(event) {
      const file = event.target.files[0];
      this.formData.file = file;

      if (file) {
        const imageURL = URL.createObjectURL(file);
        this.signature = imageURL;
        // Emitir evento al componente padre con la imagen
        this.$emit("imagen", file);
      }

    },
    clearImage() {
      this.signature = null;
      this.formData.file = null;
      this.$emit("cleared");
    },
    saveImageAsSignature() {
      this.loading = true;
      const formData = new FormData();
      formData.append('signature', this.formData.file);
      formData.append('approvedProducts', this.approvedProducts);
      
      axios.post(`/${this.saveSignatureUrl}/${this.itemId}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
      .then((response) => {
        console.log(response.data);
        this.$notify({
          title: 'Éxito',
          message: 'Se ha agregado tu firma',
          type: 'success',
        });
        location.reload();
      })
      .catch((error) => {
        console.error(error);
        this.$notify({
          title: 'Error',
          message: 'No se pudo guardar tu firma. Refresca la página e intenta de nuevo',
          type: 'error',
        });
      });
    },
  },
};
</script>

