<template>
  <div class="flex flex-col items-start gap-4">
    <label v-for="item in data" :key="item.id" class="custom-option grid grid-cols-3 gap-5 items-center relative p-0">
      <span class="w-full h-24">
        <img :src="item.media[0]?.original_url" class="bg-contain" alt="checkbox image" />
      </span>
      <input type="checkbox" class="checkbox checkbox-primary checkbox-sm absolute end-0 top-0 m-3 hidden checked:block hover:block" />
      
      <!-- Nombre -->
      <p class="font-bold">{{ item.name }}</p>

      <!-- Cantidad -->
      <div class="mr-2">
          <div class="input-group max-w-sm" data-input-number>
            <input class="input" id="number-input-label" type="text" value="0" data-input-number-input />
            <span class="input-group-text gap-3">
              <button type="button" class="btn btn-primary btn-soft size-[18px] md:size-[22px] rounded min-h-0 p-0" aria-label="Decrement button" data-input-number-decrement>
                <span class="icon-[tabler--minus] size-3.5 flex-shrink-0"></span>
              </button>
              <button type="button" class="btn btn-primary btn-soft size-[18px] md:size-[22px] rounded min-h-0 p-0" aria-label="Increment button" data-input-number-increment>
                <span class="icon-[tabler--plus] size-3.5 flex-shrink-0"></span>
              </button>
            </span>
          </div>
      </div>
    </label>
  </div>
</template>

<script>
export default {
props: {
data: Array
}
}
</script>
