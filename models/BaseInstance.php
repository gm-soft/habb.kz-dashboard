<?php

/**
 * Created by PhpStorm.
 * User: Next
 * Date: 22.02.2017
 * Time: 13:50
 */
abstract class BaseInstance implements IDatabaseObject
{
    /** @var int ID сущности в базе данных*/
    public $id = -1;

    /** @var null|string Комментарий пользователя к сущности. Служит для хранения пользовательской информации о сущности*/
    public $comment = null;

    /** @var string|null Строковое отображение последней операции над сущностью */
    public $lastOperation = null;

    /** @var  DateTime Время создания*/
    public $createdAt;

    /** @var DateTime Время обновления */
    public $updatedAt;

    public function getVarExport(){
        $result = var_export($this, true);
        return $result;
    }

    /**
     * Преобразовывает строку в формате json в объект-json. Возвратит исходный объект в случае ошибки
     * @return string
     */
    public function toJson(){
        $content = json_encode($this);
        return $content;
    }

}