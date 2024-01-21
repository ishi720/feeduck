# Feeduck(フィーダック)

### 概要

laravelで作成したRSSを管理するサービス

### 環境

- php: 7.2.17
- laravel: 6.3.0

### セットアップ

- モジュールのインストール

```shell
$ yarn install
$ composer install
```

- dbのセットアップ

```shell
$ php artisan migrate
```


- 起動

```shell
$ php artisan serve
```

### apache(xampp)で起動する方法

- httpd.conf

```conf
Alias /feeduck "C:/repos/feeduck/public/"
<Directory "C:/repos/feeduck/public/">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```




