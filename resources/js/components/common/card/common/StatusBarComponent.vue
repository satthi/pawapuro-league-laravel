<template>
    <div>
        <div class="status_label">
            {{ label }}
        </div>
        <div class="status_waku">
        </div>
        <div class="status_value_bar" v-bind:style="{width: `${value * 2}px`}">
        </div>
        <div class="status_value status_value_normal" v-if="value < 70">
            {{ value }}
        </div>
        <div class="status_value status_value_over_70" v-if="value >= 70 && value < 80">
            {{ value }}
        </div>
        <div class="status_value status_value_over_80" v-if="value >= 80 && value < 90">
            {{ value }}
        </div>
        <div class="status_value status_value_over_90" v-if="value >= 90">
            {{ value }}
        </div>
    </div>
</template>
<style scoped>
    .status_label {
        width: 75px;
        height: 20px;
        background-color: white;
        text-align: center;
        position: absolute;
        left: 0px;
    }
    .status_waku {
        width: 240px;
        height: 20px;
        text-align: center;
        position: absolute;
        background-color: gray;
        left: 75px;
    }
    .status_value_bar {
        width: 200px;
        height: 10px;
        background-color: white;
        text-align: center;
        position: absolute;
        top: 5px;
        left: 80px;
    }
    .status_value {
        width: 60px;
        height: 20px;
        text-align: center;
        position: absolute;
        background-color: gray;
        left: 300px;
    }

    .status_value_normal {
        color: white;
    }

    .status_value_over_70 {
        color: yellow;
    }
    .status_value_over_80 {
        color: orange;
    }
    .status_value_over_90 {
        color: red;
    }
</style>

<script>
    export default {
        props: ['label', 'value'],
    }
</script>
