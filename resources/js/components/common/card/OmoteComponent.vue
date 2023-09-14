<template>
    <div>
    <div v-if="data.data.player !== undefined">
        <FielderCardComponent v-if="data.data.player.position_main != 1" :data={data} />
        <PitcherCardComponent v-if="data.data.player.position_main == 1" :data={data} />
    </div>
    </div>
</template>

<script>
    import FielderCardComponent from './fielder/OmoteComponent';
    import PitcherCardComponent from './pitcher/OmoteComponent';
    export default {
        components: {FielderCardComponent, PitcherCardComponent},
        props: ['data'],
    }
</script>
