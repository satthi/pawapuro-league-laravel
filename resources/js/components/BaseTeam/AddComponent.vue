<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit">
                    <input-component label="チーム名" :errors="errors.name" v-model="baseTeam.name"/>
                    <input-component label="略称" :errors="errors.ryaku_name" v-model="baseTeam.ryaku_name"/>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import InputComponent from '../common/form/InputComponent';
    export default {
        components: {InputComponent},
        data: function () {
            return {
                baseTeam: {},
                errors: {}
            }
        },
        methods: {
            submit() {
                this.errors = [];
                axios.post('/api/base-teams', this.baseTeam)
                    .then((res) => {
                        if (res.data.save) {
                            this.$router.push({name: 'base-team.index'});
                        } else {
                            this.errors = res.data.errorMessages;
                        }
                    });
            }
        }
    }
</script>
