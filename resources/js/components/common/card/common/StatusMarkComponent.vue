<template>
    <span>
        <span class="status_value status_value_normal" v-if="value < 40">
            Ⓕ
        </span>
        <span class="status_value status_value_over_40" v-if="value >= 40 && value < 50">
            Ⓔ
        </span>
        <span class="status_value status_value_over_50" v-if="value >= 50 && value < 60">
            Ⓓ
        </span>
        <span class="status_value status_value_over_60" v-if="value >= 60 && value < 70">
            Ⓒ
        </span>
        <span class="status_value status_value_over_70" v-if="value >= 70 && value < 80">
            Ⓑ
        </span>
        <span class="status_value status_value_over_80" v-if="value >= 80 && value < 90">
            Ⓐ
        </span>
        <span class="status_value status_value_over_90" v-if="value >= 90">
            Ⓢ
        </span>
    </span>
</template>
<style scoped>

    .status_value_normal {
        color: white;
    }

    .status_value_over_40 {
        color: purple;
    }
    .status_value_over_50 {
        color: cyan;
    }
    .status_value_over_60 {
        color: lime;
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
        props: ['value'],
    }
</script>
