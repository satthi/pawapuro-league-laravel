<template>
    <div class="container">
        <h2>{{  data.season.name }} 投手成績一覧</h2>
    </div>
</template>
<script>
    export default {
        methods: {
            initial() {
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/seasons/detail/' + this.seasonId);
            },
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
            this.initial();
        }
    }
</script>
