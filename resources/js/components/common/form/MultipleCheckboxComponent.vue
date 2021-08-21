<template>
    <div class="form-group row">
        <label for="name" class="col-sm-3 col-form-label" v-text="label"></label>
        <input
            type="checkbox"
            :value="dataid"
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
            'dataid'
        ],
        methods: {
            updateValue (e) {
                if (e.target.checked) {
                    var new_value = this.value;
                    // チェックした時
                    new_value.push(e.target.value)
                } else {
                    // チェックを外した時
                    var new_value = this.value.filter(item=> item != e.target.value);
                }
                this.$emit('input', new_value)
                this.$emit('change', new_value)
            }
        }
    }
</script>
