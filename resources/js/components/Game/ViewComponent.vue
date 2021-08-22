<template>
    <div class="container">
        <h2>{{ data.date }} {{ data.home_team.name }} VS {{ data.visitor_team.name }}</h2>
        <div class="row justify-content-center">
            <div class="col-sm-6">
                    <!-- {{ data }}--><br />
                    <h4>予告先発</h4>
                    先行予告先発： {{ data.visitor_probable_pitcher ? data.visitor_probable_pitcher.name : '未設定' }}

                    <br />
                    後攻予告先発： {{ data.home_probable_pitcher ? data.home_probable_pitcher.name : '未設定' }}
                    <br />
                    <router-link v-bind:to="{name: 'game.probable-pitcher-edit', params: {gameId: gameId.toString() }}">
                        <button class="btn btn-success">設定</button>
                    </router-link>
                    <hr />

                    <h4>スタメン</h4>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            gameId: String
        },
        data: function () {
            return {
                data: {
                    'home_team' : {
                        'name' : ''
                    },
                    'visitor_team' : {
                        'name' : ''
                    },
                }
            }
        },
        methods: {
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
        },
        mounted() {
            this.getData('/api/games/view/' + this.gameId);
        }
    }
</script>