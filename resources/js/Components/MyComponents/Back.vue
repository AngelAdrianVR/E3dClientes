<template>
  <button
    @click="goBack"
    class="flex items-center px-3 py-2 transition duration-500 hover:-translate-x-1 rounded-lg"
  >
    <i class="fa-solid fa-arrow-left text-lg"></i>
  </button>
</template>

<script>
export default {
  name: "BackButton",
  methods: {
    goBack() {
      if (window.history.length > 1) {
        this.$router ? this.$router.go(-1) : window.history.back();
      } else {
        this.$emit("defaultRoute");
      }
    },
  },
};
</script>
