<?php

// Cài đặt giá tiền cho mỗi 1000 chữ, tính theo VNĐ
return [
    "priceword" => 180, // giá tiền cho mỗi 1000 chữ khi chưa có trong db (mua lần đầu tiên)

    "pricevip" => 100, // giá tiền cho mỗi 1 điểm vip của faloo khi chưa có trong db (mua lần đầu tiên)

    "priceworduservip" => 0, // giá tiền cho mỗi chương của user vip giáng thế khi mua chương đã có trong db

    "pricewordolduser" => 120, // giá tiền cho mỗi chương của user mua lại những chương đã có trong db

    "percentprofit" => 1, // phần trăm lơi nhuận khi mua chương chia cho user mua đầu tiên

    "percentdonate" => 0,// phần trăm donate chia cho user mua chương đầu tiên

    "minword" => 1000, // số chữ tối thiểu để tính tiền

    "method" => 0, // 0: tính theo 1000 chữ, 1: tính theo giá vip của faloo

    //cookie đã đăng nhập của faloo
    "cookie" => "KeenFire=UMID=33728928&UserID=fgstarpo&Pwd=ba209c3bc007ba61fbd7424082e535f0&Identity=web45387.7350331364&PhotoID=0&NickName=fgstarpo; UU12345678=uuc=132736771118206864325121945"
];
