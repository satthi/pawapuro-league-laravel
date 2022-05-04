<template>
    <div class="container" v-if="Object.keys(data).length">
        <h2>{{  data.season.name }}</h2>
        <div class="clearfix">
            <router-link ref="hoge" v-bind:to="{name: 'season.view', params: {seasonId: seasonId.toString() }}">
                <button class="btn btn-success">詳細</button>
            </router-link>
        </div>

        <div style="position:relative;height:600px;width: 100%;">
            <canvas ref="my_chart" height="400"></canvas>
        </div>
    </div>
</template>
<script>
    import Chart from 'chart.js';
    export default {
        methods: {
            initial() {
                // チーム情報など込みで詳細画面表示に必要な情報をまとめて取得（したい）
                this.getData('/api/seasons/detail/' + this.seasonId);
                this.getGraphData('/api/seasons/graph/' + this.seasonId);
            },
            getData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                        this.data = res.data;
                    });
            },
            getGraphData(getPath) {
                axios.get(getPath)
                    .then((res) => {
                    console.log(res)
                        console.log(res.data.labels)
                        console.log(res.data.datasets)

                        // グラフ作成
                        var thisObject = this
                        var timerId = setInterval(function(){
                            var ref = thisObject.$refs;
                            var refLen = Object.keys(ref).length;
                            if (refLen != 0) {
                                clearInterval(timerId);

                                var ctx = thisObject.$refs.my_chart
                                var myChart = new Chart(ctx, {
                                  type: 'line',
                                  data: {
                                    labels: res.data.labels,
                                    datasets: res.data.datasets,
                                    /*
                                    [{
                                      label: 'Red',
                                      data: [20, 35, null, 30, 45, 35, 40],
                                      // データライン
                                      borderColor: '#f88',
                                      lineTension: 0,
                                      fill: false,
                                      spanGaps: true,

                                    }, {
                                      label: 'Green',
                                      data: [20, 15, 30, 25, 30, 40, 35],
                                      borderColor: '#484',
                                      lineTension: 0,
                                      fill: false,
                                    }, {
                                      label: 'Blue',
                                      data: [30, 25, 10, 5, 25, 30, -20],
                                      borderColor: '#48f',
                                      lineTension: 0,
                                      fill: false,
                                    }],
                                    */
                                  },
                                  lineTension : 0,
                                  options: {
                                   'maintainAspectRatio' : false,
                                    plugins: {
                                      // グラフタイトル
                                      title: {
                                        display: true,
                                        text: 'Sample Chart',
                                        color: 'black',
                                        padding: {top: 5, bottom: 5},
                                        font: {
                                          family: '"Arial", "Times New Roman"',
                                          size: 12,
                                        },
                                      },
                                      // 凡例
                                      legend: {
                                        position: 'right',
                                        align: 'start',
                                        // 凡例ラベル
                                        labels: {
                                          boxWidth: 20,
                                          boxHeight: 8,
                                        },
                                        // 凡例タイトル
                                        title: {
                                          display: true,
                                          text: 'Legend',
                                          padding: {top: 20},
                                        },
                                      },
                                      // ツールチップ
                                      tooltip: {
                                        backgroundColor: '#933',
                                      },
                                    },
                                    scales: {
                                      y: {
                                        // 最小値・最大値
                                        min: 0,
                                        max: 60,
                                        // 軸タイトル
                                        title: {
                                          display: true,
                                          text: 'Scale Title',
                                          color: 'blue',
                                        },
                                        // 目盛ラベル
                                        ticks: {
                                          color: 'blue',
                                          stepSize: 20,
                                          showLabelBackdrop: true,
                                          backdropColor: '#ddf',
                                          backdropPadding: { x: 4, y: 2 },
                                          major: {
                                            enabled: true,
                                          },
                                          align: 'end',
                                          crossAlign: 'center',
                                          sampleSize: 4,
                                        },
                                        grid: {
                                          // 軸線
                                          borderColor: 'orange',
                                          borderWidth: 2,
                                          drawBorder: true,
                                          // 目盛線＆グリッド線
                                          color: '#080',
                                          display: true,
                                          // グリッド線
                                          borderDash: [3, 3],
                                          borderDashOffset: 0,
                                          // 目盛線
                                          drawTicks: true,
                                          tickColor: 'blue',
                                          tickLength: 10,
                                          tickWidth: 2,
                                          tickBorderDash: [2, 2],
                                          tickBorderDashOffset: 0,
                                        },
                                      },
                                      x: {
                                        grid: {
                                          borderColor: 'orange',
                                          borderWidth: 2,
                                        },
                                      },
                                    },
                                  },
                                });
                            }
                        }, 1000);

                    });
            },
        },
        props: {
            seasonId: String
        },
        data: function () {
            return {
                data: {}
            }
        },
        mounted() {
            this.initial();
        }
    }
</script>
