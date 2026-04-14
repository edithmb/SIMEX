<script setup>
defineProps({
  size:   { type: Number, default: 16 },
  stroke: { type: Number, default: 4 },
})
</script>

<template>
  <svg
    class="spinner"
    :width="size"
    :height="size"
    viewBox="0 0 50 50"
    role="status"
    aria-label="Cargando"
  >
    <circle
      class="spinner-path"
      cx="25"
      cy="25"
      r="20"
      fill="none"
      :stroke-width="stroke"
    />
  </svg>
</template>

<style scoped>
.spinner {
  animation: spinner-rotate 1s linear infinite;
  display: inline-block;
  vertical-align: middle;
}

.spinner-path {
  stroke: currentColor;
  stroke-linecap: round;
  animation: spinner-dash 1.5s ease-in-out infinite;
}

@keyframes spinner-rotate {
  100% { transform: rotate(360deg); }
}

@keyframes spinner-dash {
  0%   { stroke-dasharray: 1, 200;  stroke-dashoffset: 0; }
  50%  { stroke-dasharray: 90, 200; stroke-dashoffset: -35; }
  100% { stroke-dasharray: 90, 200; stroke-dashoffset: -124; }
}
</style>
