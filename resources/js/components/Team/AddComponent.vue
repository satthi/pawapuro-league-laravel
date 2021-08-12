<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit">
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Title</label>
                        <input type="text" class="col-sm-9 form-control" id="name" v-model="team.name">
                    </div>
                    <div class="form-group row">
                        <label for="ryaku_name" class="col-sm-3 col-form-label">Content</label>
                        <input type="text" class="col-sm-9 form-control" id="ryaku_name" v-model="team.ryaku_name">
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
                team: {}
            }
        },
        methods: {
            submit() {
                axios.post('/api/teams', this.team)
                    .then((res) => {
                        this.$router.push({name: 'team.index'});
                    });
            }
        }
    }
</script>
