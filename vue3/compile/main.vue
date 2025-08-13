<template>
    <div class="block"> Hello Application {{  this.t }} </div>    
</template>
<script setup>
    const t = 'this is t'
</script>
<style>
    .block{
        background-color:red;
    }
</style>