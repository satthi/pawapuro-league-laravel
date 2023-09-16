<template>
    <div>
        <div v-bind:class="{'block selected': value > 0, 'block unselected': value <= 0}"></div>
        <div v-bind:class="{'block selected': value >= 40, 'block unselected': value < 40}"></div>
        <div v-bind:class="{'block selected': value >= 50, 'block unselected': value < 50}"></div>
        <div v-bind:class="{'block selected': value >= 60, 'block unselected': value < 60}"></div>
        <div v-bind:class="{'block selected': value >= 70, 'block unselected': value < 70}"></div>
        <div v-bind:class="{'block selected': value >= 80, 'block unselected': value < 80}"></div>
        <div v-bind:class="{'block selected': value >= 90, 'block unselected': value < 90}"></div>
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
