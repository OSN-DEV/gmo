<?php
class Gmo
{
    private $SiteID;
    private $SitePass;
    private $ShopID;
    private $ShopPass;
    private $EntryTran;
    private $ExecTran;
    private $AlterTran;
    private $TdVerify;
    private $ChangeTran;
    private $SaveCard;
    private $DeleteCard;
    private $SearchCard;
    private $TradedCard;
    private $SaveMember;
    private $DeleteMember;
    private $SearchMember;
    private $UpdateMember;
    private $SearchTrade;

    public function __construct($config)
    {
        $this->SiteID = $config['SITEID'];
        $this->SitePass = $config['SITEPASS'];
        $this->ShopID = $config['SHOPID'];
        $this->ShopPass = $config['SHOPPASS'];
        $this->EntryTran = $config['ENTRYTRAN'];
        $this->ExecTran = $config['EXECTRAN'];
        $this->AlterTran = $config['ALTERTRAN'];
        $this->TdVerify = $config['TDVERIFY'];
        $this->ChangeTran = $config['CHANGETRAN'];
        $this->SaveCard = $config['SAVECARD'];
        $this->DeleteCard = $config['DELETECARD'];
        $this->SearchCard = $config['SEARCHCARD'];
        $this->TradedCard = $config['TRADEDCARD'];
        $this->SaveMember = $config['SAVEMEMBER'];
        $this->DeleteMember = $config['DELETEMEMBER'];
        $this->SearchMember = $config['SEARCHMEMBER'];
        $this->UpdateMember = $config['UPDATEMEMBER'];
        $this->SearchTrade = $config['SEARCHTRADE'];
    }

    //GMO会員登録
    public function SaveMember($MemberID, $MemberName)
    {

        $url = $this->SaveMember;

        $data = [
            'SiteID' => $this->SiteID,
            'SitePass' => $this->SitePass,
            'MemberID' => $MemberID
        ];

        $result = $this->curlData($url, $data);

        return $result;

    }

    //カード登録／更新 
    public function SaveCard($Token, $MemberID)
    {

        $url = $this->SaveCard;
        $data = [
            'SiteID' => $this->SiteID,
            'SitePass' => $this->SitePass,
            'Token' => $Token,
            'DefaultFlag' => 1,
            'MemberID' => $MemberID
        ];

        $result = $this->curlData($url, $data);

        return $result;

    }

    //カード更新
    public function UpdateCard($Token, $MemberID)
    {

        $url = $this->SaveCard;
        $data = [
            'SiteID' => $this->SiteID,
            'SitePass' => $this->SitePass,
            'Token' => $Token,
            'MemberID' => $MemberID,
            'DefaultFlag' => 1,
            'CardSeq' => 0
        ];

        $result = $this->curlData($url, $data);

        return $result;

    }

    //カード削除 
    public function DeleteCard($MemberID)
    {

        $url = $this->DeleteCard;
        $data = [
            'SiteID' => $this->SiteID,
            'SitePass' => $this->SitePass,
            'MemberID' => $MemberID,
            'CardSeq' => 0
        ];

        $result = $this->curlData($url, $data);

        return $result;

    }

    public function Entry($OrderID, $Amount)
    {
        $url = $this->EntryTran;

        $data = [
            'ShopID' => $this->ShopID,
            'ShopPass' => $this->ShopPass,
            'OrderID' => $OrderID,
            'JobCd' => 'CAPTURE',
            'Amount' => $Amount,
        ];

        $result = $this->curlData($url, $data);

        return $result;

    }

    public function SearchCards($MemberID)
    {
        $url = $this->SearchCard;

        $data = [
            'SiteID' => $this->SiteID,
            'SitePass' => $this->SitePass,
            'MemberID' => $MemberID,
            'SeqMode' => 0
        ];

        $result = $this->curlData($url, $data);

        return $result;

    }

    public function Exec($AccessID, $AccessPass, $OrderID, $MemberID, $CardSeq)
    {
        $url = $this->ExecTran;

        $data = [
            'SiteID' => $this->SiteID,
            'SitePass' => $this->SitePass,
            'AccessID' => $AccessID,
            'AccessPass' => $AccessPass,
            'OrderID' => $OrderID,
            'Method' => 1,
            'MemberID' => $MemberID,
            'CardSeq' => $CardSeq,
        ];

        $result = $this->curlData($url, $data);

        return $result;

    }

