<script setup>
import { ref, watch, defineEmits } from "vue";

const props = defineProps({
  data: Array, // Lista de productos sugeridos
});

const emit = defineEmits(["update:selectedProducts"]);
const selectedProducts = ref([]); // Almacena productos seleccionados

const toggleSelection = (product) => {
  const index = selectedProducts.value.findIndex((p) => p.id === product.id);

  if (index !== -1) {
    selectedProducts.value.splice(index, 1); // Si ya está seleccionado, se quita
  } else {
    selectedProducts.value.push({ id: product.id, name: product.name, quantity: 0 }); // Se añade con cantidad 0
  }
};

const updateQuantity = (product, quantity) => {
  const selected = selectedProducts.value.find((p) => p.id === product.id);
  if (selected) {
    selected.quantity = parseInt(quantity) || 0;
  }
};

// Detectar cambios y enviarlos al padre
watch(selectedProducts, (newValue) => {
  emit("update:selectedProducts", newValue);
}, { deep: true });
</script>

<template>
  <div class="flex flex-col items-start gap-4">
    <label v-for="item in data" :key="item.id" class="custom-option grid grid-cols-3 gap-3 items-center relative p-0">
      <span class="w-full h-24">
        <img :src="item.media[0]?.original_url" class="bg-contain" alt="checkbox image" />
      </span>

      <!-- Checkbox de selección -->
      <input
        type="checkbox"
        class="checkbox checkbox-primary checkbox-sm absolute end-0 top-0 m-3"
        :checked="selectedProducts.some((p) => p.id === item.id)"
        @change="toggleSelection(item)"
      />

      <!-- Nombre -->
      <p class="font-bold">{{ item.name }}</p>

      <!-- Cantidad -->
      <div class="mr-2">
        <div class="input-group max-w-sm" data-input-number>
          <input
            class="input"
            type="number"
            :value="selectedProducts.find((p) => p.id === item.id)?.quantity || 0"
            :disabled="!selectedProducts.some((p) => p.id === item.id)"
            @input="updateQuantity(item, $event.target.value)"
            min="0"
          />
        </div>
      </div>
    </label>
  </div>
</template>
