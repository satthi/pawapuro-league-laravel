<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit">
                    <div class="form-group row">
                        <label for="id" class="col-sm-3 col-form-label">ID</label>
                        <input type="text" class="col-sm-9 form-control-plaintext" readonly id="id" v-model="baseTeam.id" >
                    </div>

                    <input-component label="チーム名" :errors="errors.name" :value="baseTeam.name" v-model="baseTeam.name"/>
                    <input-component label="略称" :errors="errors.ryaku_name" :value="baseTeam.name" v-model="baseTeam.ryaku_name"/>

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
        props: {
            baseTeamId: String
        },
        data: function () {
            return {
                baseTeam: {},
                errors: {}
            }
        },
        methods: {
            getBaseTeam() {
                axios.get('/api/base-teams/' + this.baseTeamId)
                    .then((res) => {
                        console.log(res.data)
                        this.baseTeam = res.data;
                    });
            },
            submit() {
                this.errors = [];
                axios.put('/api/base-teams/' + this.baseTeamId, this.baseTeam)
                    .then((res) => {
                    console.log(res.data)
                        if (res.data.save) {
                            this.$router.push({name: 'base-team.index'});
                        } else {
                            this.errors = res.data.errorMessages;
                            console.log(this.errors);
                        }
                    });
            }
        },
        mounted() {
            this.getBaseTeam();
        }
    }
</script>