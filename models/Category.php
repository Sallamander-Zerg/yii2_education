<?php
/*
 * Файл models/Category.php
 */
namespace app\models;
use yii\db\ActiveRecord;

class Category extends ActiveRecord {
    public static function tableName() 
    {
        return 'category';
    }
    public function getProducts() {
        // связь таблицы БД `category` с таблицей `product`
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    public function getParent() {
        // связь таблицы БД `category` с таблицей `category`
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }
    public function getChildren() {
        // связь таблицы БД `category` с таблицей `category`
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }
}
?>