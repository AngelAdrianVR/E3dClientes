<template>
  <canvas :class="'w-' + width + ' h-' + height" ref="canvas"></canvas>
  <div class="flex justify-between mt-3">
    <button class="text-secondary text-sm" @click="limpiarCanvas"><i class="fa-solid fa-broom mr-1 ml-2"></i>Limpiar</button>
    <div class="flex space-x-1">
        <SecondaryButton :disabled="!lineas.length > 0" @click="guardarComoImagen">Guardar</SecondaryButton>
        <el-popconfirm confirm-button-text="Si" cancel-button-text="No" icon-color="#C30303" title="¿Continuar?"
            @confirm="guardarComoObjetoImagen">
            <template #reference>
                <PrimaryButton :disabled="!lineas.length > 0">Agregar</PrimaryButton>
            </template>
        </el-popconfirm>
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
    };
  },
  components:{
    PrimaryButton,
    SecondaryButton
  },
  props:{
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
  },
  mounted() {
    this.inicializarCanvas();
    this.agregarEventos();
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
        ctx.strokeStyle = '#000';

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
    guardarComoObjetoImagen() {
      let canvas = this.miCanvas;
      canvas.toBlob(
        (blob) => {
          this.signature = new File([blob], 'dibujo.png', { type: 'image/png' });
          // mandar el objeto imagen al servidor
          this.enviarImagenAlServidor();
        },
        'image/png'
      );
    },
    enviarImagenAlServidor() {
      let formData = new FormData();
      formData.append('signature', this.signature);

      axios.post(`/${this.saveDrawUrl}/${this.itemId}`, formData, {
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
    agregarEventos() {
      this.miCanvas.addEventListener('mousedown', this.empezarDibujo, false);
      this.miCanvas.addEventListener('mousemove', this.dibujarLinea, false);
      this.miCanvas.addEventListener('mouseup', this.pararDibujar, false);

      this.miCanvas.addEventListener('touchstart', this.empezarDibujo, false);
      this.miCanvas.addEventListener('touchmove', this.dibujarLinea, false);
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
