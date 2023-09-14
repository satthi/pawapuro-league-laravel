<template>
    <div>
    <div v-if="data.data.player !== undefined">
        <FielderCardComponent v-if="data.data.player.position_main != 1"/>
        <PitcherCardComponent v-if="data.data.player.position_main == 1"/>
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
