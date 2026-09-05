   <script type="text/javascript">

   var dom = document.getElementById("rpertahun");
    var myChart = echarts.init(dom);
    var app = {};

    var option;

    option = {
        title: {
            text: 'Tren Realisasi Anggaran 5 tahun terakhir',
            subtext: '<?= esc($value['sub_unit'] ?? null) ?>'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['% Realisasi']
        },
        toolbox: {
            show: true,
            feature: {
                dataView: {
                    show: true,
                    readOnly: false
                },
                magicType: {
                    show: true,
                    type: ['line', 'bar']
                },
                restore: {
                    show: true
                },
                saveAsImage: {
                    show: true
                }
            }
        },
        calculable: true,
        xAxis: [{
            type: 'category',
            // prettier-ignore
            data: ['2020', '2021', '2022', '2023', '2024']
        }],
        yAxis: [{
            type: 'value'
        }],
        series: [{
            name: '% Realisasi',
            type: 'bar',
            data: [<?php
                    $string = implode(', ', $datarealpertahun);
                    echo esc($string);
                    ?>],

            markPoint: {
                data: [{
                        type: 'max',
                        name: 'Max'
                    },
                    {
                        type: 'min',
                        name: 'Min'
                    }
                ]
            },
            markLine: {
                data: [{
                    type: 'average',
                    name: 'Avg'
                }]
            }
        }, ]
    };

    if (option && typeof option === 'object') {
        myChart.setOption(option);
    }
</script>