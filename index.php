<?php
function kisalt($metin, $uzunluk)
{
    $metin = substr($metin, 0, $uzunluk) . "...";
    $metin_son = strrchr($metin, " ");
    $metin = str_replace($metin_son, " ...", $metin);
    return $metin;
}


$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://coronavirus-map.p.rapidapi.com/v1/spots/week?region=turkey",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "x-rapidapi-host: coronavirus-map.p.rapidapi.com",
        "x-rapidapi-key: your-api-key"
    ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$decodeNews = json_decode($response);
$tarih_arr = array();
$bugun = date('Y-m-d');
$totalcaseStats = array();
$totalrecovered = array();
$totalcritical = array();
$totalrecovered = array();
$totaltested = array();
foreach ($decodeNews->data as $veri) {
    $cevir = date('Y-m-d', strtotime('-1 day', strtotime($bugun)));
    array_push($tarih_arr, $bugun);
    $bugun = $cevir;
    $arr = $veri->total_cases;
    array_push($totalcaseStats, $arr);
    array_push($totalrecovered, $veri->recovered);
    array_push($totalcritical, $veri->critical);
    array_push($totalrecovered, $veri->recovered);
    array_push($totaltested, $veri->tested);
}
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.collectapi.com/corona/coronaNews",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "authorization: apikey your-api-key",
        "content-type: application/json"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $decodeNews = json_decode($response);

    if ($decodeNews->success == 1) {

//echo ($response);
        foreach ($decodeNews->result as $veri) {
            /* echo $veri->url;
             echo $veri->description;
             echo $veri->description;
             echo $veri->image;
             echo $veri->source;
             echo $veri->date; */

        }


    }

}

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://coronavirus-monitor.p.rapidapi.com/coronavirus/latest_stat_by_iso_alpha_3.php?alpha3=TUR",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "x-rapidapi-host: coronavirus-monitor.p.rapidapi.com",
        "x-rapidapi-key: your-api-key"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $decode = json_decode($response);

    foreach ($decode->latest_stat_by_country as $veri) {
        $toplamVaka = $veri->total_cases;
        $aktifVaka = $veri->active_cases;
        $toplamOlum = $veri->total_deaths;
        $toplamIyilesen = $veri->total_recovered;
        $record_date = $veri->record_date;

    }


}

$toplamVakaOran = (100 * $toplamVaka) / $toplamVaka;
echo round($toplamVakaOran, 2);


$toplamVakaOran = (100 * $aktifVaka) / $toplamVaka;
echo round($toplamVakaOran, 2);


$toplamVakaOran = (100 * $toplamIyilesen) / $toplamVaka;
echo round($toplamVakaOran, 2);

$toplamVakaOran = (100 * $toplamOlum) / $toplamVaka;
echo round($toplamVakaOran, 2);
?>

<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<!-- Chart code -->
<script>
    am4core.ready(function () {
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("chartdiv", am4charts.XYChart);
        var data = [];
        <?php $i = 0;
        foreach ($tarih_arr as $veri){
        ?>
        var date = '<?php echo $veri ?>';
        var value = <?php echo $totalcaseStats[$i] ?>;
        data.push({date: date, value: value});
        <?php
        $i = $i + 1;
        }
        ?>
        chart.data = data;
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 60;
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "date";
        series.tooltipText = "{value}"
        series.tooltip.pointerOrientation = "vertical";
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.snapToSeries = series;
        chart.cursor.xAxis = dateAxis;
        chart.scrollbarX = new am4core.Scrollbar();

    });
</script>


<script>
    am4core.ready(function () {
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("chartdiv2", am4charts.XYChart);
        var data = [];
        <?php $i = 0;
        foreach ($tarih_arr as $veri){
        ?>
        var date = '<?php echo $veri ?>';
        var value = <?php echo $totalcritical[$i] ?>;
        data.push({date: date, value: value});
        <?php
        $i = $i + 1;
        }
        ?>
        chart.data = data;
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 60;
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "date";
        series.tooltipText = "{value}"
        series.tooltip.pointerOrientation = "vertical";
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.snapToSeries = series;
        chart.cursor.xAxis = dateAxis;
        chart.scrollbarX = new am4core.Scrollbar();

    });
</script>

<script>
    am4core.ready(function () {
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("chartdiv3", am4charts.XYChart);
        var data = [];
        <?php $i = 0;
        foreach ($tarih_arr as $veri){
        ?>
        var date = '<?php echo $veri ?>';
        var value = <?php echo $totaltested[$i] ?>;
        data.push({date: date, value: value});
        <?php
        $i = $i + 1;
        }
        ?>
        chart.data = data;
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 60;
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "date";
        series.tooltipText = "{value}"
        series.tooltip.pointerOrientation = "vertical";
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.snapToSeries = series;
        chart.cursor.xAxis = dateAxis;
        chart.scrollbarX = new am4core.Scrollbar();

    });
</script>

<script>
    am4core.ready(function () {
        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("chartdiv4", am4charts.XYChart);
        var data = [];
        <?php $i = 0;
        foreach ($tarih_arr as $veri){
        ?>
        var date = '<?php echo $veri ?>';
        var value = <?php echo $totalrecovered[$i] ?>;
        data.push({date: date, value: value});
        <?php
        $i = $i + 1;
        }
        ?>
        chart.data = data;
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.renderer.minGridDistance = 60;
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "date";
        series.tooltipText = "{value}"
        series.tooltip.pointerOrientation = "vertical";
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.snapToSeries = series;
        chart.cursor.xAxis = dateAxis;
        chart.scrollbarX = new am4core.Scrollbar();

    });
</script>


<div class="row">
    <div class="col-sm-6">
        <div style="padding-left: 18px"> SON 1 HAFTA TOPLAM TEST</div>
        <div id="chartdiv3"></div>
    </div>
    <div class="col-sm-6">
        <div style="padding-left: 18px"> SON 1 HAFTA İYİLEŞEN HASTA SAYISI</div>
        <div id="chartdiv4"></div>
    </div>
</div> <BR><BR><BR>

<div class="row">
    <div class="col-sm-6">
        <div style="padding-left: 18px"> SON 1 HAFTA TOPLAM VAKA</div>
        <div id="chartdiv"></div>
    </div>
    <div class="col-sm-6">
        <div style="padding-left: 18px"> SON 1 HAFTA YOĞUN BAKIMDAKİ HASTA SAYISI</div>
        <div id="chartdiv2"></div>
    </div>
</div>

<? foreach ($decodeNews->result as $veri) {
    if ($veri->name != '') {
        ?>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-top: 10px">
            <div class="contact-client-single ct-client-b-mg-30 ct-client-b-mg-30-n shadow-reset">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="contact-client">
                            <a target="_blank" href="<? echo $veri->url; ?>"><img
                                        src="<? echo $veri->image ?>" alt=""/>
                            </a>

                            <h5 style="padding-top: 5px">
                                <a id="group" data-type="select" data-pk="1" data-value="5"
                                   data-source="/groups" data-title="Select group" href="#">
                                    <?
                                    echo $veri->source . "<br>";
                                    echo date('d.m.Y H:i', strtotime($veri->date)) ?>
                                </a>
                            </h5>

                        </div>
                    </div>
                    <div class="col-lg-8">

                        <a target="_blank" href="<? echo $veri->url; ?>">
                            <div class="contact-client-address">
                                <h3>
                                    <? echo($veri->name) ?>
                                </h3>
                                <p class="address-client-ct">
                                    <? echo kisalt($veri->description, 100) ?>
                                </p>

                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <? }
} ?>

</div>

