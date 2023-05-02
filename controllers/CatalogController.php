<?php
namespace app\controllers;
use yii\web\Controller;
use app\models\Category;
use app\models\Product;

class CatalogController extends Controller {

    public function actionIndex() {
        // получаем корневые категории
        $categories = Category::find()->where(['parent_id' => 0])->all();
        return $this->render('catalog', ['categories' => $categories]);
    }

    public function actionCategory() {
        // получаем информацию о категории с идентификатором 5
        $category = Category::findOne(5);
        // получаем товары категории с идентификатором 5
        $products = Product::find()->where(['category_id' => 5])->all();
        return $this->render('category', ['category' => $category, 'products' => $products]);
    }

    public function actionProduct() {
        // получаем информацию о товаре с идентификатором 2
        $product = Product::findOne(2);
        return $this->render('product', ['product' => $product]);
    }
}
?>