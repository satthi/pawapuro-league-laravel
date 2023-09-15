<template>
    <div>
    <div v-if="data.player !== undefined">
        <FielderCardComponent v-if="data.player.position_main != 1" :data="data" />
        <PitcherCardComponent v-if="data.player.position_main == 1" :data="data" />
    </div>
    </div>
</template>

<script>
    import FielderCardComponent from './fielder/UraComponent';
    import PitcherCardComponent from './pitcher/UraComponent';
    export default {
        components: {FielderCardComponent, PitcherCardComponent},
        props: ['data'],
    }
</script>
