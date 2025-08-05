@if(is_null($error))
<div class="row">

    @foreach($relatorio AS $k => $row)
        <div class="col-md-4" style="margin-top: 40px">
            <div id="chart-container-{!! $k !!}"></div>
        </div>
    @endforeach

</div>
<script type="text/javascript">
    <?php
    foreach ($relatorio AS $k => $row){
    ?>
    FusionCharts.ready(function () {
        var csatGauge = new FusionCharts({
            "type": "angulargauge",
            "renderAt": "chart-container-<?php echo $k; ?>",
            "width": "100%",
            "height": "250",
            "dataFormat": "json",
            "dataSource":{
                "chart": {
                    "caption": "<?php echo  $row['linha_cuidado']; ?> (<?php echo  $row['min']; ?> ~ <?php echo  $row['max']; ?>)",
                    "subcaption": "<?php echo  number_format($row['total'], 0, "", ","); ?>",
                    "lowerLimit": "0",
                    "upperLimit": "<?php echo  $row['max']; ?>",
                    "theme": "fint"
                },
                "colorRange": {
                    "color": [
                        {
                            "minValue": "0",
                            "maxValue": "<?php echo  $row['min']; ?>",
                            "code": "#e44a00"
                        },
                        {
                            "minValue": "<?php echo  $row['min']; ?>",
                            "maxValue": "<?php echo  $row['max']; ?>",
                            "code": "#f8bd19"
                        },
                        {
                            "minValue": "<?php echo  $row['max']; ?>",
                            "maxValue": "<?php echo  $row['max']; ?>",
                            "code": "#6baa01"
                        }
                    ]
                },
                "dials": {
                    "dial": [
                        {
                            "value": "<?php echo  $row['total']; ?>"
                        }
                    ]
                }
            }
        });

        csatGauge.render();
    });
    <?php } ?>
</script>
@else
    <div class="alert alert-danger">Nenhuma informação encontrada!</div>
@endif

