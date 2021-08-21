<template>
    <div class="form-group row">
        <label for="name" class="col-sm-3 col-form-label" v-text="label"></label>
        <select
            class="col-sm-9 form-control"
            v-bind:class="{'is-invalid' : isError()}"
            :value="value"
            @change="updateValue"
        >
            <option v-if="empty" v-bind:value=null>未選択</option>
            <option v-for="option in options" v-bind:value="option.value">
                {{ option.text }}
            </option>
        </select>

        <div v-for="error in errors" class="invalid-feedback">{{ error }}</div>
    </div>
</template>

<script>
    export default {
        props: ['label', 'errors', 'value', 'options', 'empty'],
        methods: {
            isError() {
                return typeof(this.errors) !== 'undefined' && this.errors.length > 0;
            },
            updateValue (e) {
                this.$emit('input', e.target.value)
                this.$emit('change', e.target.value)
            }
        }
    }
</script>
