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
import GameIndexComponent from "./components/Game/IndexComponent";
import GameAddComponent from "./components/Game/AddComponent";
import GameAutoAddComponent from "./components/Game/AutoAddComponent";
import GameViewComponent from "./components/Game/ViewComponent";
import GameProbablePitcherUpdateComponent from "./components/Game/ProbablePitcherUpdateComponent";

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
