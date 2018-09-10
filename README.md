===========================================
#GMO CREDITCARD API Library プロトコル・タイプ
===========================================
Overview
php library
twig html

## Description
GMOプロトコル・タイプ(カード決済 インタフェース仕様)　php library

php : Gmo.php
    *//GMO会員登録
    *//カード登録／更新 
    *//カード更新
    *//カード削除
    *//取引登録
    *//カード参照
    *//カード請求
    *//会員IDによるカード請求
twig : gmo.twig
    *//javascript token

##初期設定
`    public function __construct($conf)
    {
        $this->SiteID        =  $conf['SiteID'];      
        $this->SitePass      =  $conf['SitePass'];
        $this->ShopID        =  $conf['ShopID'];
        $this->ShopPass      =  $conf['ShopPass'];
        $this->EntryTran     =  $conf['EntryTran'];
        $this->ExecTran      =  $conf['ExecTran'];
        $this->AlterTran     =  $conf['AlterTran'];
        $this->TdVerify      =  $conf['TdVerify'];
        $this->ChangeTran    =  $conf['ChangeTran'];
        $this->SaveCard      =  $conf['SaveCard'];
        $this->DeleteCard    =  $conf['DeleteCard'];
        $this->SearchCard    =  $conf['SearchCard'];
        $this->TradedCard    =  $conf['TradedCard'];
        $this->SaveMember    =  $conf['SaveMember'];
        $this->DeleteMember  =  $conf['DeleteMember'];
        $this->SearchMember  =  $conf['SearchMember'];
        $this->UpdateMember  =  $conf['UpdateMember'];
        $this->SearchTrade   =  $conf['SearchTrade'];
    }
`

## Licence
[MIT](https://github.com/tcnksm/tool/blob/master/LICENCE)

## Author
[tcnksm](https://github.com/camelG)