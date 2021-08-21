<template>
    <div class="container">
        <h2>チーム新規登録</h2>
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <form v-on:submit.prevent="submit('/api/base-teams/add', {name: 'base-team.index'})">
                    <input-component label="チーム名" :errors="errors.name" v-model="data.name"/>
                    <input-component label="略称" :errors="errors.ryaku_name" v-model="data.ryaku_name"/>

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
    import addMixin from '../../mixins/form/add.js';
    export default {
        components: {InputComponent},
        mixins : [addMixin],
        data: function () {
            return {
                data: {},
                errors: {}
            }
        }
    }
</script>
