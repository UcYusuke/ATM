<?php

// # 69 ATMを作成しよう
// コマンドラインから実行すること
// 要件定義
// ・残額、入金、引き出しの機能を実装
// 実際にATMに必要な機能をリストアップして、ご自由に開発してみてください！

//メッセージの設定
define(
    'MESSAGES',
    array(
        'SELECT_MENU' => sprintf("%s : %s / %s : %s / %s : %s", Atm::MENU_CHECK_BALANCE, Atm::MENUS[Atm::MENU_CHECK_BALANCE], Atm::MENU_DEPOSIT, Atm::MENUS[Atm::MENU_DEPOSIT], Atm::MENU_WITHDRAW, Atm::MENUS[Atm::MENU_WITHDRAW]) . PHP_EOL . "ご希望のメニューを入力してください >>> ",
        'SELECT_CONTINUE' => sprintf("操作を続けますか？ (やめる : %s / 続ける : %s) ", Atm::QUIT, Atm::CONT) . ">>> ",
        'CHECK' => "現在の残高 : ¥",
        'INPUT_DEPOSIT' => "入金金額を入力してください >>> ",
        'INPUT_WITHDRAW' => "引き出し金額を入力してください >>> ",
        'INPUT_USERID' => "ユーザIDを入力してください 例)id001 >>> ",
        'INPUT_PASSWORD' => "暗証番号を入力してください >>> ",
        'SUCCESS_LOGIN' => "ログインに成功しました。ユーザ名 : ",
        'SUCCESS_DEPOSIT' => "入金が成功しました。入金後残高 : ¥",
        'SUCCESS_WITHDRAW' => "引き出しが成功しました。引き出し後残高 : ¥",
        'FAIL_USERID' => "ユーザIDが存在しません",
        'FAIL_PASSWORD' => "パスワードが間違っています",
        'FAIL_WITHDRAW' => "残高不足です。引き出しに失敗しました",
        'ERROR_SELECTMENU' => "! Error : 1,2または3で入力してください",
        'ERROR_AMOUNT' => "! Error : 0より大きい数字で入力してください",
        'ERROR_SELECTCONTINUE' => "! Error : 半角英字の「q」または「c」で入力してください",
        'THANKS' => "ご利用いただきありがとうございました",
    )
);

class User extends Atm
{
    //ユーザリスト
    const USER_LIST = [
        'id001' => [
            'name' => 'ootani shohei',
            'pass' => 'abc'
        ],
        'id002' => [
            'name' => 'fujinami shintaro',
            'pass' => 'def'
        ],
        'id003' => [
            'name' => 'yoshida masataka',
            'pass' => 'ghi'
        ],
    ];

    /**
     * ユーザIDをUSER_LISTと照合しバリデーション
     * @param int $id ユーザID
     * @return bool ユーザIDがUSER_LISTに存在しない場合false,そうでない場合trueを返す
     */
    public static function isExistById($id)
    {
        //ユーザが存在しないとき
        if (array_key_exists($id, self::USER_LIST) === false) {
            Atm::outputMessage('FAIL_USERID'); //エラーメッセージを表示
            return false;
        } 
        //ユーザが存在するとき
        return true; 
    }

    /**
     * ユーザIDに紐づくユーザを返す
     * @param int $id ユーザID
     * @return str ユーザIDに紐づくユーザ名を、USER_LISTから返す
     */
    public static function findById($id)
    {
        return self::USER_LIST[$id]['name']; 
    }

}

class Atm{
    //メニューの設定
    const MENU_CHECK_BALANCE = 1; 
    const MENU_DEPOSIT = 2; 
    const MENU_WITHDRAW = 3; 
    const MENUS = [
        Atm::MENU_CHECK_BALANCE => '残高確認', 
        Atm::MENU_DEPOSIT => '入金',
        Atm::MENU_WITHDRAW => '引き出し'
    ];
    //操作続行選択の設定
    const QUIT = 'q'; 
    const CONT = 'c'; 

    private $accountBalance; //残高
    private $user;

    /**
     * 初期残高を設定
     * @param int 初期残高
     */
    public function __construct($initialBalance)
    {
        //初期残高の設定
        $this->accountBalance = $initialBalance;

        //ログイン処理
        $this->login();
    }

    /**
     * 一連の流れを行うメイン処理
     */
    public function main()
    {
        //メニュー選択
        $selected_menu = $this->selectMenu();

        //残高確認
        if ($selected_menu === Atm::MENU_CHECK_BALANCE) {
            $this->checkBalance() . PHP_EOL;
        }

        //入金
        if ($selected_menu === Atm::MENU_DEPOSIT) {
            $this->deposit() . PHP_EOL;
        }

        //引き出し
        if ($selected_menu === Atm::MENU_WITHDRAW) {
            $this->withdraw() . PHP_EOL;
        }

        //操作を続けるか選択
        $this->selectContinue();
    }

      /**
     * パスワードをUSER_LISTと照合しバリデーション
     * @param int $input 標準入力された値
     * @return bool 標準入力された値がUSER_LISTと異なる場合false,そうでない場合trueを返す
     */
    public function validatePass($id, $pass)
    {
        //パスワードの照合
        if($pass !== User::USER_LIST[$id]['pass']){
            //パスワードが一致しない場合
            Atm::outputMessage('FAIL_PASSWORD'); //エラーメッセージを表示
            return false; 
        }
        //パスワードが一致する場合
        return true;
    }

