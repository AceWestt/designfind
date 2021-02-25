<?php
/**
 * Created by PhpStorm.
 * User: Ace
 * Date: 04.09.2018
 * Time: 22:55
 */

namespace app\controllers;





use app\models\Category;
use app\models\Course;
use app\models\LoginForm;
use app\models\Role;
use app\models\User;
use app\models\Vacancy;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AdminController extends Controller
{

    public $enableCsrfValidation = false;
    public $enableCookieValidation = false;
    public $layout = 'admin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['*'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Method' => ['POST', 'GET'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Request-Headers' => ['*'],
                    // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                ],
            ],
        ];
    }



    //    API ACTIONS


    public function actionLogin()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $attributes = \Yii::$app->request->post();

        $userr = User::find()->where(['username' => $attributes['username']])
            ->orWhere(['email'=>$attributes['username']])->one();
        if (isset($userr)) {
            if (\Yii::$app->security->validatePassword($attributes['password'], $userr['password_hash'])) {

                \Yii::$app->user->login($userr,3600*24*30);
                if (!\Yii::$app->user->isGuest) {

                    $id = \Yii::$app->user->identity->getId();

                    return array('status' => 'success');
                } else {
                    return array('status' => 'error', 'error'=> 'not logged');
                }

            } else {
                return array('status' => 'error', 'error' => 'incorrect password');
            }

        }
        else{
            return array('status'=>'error', 'error'=> $attributes['username'].' not found');
        }
    }

    public function actionDelete(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if(!Yii::$app->user->isGuest){
            $attrs = Yii::$app->request->post();

            $id = $attrs['id'];

            $vacancy = Vacancy::findOne($id);
            if(isset($vacancy)){
                if($vacancy->delete()){
                    return array('status'=>'ok');
                };
            }else{
                return 'ss';
            }
        }else return array('status'=>'error', 'msg'=>'not authorized');
    }

    public function actionConfirm(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if(!Yii::$app->user->isGuest){
            $attr = Yii::$app->request->post();

            $vacancy = Vacancy::findOne($attr['id']);
            $vacancy->status = 1;
            if($vacancy->save()){
                return array('status'=>'ok');
            }
        }
        else return array('status'=>'error', 'msg'=>'not authorized');
    }

    public function actionNewCat(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if(!Yii::$app->user->isGuest){
            $title = Yii::$app->request->post()['title'];
            if(isset($title)&&!preg_match('/^(\s)*$/', $title)){
                $cat = new Category();
                $cat->title = $title;
                if($cat->save()){
                    return array('status'=> 'ok');
                }
            }
            else{
                return array('status'=>'error', 'msg'=>'empty');
            }

        }
        return array('status'=>'error', 'msg'=>'not authorized');
    }

    public function actionEditCat(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if(!Yii::$app->user->isGuest){
            $title = Yii::$app->request->post()['title'];
            $id = Yii::$app->request->post()['id'];
            if(isset($title)&&!preg_match('/^(\s)*$/', $title)){
                $cat = Category::findOne($id);
                $cat->title = $title;
                if($cat->save()){
                    return array('status'=> 'ok');
                }
            }
            else{
                return array('status'=>'error', 'msg'=>'empty');
            }

        }
        return array('status'=>'error', 'msg'=>'not authorized');
    }

    public function actionDeleteCat(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if(!Yii::$app->user->isGuest){
            $id = Yii::$app->request->post()['id'];
            $cat = Category::findOne($id);
            $vacancies = Vacancy::find()->where(['category_id'=> $cat['id']])->asArray()->all();
            if(count($vacancies)<1){
                if($cat->delete()){
                    return array('status'=>'ok');
                }
            }
            else return array('status'=>'error', 'msg'=>'used');
        }
        else return array('status'=>'error', 'msg'=>'not authorized');
    }



//    VIEW ACTIONS

    public function actionIndex(){
        if(!Yii::$app->user->isGuest){
            $vacancies = Vacancy::find()->all();
            $categories = Category::find()->all();
            return $this->render('panel', compact('vacancies', 'categories'));
        }
        return $this->render('index');
    }

    public  function actionPanel(){
        if(Yii::$app->user->isGuest){
            return $this->render('index');
        }
        $vacancies = Vacancy::find()->all();
        $categories = Category::find()->all();
        return $this->render('panel', compact('vacancies', 'categories'));
    }


}

