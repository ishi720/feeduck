# Feeduck（フィーダック）

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/befb1cdd3f23489e9dcbb28850de5d25)](https://app.codacy.com/gh/ishi720/feeduck/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## 概要

laravelで作成したRSSを管理するサービス

## 環境

```bash
$ php -v
PHP 8.3.36 (cli) (built: Oct 20 2026 04:21:11) (ZTS Visual C++ 2019 x64)
Copyright (c) The PHP Group
Zend Engine v4.3.36, Copyright (c) Zend Technologies

$ php artisan --version
Laravel Framework 13.40.0
```

## セットアップ

### 1. .envの設定

- .env.exampleをコピーして.envを作成
- .envを環境に合わせて設定

### 2. モジュールのインストール

```shell
$ yarn install
$ composer install
```

### 3. dbのセットアップ

```shell
$ php artisan migrate
```


### 4. 外部サービスのAPIキーの設定

`config/const.php`に取得したAPIキーを設定する

- [Googleアナリティクス](https://developers.google.com/analytics?hl=ja)


### 5. 起動

```shell
$ php artisan serve
```

## コマンド

### ER図の生成

DBのスキーマから[ER図](docs/er.md)を生成する

```shell
$ php artisan erd:generate
```

## Ex. Apache(xampp)で起動するhttpd.confの設定例

- httpd.conf

```conf
Alias /feeduck "C:/repos/feeduck/public/"
<Directory "C:/repos/feeduck/public/">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```
