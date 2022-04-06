<template>
    <div class="container-fluid bg-dark mb-3">
        <div class="container">
            <nav class="navbar navbar-dark">
                <span class="navbar-brand mb-0 h1">Vue Laravel SPA</span>
                <div>
                    <router-link v-bind:to="{name: 'base-player.fielder-rank', params: {sortType: 'avg' }}">
                        <button class="btn btn-success">野手成績</button>
                    </router-link>
                    <router-link v-bind:to="{name: 'base-player.pitcher-rank', params: {sortType: 'p_era' }}">
                        <button class="btn btn-success">投手成績</button>
                    </router-link>
                    <router-link v-bind:to="{name: 'base-team.index'}">
                        <button class="btn btn-success">チーム</button>
                    </router-link>
                    <router-link v-bind:to="{name: 'season.index'}">
                        <button class="btn btn-success">シーズン</button>
                    </router-link>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {}
</script>
