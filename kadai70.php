<?php

// # 70 ファイル関数
// 5つの果物の名前(string型)の要素をもつ配列を宣言してください。
// カレントディレクトリに、配列の中身を１行ずつ記載したCSVファイルを出力してください。
// CSVのファイル名はfruits.csvとします。
// ex)
// $fruits = array("apple", "banana", "orange); なら
// CSVファイルの中身は
// apple
// banana
// orange

$fruits = ['apple', 'banana', 'orange', 'grape', 'pinaple'];

$file = new SplFileObject('fruits.csv', 'w');

foreach($fruits as $fruit){
    $file->fputcsv([$fruit]); 
}



// # 71
// 70.の続き
// csvファイルの出力場所を下記パスに変更してください。
// ./csv/dev/fruits/
// その際に、上記パスのディレクトリが存在しない場合は、再帰的にディレクトリを作成する処理を追加してください。

// ディレクトリが存在しない場合に再帰的にディレクトリを作成
$directoryPath = './csv/dev/fruits/';
if (!file_exists($directoryPath)) {
    mkdir($directoryPath, 0777, true);
}

$filePath = $directoryPath . 'fruits.csv';
$file = new SplFileObject($filePath, 'w');

foreach($fruits as $fruit){
    $file->fputcsv([$fruit]); 
}



// # 72
// 71.の続き
// 71で出力したcsvファイルに、それぞれ金額と在庫数の項目を追加したい。
// 71で出力したcsvファイルを読み込んで、金額と在庫数の項目を追加してください。
// なお金額は、100,200,300のうちのどれか、在庫数は999個以下のランダムな数字とする。
// ex)
// apple,100, 345
// banana,200,247
// orange,300,987

// 既存の果物の配列
$fruits = ['apple', 'banana', 'orange', 'grape', 'pineapple'];

// CSVファイルへのパス
$directoryPath = './csv/dev/fruits/';
$filePath = $directoryPath . 'fruits.csv';

// ディレクトリが存在しない場合に再帰的にディレクトリを作成
if (!file_exists($directoryPath)) {
    mkdir($directoryPath, 0777, true);
}

// CSVファイルを開く
$file = new SplFileObject($filePath, 'w');

// 各果物に金額と在庫数を追加してCSVに書き込む
foreach ($fruits as $fruit) {
    // 金額と在庫数をランダムに生成
    $price = [100, 200, 300][array_rand([100, 200, 300])];
    $stock = rand(0, 999);
    
    // CSVに書き込み
    $file->fputcsv([$fruit, $price, $stock]);
}