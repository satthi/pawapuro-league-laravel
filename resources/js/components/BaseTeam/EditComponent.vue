<template>
    <div class="container">
        <h2>チーム修正</h2>
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit(submitPath, {name: 'base-team.index'})">
                    <div class="form-group row">
                        <label for="id" class="col-sm-3 col-form-label">ID</label>
                        <input type="text" class="col-sm-9 form-control-plaintext" readonly id="id" v-model="data.id" >
                    </div>

                    <input-component label="チーム名" :errors="errors.name" :value="data.name" v-model="data.name"/>
                    <input-component label="略称" :errors="errors.ryaku_name" :value="data.name" v-model="data.ryaku_name"/>

                    <button type="submit" class="btn btn-primary">Submit</button>

                    <router-link v-bind:to="{name: 'base-team.index'}">
                        <button class="btn btn-success float-right">一覧に戻る</button>
                    </router-link>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import InputComponent from '../common/form/InputComponent';
    import editMixin from '../../mixins/form/edit.js';
    export default {
        components: {InputComponent},
        mixins : [editMixin],
        props: {
            baseTeamId: String
        },
        computed: {
          submitPath() {
            return '/api/base-teams/edit/' + this.baseTeamId;
          }
        },
        data: function () {
            return {
                data: {},
                errors: {}
            }
        },
        mounted() {
            this.getData('/api/base-teams/view/' + this.baseTeamId);
        }
    }
</script>