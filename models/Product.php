<?php
/*
 * Файл models/Product.php
 */
namespace app\models;
use yii\db\ActiveRecord;

class Product extends ActiveRecord {
    public static function tableName()
    {
        return 'product';
    }
}
?>