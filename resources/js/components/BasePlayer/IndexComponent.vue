<template>
    <div class="container">
        <div class="clearfix">
            <router-link v-bind:to="{name: 'base-player.add', params: {basePlayerId: baseTeamId.toString() }}">
                <button class="btn btn-success float-right">追加</button>
            </router-link>
        </div>
        <table class="table table-hover">
            <thead class="thead-light">
            <tr>
                <th scope="col">選手名</th>
                <th scope="col">表示名</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>

                <tr v-for="(basePlayer, index) in basePlayers" :key="index">
                    <td scope="row">{{ basePlayer.name }}</td>
                    <td>{{ basePlayer.name_short }}</td>
                    <td>
                        <router-link v-bind:to="{name: 'base-Player.edit', params: {basePlayerId: basePlayer.id.toString() }}">
                            <button class="btn btn-success">Edit</button>
                        </router-link>
                        <button class="btn btn-danger" v-on:click="deleteBasePlayer(basePlayerId.id)">Delete</button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        props: {
            baseTeamId: String
        },
        data: function () {
            return {
                basePlayers: []
            }
        },
        methods: {
            getBasePlayers() {
                axios.get('/api/base-players/' + this.baseTeamId)
                    .then((res) => {
                        this.basePlayers = res.data;
                    });
            },
            deleteBasePlayer(id) {
                axios.delete('/api/base-players/' + id)
                    .then((res) => {
                        this.getBasePlayers();
                    });
            }
        },
         mounted() {
            this.getBasePlayers();
        }
    }
</script>
