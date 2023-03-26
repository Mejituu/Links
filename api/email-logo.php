<?php

/* 邮箱头像解API接口 */
$type = (isset($_GET['type'])) ? $_GET['type'] : $_POST['type'];
$email = (isset($_GET['email'])) ? $_GET['email'] : $_POST['email'];

if ($email == null || $email == '') {
    echo '请提交邮箱链接 [email=abc@abc.com]';
    exit;
} else if ($type == null || $type == '' || ($type != 'txt' && $type != 'json')) {
    echo '请提交type类型 [type=txt, type=json]';
    exit;
} else {
    $f = str_replace('@qq.com', '', $email);
    $email = $f . '@qq.com';
    if (is_numeric($f) && strlen($f) < 11 && strlen($f) > 4) {
        stream_context_set_default([
            'ssl' => [
                'verify_host' => false,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $geturl = 'https://s.p.qq.com/pub/get_face?img_type=3&uin=' . $f;
        $headers = get_headers($geturl, TRUE);
        if ($headers) {
            $g = $headers['Location'];
            $g = str_replace("http:", "https:", $g);
        } else {
            $g = 'https://q.qlogo.cn/g?b=qq&nk=' . $f . '&s=100';
        }
    } else {
        $g = 'https://cdn.helingqi.com/wavatar/' . md5($email) . '?d=mm';
    }
    $r = array('url' => $g);
    if ($type == 'txt') {
        echo $g;
        exit;
    } else if ($type == 'json') {
        echo json_encode($r);
        exit;
    }
}

/** Links by 懵仙兔兔 */