     /**
     * ログイン処理
     */
    private function login()
    {
        // ユーザーIDを入力
        $input_id = Atm::input('INPUT_USERID');

        // 暗証番号を入力
        $input_pass = Atm::input('INPUT_PASSWORD');

        //ユーザが存在するとき
        if (User::isExistById($input_id) === true) {
            //パスワードの照合
            if($this->validatePass($input_id, $input_pass) === true){
                //ログイン成功
                $user = User::findById($input_id); //ログイン成功ならuserプロパティにユーザー情報をセット
                //ログイン成功のメッセージを表示
                Atm::outputMessage('SUCCESS_LOGIN'); 
                echo $user;
            } else {
                //パスワードが一致しない場合
                return $this->login(); //入力NGなら再帰処理
            }
        } else {
            //ユーザが存在しないとき
            return $this->login(); //入力NGなら再帰処理
        };
    }

    /**
     * メッセージを出力
     * @param const 出力したいメッセージのキー名
     */
    public static function outputMessage($message)
    {
        echo PHP_EOL . MESSAGES[$message];
    }

    /**
     * 標準入力
     * @param const 入力を促すメッセージのキー名
     * @return string 標準入力された値 
     */
    public function input($message)
    {
        $this->outputMessage($message); //入力メッセージ
        $input = trim(fgets(STDIN));
        return $input;
    }

    /**
     * メニュー選択のバリデーション
     * @param int $input 標準入力された値
     * @return bool 標準入力された値がメニューのキーとして存在しない場合false,そうでない場合trueを返す
     */
    function validateMenu($input)
    {
        if (isset(Atm::MENUS[$input]) === false) {
            $this->outputMessage('ERROR_SELECTMENU');
            return false;
        }
        return true;
    }

    /**
     * 入金金額のバリデーション
     * @param int $input 標準入力された値
     * @return bool 標準入力された値が0以下の場合false,そうでない場合trueを返す
     */
    function validateDeposit($input)
    {
        if ($input <= 0) {
            $this->outputMessage('ERROR_AMOUNT');
            return false;
        }
        return true;
    }

    /**
     * 引き出し金額のバリデーション
     * @param int $input 標準入力された値
     * @return bool 標準入力された値が0以下もしくは残高より大きい場合false,そうでない場合trueを返す
     */
    function validateWithdraw($input)
    {
        if ($input <= 0) {
            $this->outputMessage('ERROR_AMOUNT');
            return false;
        }
        if ($input > $this->accountBalance) {
            $this->outputMessage('FAIL_WITHDRAW'); //引き出し失敗のメッセージ
            return false;
        }
        return true;
    }

    /**
     * メニュー選択
     * @return int 標準入力されたメニュー選択番号を返す
     */
    public function selectMenu()
    {
        $input = (int)$this->input('SELECT_MENU');

        if ($this->validateMenu($input) === false) {
            return $this->selectMenu(); //入力NGなら再帰処理
        };
        return $input;
    }

    /**
     * 残高確認
     * @return string,int 現在の残高を返す
     */
    public function checkBalance()
    {
        echo MESSAGES['CHECK'] . number_format($this->accountBalance);
    }

    /**
     * 入金
     * @return string,int 入金成功のメッセージと, 入金後の残高を返す
     */
    public function deposit()
    {
        $input = (int)$this->input('INPUT_DEPOSIT');

        if ($this->validateDeposit($input) === false) {
            return $this->deposit(); //入力NGなら再帰処理
        }
        $this->accountBalance += $input;
        echo MESSAGES['SUCCESS_DEPOSIT'] . number_format($this->accountBalance); //入金成功のメッセージ
    }

    /**
     * 引き出し
     * @return string,int 引き出し成功のメッセージと, 引き出し後の残高を返す
     */
    public function withdraw()
    {
        $input = (int)$this->input('INPUT_WITHDRAW');

        if ($this->validateWithdraw($input) === false) {
            return $this->withdraw(); //入力NGなら再帰処理
        } else {
            $this->accountBalance -= $input;
            echo MESSAGES['SUCCESS_WITHDRAW'] . number_format($this->accountBalance); //引き出し成功のメッセージ
        }
    }

    /**
     * 操作を続けるかどうか選択
     * @return function 続けない場合はコメントを出力して終了。操作を続ける場合は、メイン関数を再帰処理。
     */
    public function selectContinue() 
    {
        $input = $this->input('SELECT_CONTINUE');

        if ($input === Atm::QUIT) {
            $this->outputMessage('THANKS');
            exit();
        } else if ($input === Atm::CONT) {
            return $this->main(); //操作を続ける場合はメイン処理を再度実行
        } else {
            $this->outputMessage('ERROR_SELECTCONTINUE');
            return $this->selectContinue(); //入力NGの場合は、再帰処理
        }
    }
}

//初期残高設定
$initialBalance = 1000;
$atm = new Atm($initialBalance);

//メイン処理実行
$atm->main();
