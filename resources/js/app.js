import VueRouter from 'vue-router';
import HeaderComponent from "./components/HeaderComponent";
// import TaskListComponent from "./components/TaskListComponent";
// import TaskShowComponent from "./components/TaskShowComponent";
// import TaskCreateComponent from "./components/TaskCreateComponent";
// import TaskEditComponent from "./components/TaskEditComponent";
import BaseTeamIndexComponent from "./components/BaseTeam/IndexComponent";
import BaseTeamAddComponent from "./components/BaseTeam/AddComponent";
import BaseTeamEditComponent from "./components/BaseTeam/EditComponent";
import BasePlayerIndexComponent from "./components/BasePlayer/IndexComponent";
import BasePlayerAddComponent from "./components/BasePlayer/AddComponent";
import BasePlayerEditComponent from "./components/BasePlayer/EditComponent";
import SeasonIndexComponent from "./components/Season/IndexComponent";
import SeasonAddComponent from "./components/Season/AddComponent";
import SeasonEditComponent from "./components/Season/EditComponent";
import SeasonViewComponent from "./components/Season/ViewComponent";
import SeasonFielderRankComponent from "./components/Season/FielderRankComponent";
import SeasonPitcherRankComponent from "./components/Season/PitcherRankComponent";

import GameIndexComponent from "./components/Game/IndexComponent";
import GameAddComponent from "./components/Game/AddComponent";
import GameAutoAddComponent from "./components/Game/AutoAddComponent";
import GameViewComponent from "./components/Game/ViewComponent";
import GameProbablePitcherUpdateComponent from "./components/Game/ProbablePitcherUpdateComponent";
import GameStamenEditComponent from "./components/Game/StamenEditComponent";
import GamePlayComponent from "./components/Game/PlayComponent";
import GamePinchHitterComponent from "./components/Game/PinchHitterComponent";
import GamePinchRunnerComponent from "./components/Game/PinchRunnerComponent";
import GamePositionChangeComponent from "./components/Game/PositionChangeComponent";
import GameStealComponent from "./components/Game/StealComponent";
import GameSummaryComponent from "./components/Game/SummaryComponent";
import GameFielderSummaryComponent from "./components/Game/FielderSummaryComponent";
import GamePitcherSummaryComponent from "./components/Game/PitcherSummaryComponent";
import TeamViewComponent from "./components/Team/ViewComponent";


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        // {
        //     path: '/tasks',
        //     name: 'task.list',
        //     component: TaskListComponent
        // },
        // {
        //     path: '/tasks/:taskId',
        //     name: 'task.show',
        //     component: TaskShowComponent,
        //     props: true
        // },
        // {
        //     path: '/tasks/create',
        //     name: 'task.create',
        //     component: TaskCreateComponent
        // },
        // {
        //     path: '/tasks/:taskId/edit',
        //     name: 'task.edit',
        //     component: TaskEditComponent,
        //     props: true
        // },
        // ベースのチーム
        {
            path: '/base-teams',
            name: 'base-team.index',
            component: BaseTeamIndexComponent,
            props: true
        },
        {
            path: '/base-teams/add',
            name: 'base-team.add',
            component: BaseTeamAddComponent,
            props: true
        },
        {
            path: '/base-team/:baseTeamId/edit',
            name: 'base-team.edit',
            component: BaseTeamEditComponent,
            props: true
        },
        // ベースの選手
        {
            path: '/base-players/:baseTeamId',
            name: 'base-player.index',
            component: BasePlayerIndexComponent,
            props: true
        },
        {
            path: '/base-players/:baseTeamId/add',
            name: 'base-player.add',
            component: BasePlayerAddComponent,
            props: true
        },
        {
            path: '/base-player/:basePlayerId/edit',
            name: 'base-player.edit',
            component: BasePlayerEditComponent,
            props: true
        },
        // ベースのチーム
        {
            path: '/seasons',
            name: 'season.index',
            component: SeasonIndexComponent,
            props: true
        },
        {
            path: '/seasons/add',
            name: 'season.add',
            component: SeasonAddComponent,
            props: true
        },
        {
            path: '/season/:seasonId/edit',
            name: 'season.edit',
            component: SeasonEditComponent,
            props: true
        },
        {
            path: '/season/:seasonId/view',
            name: 'season.view',
            component: SeasonViewComponent,
            props: true
        },
        {
            path: '/season/:seasonId/fielder-rank',
            name: 'season.fielder-rank',
            component: SeasonFielderRankComponent,
            props: true
        },
        {
            path: '/season/:seasonId/pitcher-rank',
            name: 'season.pitcher-rank',
            component: SeasonPitcherRankComponent,
            props: true
        },
        {
            path: '/games/:seasonId',
            name: 'game.index',
            component: GameIndexComponent,
            props: true
        },
        {
            path: '/games/:seasonId/add',
            name: 'game.add',
            component: GameAddComponent,
            props: true
        },
        {
            path: '/games/:seasonId/auto-add',
            name: 'game.auto_add',
            component: GameAutoAddComponent,
            props: true
        },
        {
            path: '/games/view/:gameId',
            name: 'game.view',
            component: GameViewComponent,
            props: true
        },
        {
            path: '/games/:gameId/probable-pitcher-edit',
            name: 'game.probable-pitcher-edit',
            component: GameProbablePitcherUpdateComponent,
            props: true
        },
        {
            path: '/games/:gameId/:stamenType/stamen-edit',
            name: 'game.stamen-edit',
            component: GameStamenEditComponent,
            props: true
        },
        {
            path: '/games/:gameId/play',
            name: 'game.play',
            component: GamePlayComponent,
            props: true
        },
        {
            path: '/games/:gameId/:teamType/ph',
            name: 'game.ph',
            component: GamePinchHitterComponent,
            props: true
        },
        {
            path: '/games/:gameId/:teamType/pr',
            name: 'game.pr',
            component: GamePinchRunnerComponent,
            props: true
        },
        {
            path: '/games/:gameId/:teamType/position',
            name: 'game.position',
            component: GamePositionChangeComponent,
            props: true
        },
        {
            path: '/games/:gameId/:teamType/steal',
            name: 'game.steal',
            component: GameStealComponent,
            props: true
        },
        {
            path: '/games/:gameId/summary',
            name: 'game.summary',
            component: GameSummaryComponent,
            props: true
        },
        {
            path: '/games/:gameId/:type/fielder_summary',
            name: 'game.fielder_summary',
            component: GameFielderSummaryComponent,
            props: true
        },
        {
            path: '/games/:gameId/:type/pitcher_summary',
            name: 'game.pitcher_summary',
            component: GamePitcherSummaryComponent,
            props: true
        },
        {
            path: '/teams/:teamId',
            name: 'team.view',
            component: TeamViewComponent,
            props: true
        },

    ]
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('header-component', HeaderComponent);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
