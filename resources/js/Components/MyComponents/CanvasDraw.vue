<template>
  <canvas class="dark:bg-[#1F1F1F]" :class="'w-' + width + ' h-' + height" ref="canvas"></canvas>
  <div v-if="lineas.length > 0" class="flex items-center justify-between mt-3">
    <button class="text-secondary dark:text-blue-400 text-xs" @click="limpiarCanvas">
      <i class="fa-solid fa-broom mr-1 ml-2"></i>
      Limpiar
    </button>
    <button class="text-xs text-green-600 ml-2" @click="guardarFirmaServidor">
      <i class="fa-solid fa-cloud-upload-alt mr-1"></i>
      Guardar firma
    </button>
    <button class="flex items-center space-x-1 text-xs text-secondary dark:text-blue-400 mr-2" @click="guardarComoImagen">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="size-4">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
      </svg>
      <span>Descargar como imagen</span>
    </button>
  </div>

  <div v-if="signatures.length">
    <h3 class="text-sm font-semibold mb-2 mt-5">Firmas guardadas</h3>
    <div class="flex flex-wrap gap-2">
      <div 
        v-for="firma in signatures" 
        :key="firma.id" 
        class="relative border p-2 rounded hover:shadow"
      >
        <!-- Botón de eliminar -->
        <button 
          class="absolute top-0 right-0 text-red-500 font-bold p-1"
          @click.stop="eliminarFirma(firma.id)"
        >
          ×
        </button>

        <!-- Imagen de firma -->
        <div 
          class="cursor-pointer"
          @click="UseSignature(procesarUrlImagen('/storage/' + firma.path))"
        >
          <img 
            :src="procesarUrlImagen('/storage/' + firma.path)" 
            alt="Firma guardada" 
            class="w-32 h-auto"
          />
        </div>
      </div>
    </div>
  </div>


</template>

<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

