<?php
$docroot = $_SERVER['DOCUMENT_ROOT'];

require_once $docroot . '/lib/Gmo.php';
require_once $docroot . '/config.inc.php';

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

$gmo = new Gmo($config);


?>


...中略


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="<?= GMOJS ?>"></script>
<script>
var Multipayment, CryptoJS, JSEncryptExports, JSEncrypt, ASN1, Base64, Hex, KJUR;
function execPurchase(response) {

    var tokenError = {
        '000': 'トークン取得正常終了',
        '100': 'カード番号必須チェックエラー',
        '101': 'カード番号フォーマットエラー(数字以外を含む)',
        '102': 'カード番号フォーマットエラー(10-16 桁の範囲外)',
        '110': '有効期限必須チェックエラー',
        '111': '有効期限フォーマットエラー(数字以外を含む)',
        '112': '有効期限フォーマットエラー(6 又は 4 桁以外)',
        '113': '有効期限フォーマットエラー(月が 13 以上)',
        '121': 'セキュリティコードフォーマットエラー(数字以外を含む)',
        '122': 'セキュリティコード桁数エラー',
        '131': '名義人フォーマットエラー(半角英数字、一部の記号以外を含む)',
        '132': '名義人フォーマットエラー(51 桁以上)',
        '141': '発行数フォーマットエラー(数字以外を含む)',
        '142': '発行数フォーマットエラー(1-10 の範囲外)',
        '150': 'カード情報を暗号化した情報必須チェックエラー',
        '160': 'ショップ ID 必須チェックエラー',
        '161': 'ショップ ID フォーマットエラー(14 桁以上)',
        '162': 'ショップ ID フォーマットエラー(半角英数字以外)',
        '170': '公開鍵ハッシュ値必須チェックエラー',
        '180': 'ショップ ID または公開鍵ハッシュ値がマスターに存在しない',
        '190': 'カード情報(Encrypted)が復号できない',
        '191': 'カード情報(Encrypted)復号化後フォーマットエラー',
        '501': 'トークン用パラメータ(id)が送信されていない',
        '502': 'トークン用パラメータ(id)がマスターに存在しない',
        '511': 'トークン用パラメータ(cardInfo)が送信されていない',
        '512': 'トークン用パラメータ(cardInfo)が復号できない',
        '521': 'トークン用パラメータ(key)が送信されていない',
        '522': 'トークン用パラメータ(key)が復号できない',
        '531': 'トークン用パラメータ(callBack)が送信されていない',
        '541': 'トークン用パラメータ(hash)が存在しない',
        '551': 'トークン用 apikey が存在しない ID',
        '552': 'トークン用 apikey が有効ではない',
        '901': 'マルチペイメント内部のシステムエラー',
        '902': '処理が混み合っている'
    }
    if (response.resultCode == "000") {
        $("#token").val(response.tokenObject.token);
        $("form").submit();

    } else if (response.resultCode in tokenError) {
        alert(tokenError[response.resultCode]);
    } else {
        alert('エラー発生しました。');
    }
}

function doPurchase() {
    var settle = $('#settle_type_3').prop('checked');

    if (settle != false) {

        var carnum1, carnum2, carnum3, carnum4, cardno, expire, securitycode, holdername;

        var cardnum1 = document.getElementById("passwd1").value;
        var cardnum2 = document.getElementById("passwd2").value;
        var cardnum3 = document.getElementById("passwd3").value;
        var cardnum4 = document.getElementById("passwd4").value;
        var cardno = cardnum1 + cardnum2 + cardnum3 + cardnum4;
        var expire = document.getElementById("card_year").value + document.getElementById("card_month").value;
        var securitycode = document.getElementById("securitycode").value;
        var holdername = document.getElementById("card_holder_sei_kana").value + document.getElementById("card_holder_sei_kana").value;
        var tokennumber = 1;
        Multipayment.init("<?= $config['SHOPID'] ?>");
        Multipayment.getToken({
            cardno: cardno,
            expire: expire,
            securitycode: securitycode,
            holdername: holdername,
            tokennumber: tokennumber
        }, execPurchase);
    }
}
var submitForm = function(form, mode, option) {
    (form.mode||{}).value = (mode||'');

    if (typeof(option) !== 'undefined' && typeof(option.checked) !== 'undefined') {
        console.log(option.checked.id);
        if (!document.getElementById(option.checked.id).checked) {
            return alert(option.checked.message);
        }
    }
    var settle = $('#settle_type_3').prop('checked');
    if (settle != false && mode !== 'store') {
      doPurchase();
    }else{
       form.submit();
    }
};

</script>