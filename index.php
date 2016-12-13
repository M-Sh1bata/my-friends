<?php 
// ここにデータベース接続処理
// ①DBを接続
    $dsn='mysql:dbname=myfriends;host=localhost';
    $user='root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh -> query('SET NAMES utf8');

// ②SQL作成
    // $sql = 'SELECT `areas`.`area_id`, `areas`.`area_name`, COUNT(`friends`.`friend_id`) AS friends_cnt FROM `areas` LEFT JOIN `friends` ON `areas`.`area_id` = `friends`.`area_id`  GROUP BY `areas`.`area_id`, `areas`.`area_name` ORDER BY `areas`.`area_id`';

// LEFT　JOINに副問合せ
// COUNTの部分が同じなので、この処理をしても意味がないのでは？
    $sql = 'SELECT `areas`.`area_id`, `areas`.`area_name`, COUNT(`friends`.`friend_id`) AS friends_cnt FROM `areas` LEFT JOIN (SELECT * FROM `friends` WHERE `friends`.`delete_frag`=0) AS friends ON `areas`.`area_id` = `friends`.`area_id`  GROUP BY `areas`.`area_id`, `areas`.`area_name` ORDER BY `areas`.`area_id`';

// // 関数の中にSELECT文
//     $sql = 'SELECT `areas`.`area_id`, `areas`.`area_name`, COUNT('SELECT `friends`.`friend_id` FROM `friends` WHERE `friends`.`delete_frag`=0') AS friends_cnt FROM `areas` LEFT JOIN `friends` ON `areas`.`area_id` = `friends`.`area_id`  GROUP BY `areas`.`area_id`, `areas`.`area_name` ORDER BY `areas`.`area_id`';

// // 関数の中にSELECT文を入れ、更にその中に副問合せ
//     $sql = 'SELECT `areas`.`area_id`, `areas`.`area_name`, COUNT('SELECT `friends`.`friend_id` FROM (SELECT * FROM `friends` WHERE `friends`.`delete_frag`=0)') AS friends_cnt FROM `areas` LEFT JOIN `friends` ON `areas`.`area_id` = `friends`.`area_id` GROUP BY `areas`.`area_id`, `areas`.`area_name` ORDER BY `areas`.`area_id`';

// ③SQL実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // 変数に値を格納
    $areas = array();

    // $areaId=$rec['area_id'];
    // $areaName = $rec['area_name'];

// ④データ取得
    while(1){
    $rec = $stmt -> fetch(PDO::FETCH_ASSOC);
    // 変数に値を格納
    // $areaId=$rec['area_id'];
    // $areaName = $rec['area_name'];

    //   echo $areaId;
    //   echo $areaName;
    //   echo '<br>';
    
    // 取得できるデータがなかったらループ終了
    if ($rec==false) {
      break;
    }

    $areas[]=$rec;
    }

// 友達の数をカウントする
//     $sql_count='SELECT `areas`.`area_id`, `areas`.`area_name`, COUNT(`friends`.`friend_id`) AS 　friends_cnt FROM `areas` LEFT JOIN `friends` ON `areas`.`area_id` = `friends`.`area_id` GROUP BY `areas`.`area_id`, `areas`.`area_name` ORDER BY `areas`.`area_id`';
//     $stmt_count = $dbh->prepare($sql_count);
//     $stmt_count->execute();

//     // 変数に値を格納
//     $friends_count = array();

//     // $areaId=$rec['area_id'];
//     // $areaName = $rec['area_name'];

// // ④データ取得
//     while(1){
//     $rec_count = $stmt_count -> fetch(PDO::FETCH_ASSOC);
//     // 変数に値を格納
//     // $areaId=$rec['area_id'];
//     // $areaName = $rec['area_name'];

//     //   echo $areaId;
//     //   echo $areaName;
//     //   echo '<br>';
    
//     // 取得できるデータがなかったらループ終了
//     if ($rec_count==false) {
//       break;
//     }

//     $friends_count[]=$rec_count;
//     }



// ③DB切断
      $dbh = null;



 ?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>myFriends</title>

    <!-- Bootstrap -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/form.css" rel="stylesheet">
    <link href="../assets/css/timeline.css" rel="stylesheet">
    <link href="../assets/css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.html"><span class="strong-title"><i class="fa fa-facebook-square"></i> My friends</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
      <legend>都道府県一覧</legend>
        <table class="table table-striped table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th><div class="text-center">id</div></th>
              <th><div class="text-center">県名</div></th>
              <th><div class="text-center">人数</div></th>
            </tr>
          </thead>
          <tbody>
            <!-- id, 県名を表示 -->
            <?php foreach ($areas as $area): ?>
            <tr>
              <td><div class="text-center"><?php echo $area['area_id']; ?></div></td>
              <td><div class="text-center"><a href="show.php?area_id=<?php echo $area['area_id']; ?>"><?php echo $area['area_name'] ?></a></div></td>
              <td><div class="text-center"><?php echo $area['friends_cnt']; ?></div></td>
            </tr>
            <?php endforeach; ?>

            <!-- <tr> -->
            <!--               <td><div class="text-center">2</div></td>
              <td><div class="text-center"><a href="show.php">青森</a></div></td>
              <td><div class="text-center">7</div></td>
            </tr>
            <tr>
              <td><div class="text-center">3</div></td>
              <td><div class="text-center"><a href="show.php">岩手</a></div></td>
              <td><div class="text-center">2</div></td>
            </tr>
            <tr>
              <td><div class="text-center">4</div></td>
              <td><div class="text-center"><a href="show.php">宮城</a></div></td>
              <td><div class="text-center">6</div></td>
            </tr>
            <tr>
              <td><div class="text-center">5</div></td>
              <td><div class="text-center"><a href="show.php">秋田</a></div></td>
              <td><div class="text-center">8</div></td>
            </tr>
 -->          </tbody>
        </table>
      </div>
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
