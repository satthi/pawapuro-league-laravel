<template>
    <div>
        <div v-for="i in 7" v-bind:class="{'block selected': i <= value, 'block unselected': i > value}"></div>
    </div>
</template>
<style scoped>
    .block {
        width: 15px;
        height :10px;
        border: 1px solid #000;
        background-color: #FFF;
    }

    .selected {
    background-color: orange;
    }
    .unselected {
    background-color: #FFF;
    }
</style>

<script>
    export default {
        props: ['value'],
    }
</script>
