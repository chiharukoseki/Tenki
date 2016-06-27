<?php
if (isset($_GET["postcode"])) {
  $postcode = $_GET["postcode"];
  $error="";
  if ($postcode==="") {
    $error = "郵便番号を入力してください";
  } else {
    $base_url = 'http://weather.livedoor.com/forecast/webservice/json/v1';
    $query = ['city'=>$postcode];
    $proxy = array(
      "http" => array(
       "proxy" => "tcp://proxy.kmt.neec.ac.jp:8080",
       'request_fulluri' => true,
      ),
    );
    $proxy_context = stream_context_create($proxy);
    $response = file_get_contents(
                      $base_url.'?' .
                      http_build_query($query),
                      false,
                      $proxy_context
                );
    $result = json_decode($response,true);
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>天気の検索</title>
  </script>
</head>

<body>
  <?php //print_r($response); ?>
  <form action="tenki.php" method="get">
    <a href="http://weather.livedoor.com/forecast/rss/primary_area.xml">地域とＩＤの定義表</a>
    <br>
    <label for="name">ID:</label>
    <input type="number" name="postcode">
    <input type="submit" value="検索"><span id="err" style="color: red;"> <?= $error ?></span>
  </form>
  <hr>
  <?= $result["location"]["prefecture"] ?>
  <br>
  <?= $result["forecasts"][0]["date"] ?>
  <img src='<?= $result["forecasts"][0]["image"]["url"] ?>'>
  <?= $result["forecasts"][0]["telop"] ?>
  <br>
  <?= $result["forecasts"][1]["date"] ?>
  <img src='<?= $result["forecasts"][1]["image"]["url"] ?>'>
  <?= $result["forecasts"][1]["telop"] ?>
  <br>
  <?= $result["forecasts"][2]["date"] ?>
  <img src='<?= $result["forecasts"][2]["image"]["url"] ?>'>
  <?= $result["forecasts"][2]["telop"] ?>
  <br>
  <br>

  <?php
  $response2 = file_get_contents(
                    'https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=10&q=http://weather.livedoor.com/forecast/rss/mame.xml',
                    false,
                    $proxy_context
              );
              $result2 = json_decode($response2,true);
      ?>
      <br>

    <a
    href=<?= $result2["responseData"]["feed"]["entries"][1]["link"] ?>>
    <?= $result2["responseData"]["feed"]["entries"][1]["title"]?>
    </a>

</body>
</html>
