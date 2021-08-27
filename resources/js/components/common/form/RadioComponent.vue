<template>
    <div class="form-group">
        <label for="name" class="col-sm-3 col-form-label" v-text="label" v-if="label"></label>
        <input
            type="radio"
            :name="name"
            v-bind:class="{'is-invalid' : isError()}"
            :value="radio_value"
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
            'value',
            'radio_value',
            'name'
        ],
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
