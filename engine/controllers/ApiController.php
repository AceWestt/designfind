<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 31.10.2019
 * Time: 18:19
 */

namespace app\controllers;


use app\models\Category;
use app\models\Vacancy;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ApiController extends AppController
{
    public $enableCsrfValidation = false;
    public $enableCookieValidation = false;

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

    public function actionPublish(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $errorarray = array();
        $companyNameCheck = '/^$/';
        $descriptionCheck = '/^$/';
        $dutiesCheck = '/^$/';
        $conditionsCheck = '/^$/';
        $companyLocationCheck = '/^$/';
        $maxWageCheck = '/^$/';
        $minWageCheck = '/^$/';
        $phoneCheck = '/^$/';
        $tagsCheck = '/^[A-Za-zА-Яа-я0-9\s\\-\\,]*$/u';



        if($_POST && $_FILES){
            $companyName = $_POST['companyName'];
            $category = $_POST['category'];
            $exp = $_POST['exp'];
            $busyType = $_POST['busyType'];
            $description = $_POST['descp'];
            $duties = $_POST['prettyDuties'];
            $conditions = $_POST['prettyCond'];
            $region = $_POST['region'];
            $companyLocation = $_POST['companyLocation'];
            $maxWage = $_POST['maxvage'];
            $minWage = $_POST['minvage'];
            $phone = $_POST['phone'];
            $tags = $_POST['tags'];
            if($this->checkIfEmpty($companyName)){
                array_push($errorarray, [
                    'field'=>'companyName',
                    'message'=>'Пожалуйста, введите название!'
                ]);
            }
            if($category == '-1'){
                array_push($errorarray, [
                    'field'=>'category',
                    'message'=>'Пожалуйста, выберите категорию!'
                ]);
            }
            if($exp == '-1'){
                array_push($errorarray, [
                    'field'=>'exp',
                    'message'=>'Пожалуйста, укажите опыт работы кандидата!'
                ]);
            }
            if($busyType == '-1'){
                array_push($errorarray, [
                    'field'=>'busyType',
                    'message'=>'Пожалуйста, укажите тип занятости кандидата!'
                ]);
            }
            if($this->checkIfEmpty($description)){
                array_push($errorarray, [
                    'field'=>'description',
                    'message'=>'Пожалуйста, введите описание!'
                ]);
            }
            if($this->checkIfEmpty($duties)){
                array_push($errorarray, [
                    'field'=>'duties',
                    'message'=>'Пожалуйста, введите обязанности кандидата!'
                ]);
            }
             if($this->checkIfEmpty($conditions)){
                array_push($errorarray, [
                    'field'=>'conditions',
                    'message'=>'Пожалуйста, укажите условия работы!'
                ]);
            }
            if($region == '-1'){
                array_push($errorarray, [
                    'field'=>'region',
                    'message'=>'Пожалуйста, укажите область!'
                ]);
            }
            if($this->checkIfEmpty($companyLocation)){
                array_push($errorarray, [
                    'field'=>'companyLocation',
                    'message'=>'Пожалуйста, введите фактический адрес!'
                ]);
            }
            if($this->checkIfEmpty($minWage)){
                array_push($errorarray, [
                    'field'=>'minvage',
                    'message'=>'от'
                ]);
            }
            if($this->checkIfEmpty($maxWage)){
                array_push($errorarray, [
                    'field'=>'maxvage',
                    'message'=>'до'
                ]);
            }
             if($this->checkIfEmpty($phone)){
                array_push($errorarray, [
                    'field'=>'phone',
                    'message'=>'Введите свои контакты!'
                ]);
            }
            if(!preg_match($tagsCheck, $tags)){
                array_push($errorarray,
                    [
                        'field' => 'tags',
                        'message'=>''
                    ]);
            }
            if($_FILES['image']['name']!=" "&&$_FILES['image']['name']!=''){
                $extension = explode('.', $_FILES['image']['name']);
                $extension = $extension[count($extension)-1];

                if(!preg_match('/^jpg|jpeg|png$/i',$extension)){

                    array_push($errorarray,
                        ['field'=> 'image',
                            'message'=> 'Только фотографии формата "jpg/jpeg" или "png" могут быть загружены!']);
                }
                else{
                    $size = $_FILES['image']['size'];
                    if($size>5242880){
                        array_push($errorarray,
                            ['field'=> 'image',
                                'message'=> 'Размер изображения не должен превышать 5mb!']);
                    }
                }
            }
            else{
                array_push($errorarray,
                    [
                        'field' => 'image',
                        'message'=>'Загрузите картинку'
                    ]);
            }
            if($_FILES['image']['name']!=" "&&$_FILES['image']['name']!=''){
                $extension = explode('.', $_FILES['image']['name']);
                $extension = $extension[count($extension)-1];
                if(preg_match('/^jpg|jpeg|png$/i',$extension)){
                    $size = $_FILES['image']['size'];
                    if($size<5242880){
                       if(!$this->checkIfEmpty($companyName)
                       &&$category!==-1
                       &&$exp!==-1
                       &&$busyType!==-1
                       &&!$this->checkIfEmpty($description)
                       &&!$this->checkIfEmpty($duties)
                       &&!$this->checkIfEmpty($conditions)
                       &&$region!==-1
                       &&!$this->checkIfEmpty($companyLocation)
                       &&!$this->checkIfEmpty($minWage)
                       &&!$this->checkIfEmpty($maxWage)
                       &&!$this->checkIfEmpty($phone)
                       &&preg_match($tagsCheck, $tags)){
                           $vacancy = new Vacancy();
                           $vacancy->company = $companyName;
                           $vacancy->category_id = $category;
                           $vacancy->exp = $exp;
                           $vacancy->time = $busyType;
                           $vacancy->description = $description;
                           $vacancy->duties = $duties;
                           $vacancy->conditions = $conditions;
                           $vacancy->region = $region;
                           $vacancy->location = $companyLocation;
                           $vacancy->minwage = $minWage;
                           $vacancy->wage = $maxWage;
                           $vacancy->contact = $phone;
                           $vacancy->imgUrl = 'url';
                           $vacancy->date = new Expression('NOW()');
                           $vacancy->tags = $tags;
                           if($vacancy->save()){
                               $rnd = mt_rand(123456789, 987654321);
                               $timeStamp = time();
                               $file = $_FILES['image']['tmp_name'];
                               $filename = $_FILES['image']['name'];
                               $ext = explode('.', $filename);
                               $ext = $ext[count($ext) - 1];
                               $uploadDir = $this->getUploadFolder().'site/user/vacancy';
                               $rubbishFolder = $this->getUploadFolder().'site/tmp/fileupload';
                               $myVac = Vacancy::findOne($vacancy->id);
                               $category = Category::findOne($myVac['category_id']);

                               $name = $rnd.'-'.$timeStamp.'-'.$myVac['date'].'-'.$myVac['id'].'.'.$ext;

                               if(move_uploaded_file($file, "$uploadDir/$name")){
                                   $myVac->imgUrl = '/uploads/site/user/vacancy/'.$name;
                                   if($myVac->save()){
                                       $rubbish = glob($rubbishFolder.'/*');
                                       foreach ($rubbish as $rubbishitem){
                                           if(is_file($rubbishitem)){
                                               unlink($rubbishitem);
                                           }
                                       }
                                       return array('status'=>'ok');
                                   }
                                   else{
                                       return array('status'=>'error', 'msg'=>'final save error');
                                   }
                               }
                               else{
                                   return array('status'=>'error', 'msg'=>'upload error');
                               }
                           }
                           else{
                               return array('status'=>'error', 'save error');
                           }
                       }
                    }
                }
            }
            return array('status'=>'error', 'msg'=>$errorarray);
        }
    }

    public function actionTempUpload(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;



        if($_FILES){


            if(!$this->checkIfEmpty($_FILES['img']['name'])){
                $filename = $_FILES['img']['name'];
                $ext = explode('.', $filename);
                $ext = $ext[count($ext) - 1];
                if(!preg_match('/^jpg|jpeg|png$/i',$ext)){
                    return array('status'=>'error', 'msg'=>'not image');
                }
                else{
                    $size = $_FILES['img']['size'];
                    if($size>5242880){
                        return array('status'=>'error','msg'=>'oversize');
                    }
                    else{
                        $rnd = mt_rand(123456789, 987654321);
                        $timeStamp = time();
                        $file = $_FILES['img']['tmp_name'];
                        $uploadDir = $this->getUploadFolder().'site/tmp/fileupload';

                        $rubbish = glob($uploadDir.'/*');
                        foreach ($rubbish as $rubbishitem){
                            if(is_file($rubbishitem)){
                                unlink($rubbishitem);
                            }
                        }

                        $name = $rnd.'-'.$timeStamp.'-'.basename($filename);
                        if(move_uploaded_file($file,"$uploadDir/$name")){

                            return array('status'=>'ok','msg'=>'/uploads/site/tmp/fileupload/'.$name);
                        }
                        else{
                            return array('status'=>'error','msg'=>'could not upload');
                        }

                    }
                }
            }
            else{
                return array('status'=>'error', 'msg'=>'no file received');
            }
        }else{
            return array('status'=>'error', 'msg'=>'no file received');
        }
    }

    public function actionVacSearch(){
//        returning the response in json
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

//        creating an array to store all vacancies as search result
        $filteredArray = array();
        $vacancies = Vacancy::find()->where(['status'=>1])->orderBy(['id'=>SORT_DESC])->all();

//        creating core variable to make on phrase out of search words
        $searchPhrase = '';
        $attributes = \Yii::$app->request->post();

        $searchWords = [];
        $busyTypes = [];
        $tags = [];



        if($_POST){

//            receiving and aligning to a variable user input search words to an array variable


            if( isset($_POST['searchWords'])){
                $searchWords = $_POST['searchWords'];
            }




//            receiving and aligning a variable user selected category id
            $cat = $_POST['cat'];

            if(isset($_POST['busyType'])){
                $busyTypes = $_POST['busyType'];
            }

            if(isset($_POST['tags'])){
                $tags = $_POST['tags'];
            }





            $limit = $_POST['limit'];

            //        getting all vacancies in case no research input is received
            $vacancies = Vacancy::find()->where(['status'=>1])->orderBy(['id'=>SORT_DESC])->limit($limit)->asArray()->all();


//            return all vacancies if no search preference is received
            if(count($searchWords) <= 0
            && $cat < 0
            && count($busyTypes) <= 0
            && count($tags) <= 0){
                $newARRAY = array();
                foreach ($vacancies as $vacancy){
                    $cat = Category::findOne($vacancy['category_id']);
                    $array = array();
                    $array = Vacancy::find()->where(['id'=> $vacancy['id']])->asArray()->one();
                    $array['cat'] = $cat['title'];
                    array_push($newARRAY, $array);
                }
                return $newARRAY;
            }

//            creating an array for further check for duplicates in search result array
            $dupes = array();
            if(count($searchWords)>0){
//                SEARCH BY PHRASE
//                The whole process of search is divided into two steps: "Search by Phrase and Search by each word in input"
//                The search by input occurs if no data was found after search by phrase
//                As search input is received as an array of word, each item (word) in array is added to one string to make
//                one phrase through foreach cycling
                foreach ($searchWords as $word){
                    $word = trim($word);
                    $searchPhrase = $searchPhrase.' '.$word;
                }
//                removing extra white space characters from input to be sure
                $searchPhrase = preg_replace('/ +/', ' ', $searchPhrase);
//                replacing brackets from phrase with patterned brackets since the phrase is used as SQL REGEXP pattern further
                $searchPhrase = preg_replace('/\(/', '\\\\\(', $searchPhrase);
                $searchPhrase = preg_replace('/\)/', '\\\\\)', $searchPhrase);
//                removing extra spaces from the beginning and end
                $searchPhrase = trim($searchPhrase);

//                further queries to collect vacancies a table according to several columns
//                the first one is commented others work with the same logic
//                the vacancies containing the phrase in their defined row (company name in the first case) are received from the db
                $vacancies = Vacancy::find()->where("LOWER(company) REGEXP LOWER('".$searchPhrase."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
//                checking if the list isn't empty
                if(count($vacancies)>0){
                    foreach ($vacancies as $vacancy){
//                        for each vacancy found there's an iteration through the final result array where the current vacancy
//                        is checked for existence if the vacancy already exists in the array the vacancy  is added to the dupes array
                        foreach ($filteredArray as $array){
                            if($array['id']==$vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
//                        if the dupes array is not empty then the vacancy is not added to the final result array
//                        the whole process prevents one vacancy from being added twice
//                        more specifically the function above adds the vacancy to the dupes array
//                        and the vacancy is not added to the final result array because there's already the copy of it there
//                        At the end of the one cycle the dupes array is cleaned to allow new vacancies to be added to
//                        the filtered array
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

//                search in Category Column
                $category = Category::find()->where("LOWER(title) REGEXP('".trim($searchPhrase)."')")->asArray()->all();
                if(count($category)>0){
                    foreach ($category as $cat){
                        $vacancies = Vacancy::find()->where(['category_id'=> $cat['id']])
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($vacancies)>0){
                            foreach ($vacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id']==$vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }
                    }
                }

                //                search in Experience Column
                $expVacancies = Vacancy::find()->where("LOWER(exp) REGEXP('".trim($searchPhrase)."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($expVacancies)>0){
                    foreach ($expVacancies as $vacancy){
                        foreach ($filteredArray as $array){
                            if($array['id']==$vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

                //                search in busy type Column
                $timeVacancies = Vacancy::find()->where("LOWER(time) REGEXP('".trim($searchPhrase)."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($timeVacancies)>0){
                    foreach ($timeVacancies as $vacancy){
                        foreach ($filteredArray as $array){
                            if($array['id'] == $vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

                //                search in Description Column
                $descpVacancies = Vacancy::find()->where("LOWER(description) REGEXP('".trim($searchPhrase)."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($descpVacancies)>0){
                    foreach ($descpVacancies as $vacancy){
                        foreach ($filteredArray as $array){
                            if($array['id'] == $vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

                //                search in duties Column
                $dutVacancies = Vacancy::find()->where("LOWER(duties) REGEXP('".trim($searchPhrase)."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($dutVacancies)>0){
                    foreach ($dutVacancies as $vacancy){
                        foreach ($filteredArray as $array){
                            if($array['id'] == $vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

                //                search in conditions Column
                $condVacancies = Vacancy::find()->where("LOWER(conditions) REGEXP('".trim($searchPhrase)."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($condVacancies)>0){
                    foreach ($condVacancies as $vacancy){
                        foreach ($filteredArray as $array){
                            if($array['id'] == $vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

                //                search in region Column
                $regionVacancies = Vacancy::find()->where("LOWER(region) REGEXP('".trim($searchPhrase)."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($regionVacancies)>0){
                    foreach ($regionVacancies as $vacancy){
                        foreach ($filteredArray as $array){
                            if($array['id'] == $vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

                //                search in location Column
                $locVacancies = Vacancy::find()->where("LOWER(location) REGEXP('".trim($searchPhrase)."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($locVacancies)>0){
                    foreach ($locVacancies as $vacancy){
                        foreach ($filteredArray as $array){
                            if($array['id'] == $vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

                //                search in contact Column
                $contVacancies = Vacancy::find()->where("LOWER(contact) REGEXP('".trim($searchPhrase)."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($contVacancies)>0){
                    foreach ($contVacancies as $vacancy){
                        foreach ($filteredArray as $array){
                            if($array['id'] == $vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

                //                search in tags Column
                $tagVacancies = Vacancy::find()->where("LOWER(tags) REGEXP('".trim($searchPhrase)."')")
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($tagVacancies)>0){
                    foreach ($tagVacancies as $vacancy){
                        foreach ($filteredArray as $array){
                            if($array['id'] == $vacancy['id']){
                                array_push($dupes, $vacancy);
                            }
                        }
                        if(count($dupes) <= 0){
                            array_push($filteredArray, $vacancy);
                        }
                        $dupes = array();
                    }
                }

//                here the final result array is checked on content after search using the whole phrase
//                if it's empty than the search goes through foreach iteration using the familiar logic
//                but for each typed-by-user word instead of one phrase
                if(count($filteredArray)<=0){
                    foreach ($searchWords as $word){
                        $vacancies = Vacancy::find()->where("LOWER(company) REGEXP LOWER('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($vacancies)>0){
                            foreach ($vacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id']==$vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $category = Category::find()->where("LOWER(title) REGEXP('".trim($word)."')")->asArray()->all();
                        if(count($category)>0){
                            foreach ($category as $cat){
                                $vacancies = Vacancy::find()->where(['category_id'=> $cat['id']])
                                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                                if(count($vacancies)>0){
                                    foreach ($vacancies as $vacancy){
                                        foreach ($filteredArray as $array){
                                            if($array['id']==$vacancy['id']){
                                                array_push($dupes, $vacancy);
                                            }
                                        }
                                        if(count($dupes) <= 0){
                                            array_push($filteredArray, $vacancy);
                                        }
                                        $dupes = array();
                                    }
                                }
                            }
                        }

                        $expVacancies = Vacancy::find()->where("LOWER(exp) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($expVacancies)>0){
                            foreach ($expVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id']==$vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $timeVacancies = Vacancy::find()->where("LOWER(time) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($timeVacancies)>0){
                            foreach ($timeVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $descpVacancies = Vacancy::find()->where("LOWER(description) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($descpVacancies)>0){
                            foreach ($descpVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $dutVacancies = Vacancy::find()->where("LOWER(duties) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($dutVacancies)>0){
                            foreach ($dutVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $condVacancies = Vacancy::find()->where("LOWER(conditions) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($condVacancies)>0){
                            foreach ($condVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $regionVacancies = Vacancy::find()->where("LOWER(region) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($regionVacancies)>0){
                            foreach ($regionVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $locVacancies = Vacancy::find()->where("LOWER(location) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($locVacancies)>0){
                            foreach ($locVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $contVacancies = Vacancy::find()->where("LOWER(contact) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($contVacancies)>0){
                            foreach ($contVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        //                search in tags Column
                        $tagVacancies = Vacancy::find()->where("LOWER(tags) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($tagVacancies)>0){
                            foreach ($tagVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $minWageVacancies = Vacancy::find()->where("LOWER(minwage) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($minWageVacancies)>0){
                            foreach ($minWageVacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                        $maxWageV = Vacancy::find()->where("LOWER(wage) REGEXP('".trim($word)."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($maxWageV)>0){
                            foreach ($maxWageV as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id'] == $vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }

                    }
                }
            }

            if($cat > 0){
                $vacancies = Vacancy::find()->where(['category_id'=>$cat])
                    ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                if(count($filteredArray) > 0){
                    $newFilteredArray = array();
                    foreach ($filteredArray as $array){
                        if($array['category_id']==$cat){
                            array_push($newFilteredArray, $array);
                        }
                    }
                    $filteredArray = $newFilteredArray;

                }
                else{
                    if(count($vacancies)>0){
                        foreach ($vacancies as $vacancy){
                            foreach ($filteredArray as $array){
                                if($array['id']==$vacancy['id']){
                                    array_push($dupes, $vacancy);
                                }
                            }
                            if(count($dupes) <= 0){
                                array_push($filteredArray, $vacancy);
                            }
                            $dupes = array();
                        }
                    }
                }

            }

            if(count($busyTypes)>0){
                if(count($filteredArray)>0){
                    $newFilteredArray = array();
                    foreach ($busyTypes as $type){
                        foreach ($filteredArray as $array){
                            if($type == $array['time']){
                                array_push($newFilteredArray, $array);
                            }
                        }
                    }
                    $filteredArray = $newFilteredArray;
                }
                else{
                    foreach ($busyTypes as $type){
                        $vacancies = Vacancy::find()->where("LOWER(time) REGEXP LOWER('".$type."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($vacancies)>0){
                            foreach ($vacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id']==$vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }
                    }
                }
            }

            if(count($tags)>0){
                if(count($filteredArray)>0){
                    $newFilteredArray = array();
                    foreach ($tags as $tag){
                        foreach ($filteredArray as $array){
                            if(preg_match('/'.$tag.'/i', $array['tags'])){
                                array_push($newFilteredArray, $array);
                            }
                        }
                    }
                    $filteredArray = $newFilteredArray;
                }
                else{
                    foreach ($tags as $tag){
                        $vacancies = Vacancy::find()->where("LOWER(tags) REGEXP LOWER('".$tag."')")
                            ->andWhere(['status'=>1])->orderBy(['id'=>SORT_DESC])->asArray()->all();
                        if(count($vacancies)>0){
                            foreach ($vacancies as $vacancy){
                                foreach ($filteredArray as $array){
                                    if($array['id']==$vacancy['id']){
                                        array_push($dupes, $vacancy);
                                    }
                                }
                                if(count($dupes) <= 0){
                                    array_push($filteredArray, $vacancy);
                                }
                                $dupes = array();
                            }
                        }
                    }
                }
            }

//            $filteredArray = array_reverse($filteredArray);
//            $filteredArray = array_slice($filteredArray, 0, $limit);

            $final = array();
            foreach ($filteredArray as $array){
                $newarray = [];
                $cat = Category::findOne($array['category_id']);
                $newarray = $array;
                $newarray['cat'] = $cat['title'];
                $final[] = $newarray;
            }
            return $final;
        }
        else{
            if(count($vacancies) > 0){
                return array('status'=>'ok', 'data'=>$vacancies);
            }
            else return array('status'=>'error', 'msg'=>'not found');
        }
    }

}