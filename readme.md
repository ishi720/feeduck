# Feeduck（フィーダック）

## 概要

laravelで作成したRSSを管理するサービス

## 環境

```bash
$ php -v
PHP 8.1.17 (cli) (built: Mar 14 2023 23:07:43) (ZTS Visual C++ 2019 x64)
Copyright (c) The PHP Group
Zend Engine v4.1.17, Copyright (c) Zend Technologies

$ php artisan --version
Laravel Framework 8.83.27
```

## セットアップ

### 1. モジュールのインストール

```shell
$ yarn install
$ composer install
```

### 2. dbのセットアップ

```shell
$ php artisan migrate
```


### 3. 外部サービスのAPIキーの設定

`config/const.php`に取得したAPIキーを設定する

- [Gooラボ](https://labs.goo.ne.jp/)
- [Aoogleアナリティクス](https://developers.google.com/analytics?hl=ja)


### 4. 起動

```shell
$ php artisan serve
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
