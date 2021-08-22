<template>
    <div class="container">
        <h2>{{  data.season.name }}</h2>
        <div class="clearfix">
            <router-link v-bind:to="{name: 'game.index', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success float-right">日程一覧</button>
            </router-link>
        </div>
    </div>
</template>
<script>
    export default {
        methods: {
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
        },
        props: {
            seasonId: String
        },
        data: function () {
            return {
                data: {
                    season: {}
                }
            }
        },
        mounted() {
            // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
            this.getData('/api/seasons/detail/' + this.seasonId);
        }
    }
</script>
