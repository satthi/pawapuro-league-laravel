<template>
    <div class="container">
        <h2>チーム修正</h2>
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit(submitPath, {name: 'season.index'})">
                    <div class="form-group row">
                        <label for="id" class="col-sm-3 col-form-label">ID</label>
                        <input type="text" class="col-sm-9 form-control-plaintext" readonly id="id" v-model="data.id" >
                    </div>

                    <input-component label="シーズン名" :errors="errors.name" v-model="data.name"/>
                    <checkbox-component label="レギュラーフラグ" :errors="errors.regular_flag" v-model="data.regular_flag"/>

                    <button type="submit" class="btn btn-primary">Submit</button>

                    <router-link v-bind:to="{name: 'season.index'}">
                        <button class="btn btn-success float-right">一覧に戻る</button>
                    </router-link>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import InputComponent from '../common/form/InputComponent';
    import CheckboxComponent from '../common/form/CheckboxComponent';
    import editMixin from '../../mixins/form/edit.js';
    export default {
        components: {InputComponent, CheckboxComponent},
        mixins : [editMixin],
        props: {
            seasonId: String
        },
        computed: {
          submitPath() {
            return '/api/seasons/edit/' + this.seasonId;
          }
        },
        data: function () {
            return {
                data: {},
                errors: {}
            }
        },
        mounted() {
            this.getData('/api/seasons/view/' + this.seasonId);
        }
    }
</script>