    public function ExecByMemberID($MemberID, $OrderID, $Amount)
    {
        $result_entry = $this->Entry($OrderID, $Amount);
        if ($result_entry['ErrInfo']) {
            $result['error'] = 1;
            $result['alert'] = '"Error' . $result_entry['ErrInfo'] . '","取引登録失敗！' . $this->getErrMsg($result_entry['ErrInfo']) . '","error"';

            return $result;
        }
        $AccessID = $result_entry['AccessID'];
        $AccessPass = $result_entry['AccessPass'];

        $result_search = $this->SearchCards($MemberID);
        if ($result_search['ErrInfo']) {
            $result['error'] = 1;
            $result['alert'] = '"Error' . $result_search['ErrInfo'] . '","カード参照失敗！' . $this->getErrMsg($result_search['ErrInfo']) . '","error"';

            return $result;
        }

        if (strlen($result_search['DefaultFlag']) > 1) {
            $array = explode('|', $result_search['CardSeq']);
            $key = array_search('1', explode('|', $result_search['DefaultFlag']));
            $CardSeq = $array[$key];
        }

        if (strlen($result_search['DefaultFlag']) == '1') {
            $key = 1;
            $CardSeq = $result_search['CardSeq'];
        }

        if (!$key) {
            $result['error'] = 1;
            $result['alert'] = '"Error","デフォルトフラグが見つかりません。","error"';
            return $result;
        }

        $result = $this->Exec($AccessID, $AccessPass, $OrderID, $MemberID, $CardSeq);
        if (!$result) {
            $result['error'] = 1;
            $result['alert'] = '"Error","請求失敗！","error"';
        }

        return $result;

    }

