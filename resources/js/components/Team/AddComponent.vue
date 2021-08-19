<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit">
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">チーム名</label>
                        <input type="text" class="col-sm-9 form-control" v-bind:class="{'is-invalid' : isError('name')}" id="name" v-model="team.name">
                        <div v-for="error in errors.name" class="invalid-feedback">{{ error }}</div>
                    </div>
                    <div class="form-group row">
                        <label for="ryaku_name" class="col-sm-3 col-form-label">略称</label>
                        <input type="text" class="col-sm-9 form-control" v-bind:class="{'is-invalid' : isError('ryaku_name')}" id="ryaku_name" v-model="team.ryaku_name">
                        <div v-for="error in errors.ryaku_name" class="invalid-feedback">{{ error }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                team: {},
                errors: {}
            }
        },
        methods: {
            submit() {
                this.errors = [];
                axios.post('/api/teams', this.team)
                    .then((res) => {
                    console.log(res.data);
                        if (res.data.save) {
                            this.$router.push({name: 'team.index'});
                        } else {
                            this.errors = res.data.errorMessages;
                        }
                    });
            },
            isError(field) {
                // undefined時はエラーはない
                if (!(field in this.errors)) {
                    return false;
                }
                return this.errors[field].length > 0;
            }
        }
    }
</script>