export default {
  data() {
    return {
      miCanvas: null,
      signature: null,
      lineas: [],
      correccionX: 0,
      correccionY: 0,
      pintarLinea: false,
      nuevaPosicionX: 0,
      nuevaPosicionY: 0,
      loading: false,
      signatures: [],
    };
  },
  components: {
    PrimaryButton,
    SecondaryButton
  },
  props: {
    itemId: Number,
    offsetX: {
      type: Number,
      default: 0
    },
    offsetY: {
      type: Number,
      default: 0
    },
    width: {
      type: Number,
      default: 400
    },
    height: {
      type: Number,
      default: 200
    },
    saveDrawUrl: {
      type: String,
      default: null
    },
    approvedProducts: {
      type: Array,
      default: []
    },
  },
  mounted() {
    //retardo de 1 segundo para montar el canvas cuando se muestra en el DOM
    setTimeout(() => {
      this.inicializarCanvas();
      this.agregarEventos();
    }, 500); // 1000 milisegundos = 1 segundo

    this.fetchSignatures();
  },
  methods: {
    inicializarCanvas() {
      this.miCanvas = this.$refs.canvas;
      this.correccionX = this.miCanvas.getBoundingClientRect().x;
      this.correccionY = this.miCanvas.getBoundingClientRect().y;

      this.miCanvas.width = this.width;
      this.miCanvas.height = this.height;
    },
    empezarDibujo() {
      this.pintarLinea = true;
      this.lineas.push([]);
    },
    guardarLinea() {
      this.lineas[this.lineas.length - 1].push({
        x: this.nuevaPosicionX,
        y: this.nuevaPosicionY,
      });
    },
    dibujarLinea(event) {
      event.preventDefault();
      if (this.pintarLinea) {
        let ctx = this.miCanvas.getContext('2d');
        ctx.lineJoin = ctx.lineCap = 'round';
        ctx.lineWidth = 2;
        // Detectar si está activo el modo oscuro
        const isDarkMode = document.documentElement.classList.contains('dark');

        // Cambiar el color de la línea según el modo
        ctx.strokeStyle = isDarkMode ? '#f9fafb' : '#000000';

        if (event.changedTouches === undefined) {
          this.nuevaPosicionX = event.layerX - this.offsetX; // 7 offset para dibujar en la coordenada correcta
          this.nuevaPosicionY = event.layerY - this.offsetY; // 160 offset para dibujar en la coordenada correcta
        } else {
          this.nuevaPosicionX =
            event.changedTouches[0].pageX - this.correccionX;
          this.nuevaPosicionY =
            event.changedTouches[0].pageY - this.correccionY;
        }

        this.guardarLinea();

        ctx.beginPath();
        this.lineas.forEach(function (segmento) {
          ctx.moveTo(segmento[0].x, segmento[0].y);
          segmento.forEach(function (punto) {
            ctx.lineTo(punto.x, punto.y);
          });
        });
        ctx.stroke();
      }
    },
    pararDibujar() {
      this.pintarLinea = false;
      this.guardarLinea();
    },
    limpiarCanvas() {
      let ctx = this.miCanvas.getContext('2d');
      ctx.clearRect(0, 0, this.miCanvas.width, this.miCanvas.height);
      this.lineas = [];
    },
    guardarComoImagen() {
      let enlace = document.createElement('a');
      enlace.href = this.miCanvas.toDataURL('image/png');
      enlace.download = 'dibujo.png';
      enlace.click();
    },
    // guardarComoObjetoImagen() {
    //   let canvas = this.miCanvas;
    //   canvas.toBlob(
    //     (blob) => {
    //       this.signature = new File([blob], 'dibujo.png', { type: 'image/png' });
    //       // mandar el objeto imagen al servidor
    //       this.enviarImagenAlServidor();
    //     },
    //     'image/png'
    //   );
    // },
    // enviarImagenAlServidor() {
    //   this.loading = true;
    //   let formData = new FormData();
    //   formData.append('signature', this.signature);
    //   formData.append('approvedProducts', this.approvedProducts);

    //   axios.post(`/${this.saveDrawUrl}/${this.itemId}`, formData, {
    //     headers: {
    //       'Content-Type': 'multipart/form-data',
    //     },
    //   })
    //     .then((response) => {
    //       console.log(response.data);
    //       location.reload();
    //     })
    //     .catch((error) => {
    //       console.error(error);
    //       this.$notify({
    //         title: 'Error',
    //         message: 'No se pudo guardar tu firma. Refresca la página e intenta de nuevo',
    //         type: 'error',
    //       });
    //     });
    // },
    guardarFirmaServidor() {
      const dataUrl = this.miCanvas.toDataURL('image/png');
      const blob = this.dataURLtoBlob(dataUrl);

      const formData = new FormData();
      formData.append('firma', blob, 'firma.png');
      formData.append('quote_id', this.itemId); // opcional

      axios.post(route('signatures.save-sign'), formData)
        .then(response => {
          this.$notify({
            title: 'Éxito',
            message: 'Firma guardada correctamente',
            type: 'success',
          });
          this.limpiarCanvas();
          this.fetchSignatures(); // Actualiza la lista de firmas guardadas
          console.log(response.data); // puedes guardar el URL si lo necesitas
        })
        .catch(error => {
          console.error('Error al guardar la firma', error);
          alert('Ocurrió un error al guardar la firma');
        });
    },
    dataURLtoBlob(dataURL) {
      const parts = dataURL.split(';base64,');
      const contentType = parts[0].split(':')[1];
      const byteCharacters = atob(parts[1]);
      const byteArrays = [];

      for (let i = 0; i < byteCharacters.length; i++) {
        byteArrays.push(byteCharacters.charCodeAt(i));
      }

      return new Blob([new Uint8Array(byteArrays)], { type: contentType });
    },
    agregarEventos() {
      this.miCanvas.addEventListener('mousedown', this.empezarDibujo, false);
      this.miCanvas.addEventListener('mousemove', this.dibujarLinea, false);
      this.miCanvas.addEventListener('mouseup', this.pararDibujar, false);

      this.miCanvas.addEventListener('touchstart', this.empezarDibujo, { passive: false });
      this.miCanvas.addEventListener('touchmove', this.dibujarLinea, { passive: false });
      this.miCanvas.addEventListener('touchend', this.pararDibujar, false);
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
        // const nuevaUrl = originalUrl.replace('https://clientes-emblems3d.dtw.com.mx', 'http://www.intranetemblems3d.dtw.com.mx');
        const nuevaUrl = originalUrl.replace('http://localhost:8000', 'https://clientes-emblems3d.dtw.com.mx'); //para hacer pruebas en local
        return nuevaUrl;
    },
    // Método para eliminar una firma guardada
    eliminarFirma(id) {
      if (!confirm('¿Estás seguro de eliminar esta firma?')) return;

      axios.delete(route('signatures.delete-sign', id))
        .then(() => {
          this.signatures = this.signatures.filter(f => f.id !== id);
        })
        .catch(error => {
          console.error('Error al eliminar la firma:', error);
          alert('No se pudo eliminar la firma');
        });
    },
    // Método para usar una firma guardada
    UseSignature(path) {
      console.log(path);
      const canvas = this.miCanvas;
      const ctx = canvas.getContext('2d');
      const img = new Image();

      img.onload = () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
      };

      img.src = path;
    },
    async fetchSignatures() {
      try {
        const response = await axios.get(route('signatures.get-signatures'));
        if ( response.status === 200 ) {
          this.signatures = response.data;
        }
      } catch (error) {
        console.error('Error al cargar las firmas guardadas:', error);
      }
    },
  },
};
</script>

<style scoped>
canvas {
  background-color: #fff;
  border: 1px solid #9a9a9a;
  border-radius: 10px;
}
</style>
