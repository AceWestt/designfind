<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 09.09.2019
 * Time: 10:50
 */

namespace app\controllers;


use app\models\Category;
use app\models\Vacancy;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class VacancyController extends AppController
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




    public function actionIndex(){
        $categories = Category::find()->all();
        $vacancies = Vacancy::find()->where(['status'=>'1'])->orderBy(['id'=>SORT_DESC])->all();
        $tags = array();
        foreach ($vacancies as $vacancy){
            $vtags = explode(',',$vacancy['tags']);
            foreach ($vtags as $tag){
                if(!preg_match('/^\s+$/', $tag) && $tag != ''){
                    $tag = trim($tag);
                    array_push($tags, $tag);
                }
            }
        }

        $tagArray = $tags;
        shuffle($tagArray);
        $tagArray = array_unique($tagArray);
        $tagArray = array_slice($tagArray, 0, 10);




        return $this->render('index', compact('categories', 'tags', 'tagArray'));
    }

    public function actionAdd(){
        $categories = Category::find()->all();
        return $this->render('add', compact('categories'));
    }

    public function actionItem($id){
        $vacancy = Vacancy::findOne($id);
        $vacancies = Vacancy::find()->where(['status'=>'1'])->orderBy(['id'=>SORT_DESC])->asArray()->all();
        shuffle($vacancies);
        $vacancies = array_slice($vacancies, 0, 3);
        return $this->render('item', compact('vacancy','vacancies'));
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

        if($attributes){

//            receiving and aligning to a variable user input search words to an array variable
            $searchWords = $attributes['searchWords'];

//            receiving and aligning a variable user selected category id
            $cat = $attributes['cat'];

            $busyTypes = $attributes['busyType'];

            $tags = $attributes['tags'];

            $limit = $attributes['limit'];

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