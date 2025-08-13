<!-- use  balafon --vue3:sfc-compile-file .test/vue3/compile/app.vue -->
<template>
    <div class="app">
    App vue to compile  {{ this.x }}
    </div>
</template>
<script setup>
const { h, ref } = Vue;
const x = ref(8);
</script>

<script>

const { createApp } = Vue;
const x = 9;
const y = 9;
export default {
    mounted() {
     console.log('mounted definition');   
    }
}
</script>

<style>
.app{
    background-color:red;
}
</style>
