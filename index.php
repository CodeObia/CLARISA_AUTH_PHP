<?php
require 'constants.php';

$data_exist = false;
if ($_GET["code"]) {

    $data = array(
        'client_id' => CLIENT_ID,
        'client_secret' => CLIENT_SECRET,
        'code' => $_GET["code"],
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://cauth.loc.codeobia.com/api/oauth');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: */*', 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseArray = json_decode($response);
    if ($responseArray->access_token) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://cauth.loc.codeobia.com/api/oauth/me');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Authorization: Bearer ' . $responseArray->access_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        curl_close($ch);

        $data_exist = true;
        $userData = json_decode($result, true);
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <?php if ($data_exist) { ?>
        <div class="row" style="margin-top: 100px">
            <ul class="list-group">
                <li class="list-group-item active" aria-current="true">
                    Logged in as <?= $userData['firstName'] . ' ' . $userData['lastName'] ?>
                </li>
                <?php foreach ($userData as $key => $value) { ?>
                    <?php if ($key != 'firstName' && $key != 'lastName' && $key != 'id') { ?>
                        <li class="list-group-item">
                            <?= $key . ': ' . $value ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
        <div class="row" style="margin-top: 50px">
            <div class="col-md-4"></div>
            <a href="http://clarisauth.local" class="btn btn-primary col-md-4"> Logout </a>
            <div class="col-md-4"></div>
        </div>
    <?php } else { ?>
        <div class="row" style="margin-top: 100px">
            <div class="col-md-4"></div>
            <a href="https://cauth.loc.codeobia.com/auth/client?client_id=47" class="btn btn-primary col-md-4">
                Login </a>
            <div class="col-md-4"></div>
        </div>
    <?php } ?>
</div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</html>
