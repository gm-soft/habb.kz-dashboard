<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 19.11.2016
 * Time: 10:11
 */
class User extends BaseInstance
{

    /** @var string Логин пользователя */
    public $login;

    /** @var string Зашифрованный пароль пользователя */
    public $password;

    /** @var string Уникальный зашифрованный хэш пользователя, который кладется в Куки */
    public $hash;

    /** @var int Уровень прав доступа пользователя */
    public $permission;

    function __construct($id = -1)
    {
        $this->id = $id;
        $this->login = null;
        $this->password = null;
        $this->permission = 1;
        $this->createdAt = new DateTime();
        $this->hash = null;
    }

    public function fill( array $row ) {
        $this->id           = $row["user_id"];
        $this->login        = $row["user_login"];
        $this->password     = $row["user_password"];
        $this->permission   = $row["user_permission"];
        $this->hash         = $row["user_hash"];
        $this->createdAt   = DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"]);
    }

    public static function fromDatabase(array $databaseRow)
    {
        $instance = new self();
        $instance->fill( $databaseRow );
        return $instance;
    }

    public static function fromUserData($login, $password)
    {
        $instance = new self();
        $instance->login = $login;
        $instance->password = md5(md5($password));

        $instance->hash = md5(self::generateCode(10));
        return $instance;
    }

    public function generateNewHash(){
        $this->hash = md5(self::generateCode(10));
    }

    public function resetPassword($newPassword){
        $this->password = md5(md5($newPassword));
    }

    /**
     * Генерирует строку со случайным набором чисел и символов
     *
     * @param int $length
     * @return string
     */
    public static function generateCode($length=6) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
        }

        return $code;

    }

    /**
     * Возвращает true/false в зависимости от равенства введенного пароля и пароля юзера
     *
     * @param $password - пароль в исходном виде
     * @return bool
     */
    public function validatePassword($password){
        $password = md5(md5($password));
        $check = $this->password == $password;
    
        return $check;
    }

    public function checkPermission($requiredLevel){
        return $this->permission >= $requiredLevel;
    }

    /**
     * Возвращает массив с данными для заполнения формы
     *
     * @return array
     */
    public function getAsFormArray(){

        $formData = [
            "user_id" => $this->id,
            "user_login" => $this->login,
            "user_password" => $this->password,
            "user_permission" => $this->permission,
        ];
        return $formData;
    }


    //--------------------------
    //--------------------------
    /**
     * Функция возврата пользователя из базы данных по искомому значению.
     * Возвращает массив с полями result и data. Если запрос был успешен, то
     * result равен true. Если пользователь был найден, то data будет содержать этот объект,
     * иначе null
     *
     * @param mixed $searchable - Искомое значение
     * @param $mysql MysqlHelper
     * @param string $field - названеи поля, по которому осуществлять поиск
     * @return null|User
     */
    public static function getInstanceFromDatabase($searchable, $mysql, $field = "user_login") {


        $query = "select * from ".TABLE_USERS." where ".$field."='".$searchable."'";
        $data = $mysql->executeQuery($query);

        if (
            $data["result"] != true ||
            is_null($data["data"])
        ) return null;

        return self::fromDatabase($data["data"]);
    }


    /**
     * Возвращает список пользователей системы
     *
     * @param $mysql MysqlHelper
     * @return null|User[]
     */
    public static function getInstancesFromDatabase($mysql){

        $query = "select * from ".TABLE_USERS;

        $query_result = $mysql->selectData($query);
        if ($query_result["result"] == true) {
            $instances = [];
            foreach ($query_result["data"] as $key => $value) {


                $instance = self::fromDatabase($value);
                $instances[] = $instance;
            }
            $query_result = $instances;
        }

        return $query_result;
    }



    /**
     * Функция добавляет нового пользователя в систему. Возвращает id последней добавленной записи
     *
     * @param $mysql MysqlHelper
     * @return array(
     *      "result" => true/false,
     *      "data" => id/ошибка
     * )|null
     */
    public function insertToDatabase($mysql){

        $query = "insert into `".TABLE_USERS."` (`user_login`, `user_password`, `user_hash`, `user_permission`) values (".
            "'".$this->login."', '".$this->password."', '".$this->hash."', ".$this->permission." )";
        $query_result = $mysql->executeQuery($query);
        if ($query_result["result"] != true) {
            return $query_result;
        }
        $query_result["data"] = $mysql->getLastInsertedId();
        $this->id = $query_result["data"];
        return $query_result;
    }

    /**
     * Обновляет хэш в БД
     * @param $mysql MysqlHelper
     * @return mixed
     */
    public function updateUserHash($mysql){
        $query = "UPDATE `".TABLE_USERS."` SET ".
            "`user_hash`='".$this->hash."'".
            " where user_id=".$this->id;
        $query_result = $mysql->executeQuery($query);
        return $query_result;
    }

    /**
     * Обновляет пользовательские данные
     *
     * @param $mysql MysqlHelper
     * @return array|null
     */
    public function updateInDatabase($mysql){
        $query = "update `".TABLE_USERS."` set ".
            "`user_login`='".$this->login."', ".
            "`user_password`='".$this->password."', ".
            "`user_permission`='".$this->permission."', ".
            "`user_hash`='".$this->hash."'".
            " where user_id=".$this->id;
        $query_result = $mysql->executeQuery($query);
        return $query_result;
    }

    /**
     * Удаляет пользователя из системы
     *
     * @param $mysql MysqlHelper
     * @return array|null
     */
    public function deleteFromDatabase($mysql){
        $searchable = $this->id;
        $field = "user_id";
        $tableName = TABLE_USERS;
        return $mysql->deleteInstance($searchable, $field, $tableName);
    }

    public function getPermissionTitle(){
        switch($this->permission){
            case 0:
                return "Демонстрационный аккаунт";
            case 1:
                return "Пользователь";
            case 2:
                return "Пользователь с повышенными правами";
            case 4:
                return "Бог";
            default:
                return "Права доступа не определены";
        }
    }


    static function filterInstancesFromDatabase($mysql, array $filterConditions, $condition, $withSort, $sortBy, $sortType)
    {
        // TODO: Implement filterInstancesFromDatabase() method.
        return self::getInstancesFromDatabase($mysql);
    }
}