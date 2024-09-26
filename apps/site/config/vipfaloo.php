<?php

// Cài đặt giá tiền cho mỗi 1000 chữ, tính theo VNĐ
return [
    "priceword" => 200, // giá tiền cho mỗi 1000 chữ khi chưa có trong db (mua lần đầu tiên)

    "pricevip" => 100, // giá tiền cho mỗi 1 điểm vip của faloo khi chưa có trong db (mua lần đầu tiên)

    "priceworduservip" => 0, // giá tiền cho mỗi chương của user vip giáng thế khi mua chương đã có trong db

    "pricewordolduser" => 130, // giá tiền cho mỗi chương của user mua lại những chương đã có trong db

    "percentprofit" => 0.8, // phần trăm lơi nhuận khi mua chương chia cho user mua đầu tiên

    "percentdonate" => 0,// phần trăm donate chia cho user mua chương đầu tiên

    "minword" => 1000, // số chữ tối thiểu để tính tiền

    "method" => 0, // 0: tính theo 1000 chữ, 1: tính theo giá vip của faloo
    "proxy" => "socks5://ohuy0895:QJSqlt7469@160.30.91.3:43541",
    //cookie đã đăng nhập của faloo
    "cookie" => "KeenFire=UMID=34307370&UserID=fgstarpopp&Pwd=281a826d30bf4906a5076d4ffa23090e&Identity=web45562.2606329226&PhotoID=0&NickName=fgstarpopp; UU12345678=uuc=133591887448422283345958489"
    // "cookie" => "KeenFire=UMID=34092005&UserID=hoanganh97&Pwd=74639c9632ac61ab0292f3ee2d30ceeb&Identity=web45394.1252113685&PhotoID=0&NickName=hoanganh97; UU12345678=uuc=133573096409661720189114593"
];
