<template>
    <div class="form-group row">
        <label for="name" class="col-sm-3 col-form-label" v-text="label"></label>
        <input
            type="checkbox"
            v-bind:class="{'is-invalid' : isError()}"
            :checked="value"
            @input="updateValue"
        >

        <div v-for="error in errors" class="invalid-feedback">{{ error }}</div>
    </div>
</template>

<script>
    export default {
        props: [
            'label',
            'errors',
            'value'
        ],
        methods: {
            isError() {
                return typeof(this.errors) !== 'undefined' && this.errors.length > 0;
            },
            updateValue (e) {
                this.$emit('input', e.target.checked)
                this.$emit('change', e.target.checked)
            }
        }
    }
</script>
