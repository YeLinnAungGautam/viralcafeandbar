<script setup>
const props = defineProps({
  text: String,
  subText: String,
  subTextFollow: String,
  amount: [String, Number]
});
</script>

<template>
    <div class="bg-white rounded-lg p-5">
        <p class="font-semibold text-slate-550 text-xs mb-1.5">{{ text }}</p>
        <p class="font-bold text-slate-550 mb-2.5">
          {{ subText }}
          <small class="font-semibold">{{ subTextFollow }}</small>
        </p>
        <p class="font-bold text-2xl text-black">{{ amount }}</p>
    </div>
</template>
