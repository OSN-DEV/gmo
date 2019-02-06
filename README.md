# GMO CREDITCARD API Library プロトコル・タイプ

Overview  
php library  
twig html

## Description

GMO プロトコル・タイプ(カード決済 インタフェース仕様)　 php library

- [x] GMO 会員登録
- [x] カード登録／更新
- [x] カード更新
- [x] カード削除
- [x] 取引登録
- [x] カード参照
- [x] カード請求
- [x] 会員 ID によるカード請求

twig : gmo.twig  
 \*//javascript token

## 初期設定

```php
$config = [
    'SITEID' => DENKI_SITEID,
    'SITEPASS' => DENKI_SITEPASS,
    'SHOPID' => DENKI_SHOPID,
    'SHOPPASS' => DENKI_SHOPPASS,
    'ENTRYTRAN' => ENTRYTRAN,
    'EXECTRAN' => EXECTRAN,
    'ALTERTRAN' => ALTERTRAN,
    'TDVERIFY' => TDVERIFY,
    'CHANGETRAN' => CHANGETRAN,
    'SAVECARD' => SAVECARD,
    'DELETECARD' => DELETECARD,
    'SEARCHCARD' => SEARCHCARD,
    'TRADEDCARD' => TRADEDCARD,
    'SAVEMEMBER' => SAVEMEMBER,
    'DELETEMEMBER' => DELETEMEMBER,
    'SEARCHMEMBER' => SEARCHMEMBER,
    'UPDATEMEMBER' => UPDATEMEMBER,
    'SEARCHTRADE' => SEARCHTRADE
];
```

## Licence

[MIT](https://github.com/tcnksm/tool/blob/master/LICENCE)

## Author

[camelG](https://github.com/camelG)