    public function curlData($url, $data)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));

        if (!$result = curl_exec($ch)) {
            echo curl_error($ch);
        }

        curl_close($ch);
        parse_str($result, $array);

        return $array;
    }

    public function getErrMsg($ErrInfo)
    {
        $err = [
            'E00000000' => '',
            'E01010001' => 'ショップIDが指定されていません。',
            'E01020001' => 'ショップパスワードが指定されていません。',
            'E01030002' => '指定されたIDとパスワードのショップが存在しません。',
            'E01040001' => 'オーダーIDが指定されていません。',
            'E01040003' => 'オーダーIDが最大文字数を超えています。',
            'E01040010' => '既にオーダーIDが存在しています。',
            'E01040013' => 'オーダーIDに不正な文字が存在します。',
            'E01050001' => '処理区分が指定されていません。',
            'E01050002' => '指定された処理区分は定義されていません。',
            'E01050004' => '指定した処理区分の処理は実行出来ません。',
            'E01060001' => '利用金額が指定されていません。',
            'E01060005' => '利用金額が最大桁数を超えています。',
            'E01060006' => '利用金額に数字以外の文字が含まれています。',
            'E01070005' => '税送料が最大桁数を超えています。',
            'E01070006' => '税送料に数字以外の文字が含まれています。',
            'E01080007' => '3Dセキュア使用フラグに0,1以外の値が指定されています。',
            'E01090001' => '取引IDが指定されていません。',
            'E01100001' => '取引パスワードが指定されていません。',
            'E01110002' => '指定されたIDとパスワードの取引が存在しません。',
            'E01120008' => 'カード種別の書式が誤っています。',
            'E01130002' => '指定されたカード略称が存在しません。',
            'E01140007' => '対応支払方法に0,1以外の値が指定されています。',
            'E01140003' => '対応支払方法が最大文字数を超えています。',
            'E01150007' => '対応分割回数に0,1以外の値が指定されています。',
            'E01160007' => '対応ボーナス分割回数に0,1以外の値が指定されています。',
            'E01170001' => 'カード番号が指定されていません。',
            'E01170003' => 'カード番号が最大文字数を超えています。',
            'E01170006' => 'カード番号に数字以外の文字が含まれています。',
            'E01170011' => 'カード番号が10桁～16桁の範囲ではありません。',
            'E01180001' => '有効期限が指定されていません。',
            'E01180003' => '有効期限が4桁ではありません。',
            'E01180006' => '有効期限に数字以外の文字が含まれています。',
            'E01190001' => 'サイトIDが指定されていません。',
            'E01200001' => 'サイトパスワードが指定されていません。',
            'E01210002' => '指定されたIDとパスワードのサイトが存在しません。',
            'E01220001' => '会員IDが指定されていません。',
            'E01230001' => 'カード登録連番が指定されていません。',
            'E01230006' => 'カード登録連番に数字以外の文字が含まれています。',
            'E01230009' => 'カード登録連番が最大登録可能数を超えています。',
            'E01240002' => '指定されたサイトIDと会員ID、カード連番のカードが存在しません。',
            'E01250010' => 'カードパスワードが一致しません。',
            'E01260001' => '支払方法が指定されていません。',
            'E01250002' => '指定された支払方法が存在しません。',
            'E01260010' => '指定された支払方法はご利用できません。',
            'E01270001' => '支払回数が指定されていません。',
            'E01270005' => '支払回数が1～2桁ではありません。',
            'E01270006' => '支払回数の数字以外の文字が含まれています。',
            'E01270010' => '指定された支払回数はご利用できません。',
            'E01280012' => '加盟店URLの値が最大バイト数を超えています。',
            'E01290001' => 'HTTP_ACCEPTが指定されていません。',
            'E01300001' => 'HTTP_USER_AGENTが指定されていません。',
            'E01310001' => '使用端末が指定されていません。',
            'E01310007' => '使用端末に0,1以外の値が指定されています。',
            'E01320012' => '加盟店自由項目1の値が最大バイト数を超えています。',
            'E01330012' => '加盟店自由項目2の値が最大バイト数を超えています。',
            'E01340012' => '加盟店自由項目3の値が最大バイト数を超えています。',
            'E01350001' => 'MDが指定されていません。',
            'E01360001' => 'PaREsが指定されていません。',
            'E01370012' => '3Dセキュア表示店舗名の値が最大バイト数を超えています。',
            'E01380007' => '決済方法フラグに0,1以外の値が指定されています。',
            'E01390002' => '指定されたサイトIDと会員IDの組み合わせが存在しません。',
            'E01390010' => '指定されたサイトIDと会員IDの組み合わせは既に存在しています。',
            'E01460008' => 'セキュリティコードが正しくありません',
            'E11010001' => 'この取引は既に決済が終了しています。',
            'E11010002' => 'この取引は決済が終了していませんので、変更する事が出来ません。',
            'E11010003' => 'この取引は指定処理区分処理を行う事が出来ません。',
            'E21010001' => '3Dセキュア認証に失敗しました。もう一度、購入画面からやり直して下さい。',
            'E21020001' => '3Dセキュア認証に失敗しました。もう一度、購入画面からやり直して下さい。',
            'E21020002' => '3Dセキュア認証がキャンセルされました。もう一度、購入画面からやり直して下さい。',
            'E41170002' => '入力されたカードの会社には対応していません。別のカード番号を入力して下さい。',
            'E41170099' => 'カード番号に誤りがあります。再度確認して入力して下さい。',
            'E61010001' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            'E61010002' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            'E61010003' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            'E90010001' => '現在処理を行っているのでもうしばらくお待ち下さい。',
            'E91019999' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            'E91029999' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            'E91099999' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C010000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C030000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C120000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C130000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C140000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C150000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C500000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C510000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C530000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C540000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C550000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C560000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C570000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C580000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C600000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C700000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C710000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C720000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C730000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C740000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C750000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C760000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C770000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42C780000' => '決済処理に失敗しました。申し訳ございませんが、しばらくした後にもう一度購入画面からやり直してください。',
            '42G020000' => 'カード残高が不足しているために、決済が完了できませんでした。',
            '42G030000' => 'カード限度額を超えているために、決済が完了できませんでした。',
            '42G040000' => 'カード残高が不足しているために、決済が完了できませんでした。',
            '42G050000' => 'カード限度額を超えているために、決済が完了できませんでした。',
            '42G120000' => 'このカードでは取引をする事が出来ません。',
            '42G220000' => 'このカードでは取引をする事が出来ません。',
            '42G300000' => '',
            '42G420000' => '暗証番号が誤っていた為に、決済を完了する事が出来ませんでした。',
            '42G540000' => 'このカードでは取引をする事が出来ません。',
            '42G550000' => 'カード限度額を超えているために、決済が完了できませんでした。',
            '42G560000' => '',
            '42G600000' => 'このカードでは取引をする事が出来ません。',
            '42G610000' => 'このカードでは取引をする事が出来ません。',
            '42G650000' => 'カード番号に誤りがあるために、決済を完了できませんでした。',
            '42G670000' => '商品コードに誤りがあるために、決済を完了できませんでした。',
            '42G680000' => '金額に誤りがあるために、決済を完了できませんでした。',
            '42G690000' => '税送料に誤りがあるために、決済を完了できませんでした。',
            '42G700000' => 'ボーナス回数に誤りがあるために、決済を完了できませんでした。',
            '42G710000' => 'ボーナス月に誤りがあるために、決済を完了できませんでした。',
            '42G720000' => 'ボーナス額に誤りがあるために、決済を完了できませんでした。',
            '42G730000' => '支払開始月に誤りがあるために、決済を完了できませんでした。',
            '42G740000' => '分割回数に誤りがあるために、決済を完了できませんでした。',
            '42G750000' => '分割金額に誤りがあるために、決済を完了できませんでした。',
            '42G760000' => '初回金額に誤りがあるために、決済を完了できませんでした。',
            '42G770000' => '業務区分に誤りがあるために、決済を完了できませんでした。',
            '42G780000' => '支払区分に誤りがあるために、決済を完了できませんでした。',
            '42G790000' => '照会区分に誤りがあるために、決済を完了できませんでした。',
            '42G800000' => '取消区分に誤りがあるために、決済を完了できませんでした。',
            '42G810000' => '取消取扱区分に誤りがあるために、決済を完了できませんでした。',
            '42G830000' => '有効期限に誤りがあるために、決済を完了できませんでした。',
            '42G950000' => '',
            '42G960000' => 'このカードでは取引をする事が出来ません。',
            '42G970000' => 'このカードでは取引をする事が出来ません。',
            '42G980000' => 'このカードでは取引をする事が出来ません。',
            '42G990000' => 'このカードでは取引をする事が出来ません。',
        ];

        if ($err[$ErrInfo]) {
            $result = $err[$ErrInfo];
        } else {
            $result = '該当エラーコードがありません。';
        }

        return $result;
    }

}