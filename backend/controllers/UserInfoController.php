<?php

namespace backend\controllers;

use Yii;
use backend\models\UserInfo;
use backend\models\UserInfoSearch;
//use common\models\AddressUserInfo;
//use common\models\AddressUserInfoSearch;
//use common\models\ProfileInfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\helpers\BaseFileHelper;
use yii\filters\AccessControl;
use yii\base\Model;

/**
 * UserInfoController implements the CRUD actions for UserInfo model.
 */
class UserInfoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'update', 'delete', 'publish'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all UserInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        

        $searchModelProfiles = new ProfileInfoSearch();
        $dataProviderProfiles = $searchModelProfiles->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchModelProfiles' => $searchModelProfiles,
            'dataProviderProfiles' => $dataProviderProfiles,
        ]);
    }

    /**
     * Displays a single UserInfo model.
     * @param string $id
     * @return mixed
     */
    // public function actionView($id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($id, "common\models\UserInfo"),
    //     ]);
    // }

    /**
     * Creates a new UserInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $modelUserInfo = new UserInfo();
    //     $modelUserInfo->id = 0;


    //     if(Yii::$app->request->isAjax && Yii::$app->request->isPost){
    //         if ($modelUserInfo->load(Yii::$app->request->post())) {
    //             // echo "<pre>";
    //             // print_r($_POST);
    //             // exit;
    //             Yii::$app->response->format = Response::FORMAT_JSON;
    //             echo json_encode(ActiveForm::validate($modelUserInfo));
    //             Yii::$app->end();
    //         }
    //     }

    //     if ($modelUserInfo->load(Yii::$app->request->post()) && $modelUserInfo->save()) {
    //         return $this->redirect(['user-info/update-user-info', 'first_name' => $modelUserInfo->first_name]);
    //     } else {
    //         return $this->render('create', [
    //             'modelUserInfo' => $modelUserInfo,
    //         ]);
    //     }
    // }

    protected function batchUpdate($items)
    {
        $citysArr=[];
        print_r($_REQUEST['AddressUserInfo']);
        foreach (Yii::$app->request->post('AddressUserInfo') as $key => $value) {
            preg_match("/\"(.*?)\"/i", Yii::$app->request->post('AddressUserInfo')[$key]['code'], $matches);
            //echo $matches[1];
            echo $_REQUEST['AddressUserInfo'][$key]['code']."<br>";
            if (count($matches)) {
                $_REQUEST['AddressUserInfo'][$key]['code'] = $matches[1];
                $_POST['AddressUserInfo'][$key]['code'] = $matches[1];
                Yii::$app->request->post('AddressUserInfo')[$key]['code'] = $matches[1];
            }
        }
        if (Model::loadMultiple($items, $_POST) &&
            Model::validateMultiple($items)) {
            foreach ($items as $key => $item) {
                if(!empty($_POST['AddressUserInfo'][$key]['city'])) {
                    $item->save();
                    $citysArr[] = [$item->city, $item->map_x, $item->map_y, $item->code];
                }
                else{
                    $item->delete();
                }
            }
            return $citysArr;
        }
    }

    /**
     * Updates an existing UserInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
     /*Yii::$app->getResponse()
            ->getHeaders()
            ->set('Cache-Control: no-cache, must-revalidate');
        Yii::$app->getResponse()
            ->getHeaders()
            ->set('Pragma: no-cache');
        */
        
        $modelUserInfo = $this->findModel($id, "backend\models\UserInfo");
        $modelUser = $this->findModel($id, "backend\models\User");

        /*
              if (Yii::$app->request->get('deleteAddress')) {
                  //echo Yii::$app->request->get('deleteAddress');
                  $modelAddressUserInfo = $this->findModel(Yii::$app->request->get('deleteAddress'), "common\models\AddressUserInfo");

                  $citysArr = json_decode($modelUserInfo->city);

                  if (is_array($citysArr)) {
                      unset($citysArr[array_search($modelAddressUserInfo->city, $citysArr)]);
                      if (count($citysArr)) {
                          foreach ($citysArr as $key => $value) {
                              $newCitysArr[] = $value;
                          }
                          $modelUserInfo->city = json_encode($newCitysArr);
                      }
                      else{
                          $modelUserInfo->city = "";
                      }
                  }
                  else{
                      $modelUserInfo->city = "";
                  }
                  $modelUserInfo->save();
                  $modelAddressUserInfo->delete();
              }
              if (!empty($_POST['createAddress'])) {
                  // echo "<pre>";
                  // print_r(Yii::$app->request->post());
                  // exit;
                  $res=AddressUserInfo::find()->where(['profile_id' => $id])->andWhere(['city' => ''])->all();
                  if (count($res)) {
                      $addressUserInfo = $this->findModel($res[0]['id'], "common\models\AddressUserInfo");
                      $addressUserInfo->city = Yii::$app->request->post('CityUserInfo');
                      $citysArr[0] = $addressUserInfo->city;
                      $addressUserInfo->map_x = Yii::$app->request->post('MapXUserInfo');
                      $citysArr[1] = $addressUserInfo->map_x;
                      $addressUserInfo->map_y = Yii::$app->request->post('MapYUserInfo');
                      $citysArr[2] = $addressUserInfo->map_y;
                      preg_match("/\"(.*?)\"/i", Yii::$app->request->post('CodeUserInfo'), $matches);
                      $addressUserInfo->code = (count($matches))?$matches[1]:"";
                      $citysArr[3] = $addressUserInfo->code;
                      $addressUserInfo->save();
                      if ($modelUserInfo->city) {
                          $saveCitysArr = json_decode($modelUserInfo->city);
                          array_push($saveCitysArr, $citysArr);
                          $modelUserInfo->city = json_encode($saveCitysArr);
                      }
                      else{
                          $newCitysArr[0] = $citysArr;
                          $modelUserInfo->city = json_encode($newCitysArr);
                      }
                      $modelUserInfo->save();
                  }
                  else {
                      $addressUserInfo = new AddressUserInfo;
                      $addressUserInfo->profile_id = $id;
                      $addressUserInfo->save();
                  }
                  return $this->redirect(Url::to(['/user-info/update', 'id'=> $modelUserInfo->id]));
              }

              if(Yii::$app->request->isAjax && !isset($_POST['scenario']) && Yii::$app->request->isPost){
                  if ($modelUser->load(Yii::$app->request->post()) && $modelUserInfo->load(Yii::$app->request->post())) {
                      Yii::$app->response->format = Response::FORMAT_JSON;
                      echo json_encode(array_merge(ActiveForm::validate($modelUserInfo), ActiveForm::validate($modelUser)));
                      Yii::$app->end();
                  }
              }
              $curAvatar = $modelUserInfo->avatar;
              $curAvatarMob = $modelUserInfo->avatar_mob;
              if (!isset($_POST['scenario'])&& $modelUser->load(Yii::$app->request->post()) && $modelUserInfo->load(Yii::$app->request->post()) && $modelUser->validate() && $modelUserInfo->validate()) {

                  $transaction = Yii::$app->db->beginTransaction();

                  //if( $modelUser->status != 1 ) {

                  $modelUser->status = $modelUserInfo->active;

                 // }else{ // обновление
                  //    $modelUser->status = 1;//$modelUserInfo->active;
                 // }

                  if ($user = $modelUser->save()) {
                      $modelUserInfo->avatar =  (Yii::$app->request->post('deleteImg') == 1)? false: $curAvatar;
                      $id = $modelUserInfo->id;
                      $path = Yii::getAlias("@frontend/web/uploads/avatar/".$id);
                      if ($file = UploadedFile::getInstance($modelUserInfo, 'avatar')) {
                          $this->fileUpload1($modelUserInfo, $path, 'avatar', $file);
                      }
                      $modelUserInfo->avatar_mob =  (Yii::$app->request->post('deleteImg') == 1)? false: $curAvatarMob;
                      $id = $modelUserInfo->id;
                      $path = Yii::getAlias("@frontend/web/uploads/avatar/".$id);
                      if ($file = UploadedFile::getInstance($modelUserInfo, 'avatar_mob')) {
                          $this->fileUpload1($modelUserInfo, $path, 'avatar_mob', $file);
                      }
                      if ($modelUserInfo->social1 == '0') {
                          $modelUserInfo->socialUrl1 = '';
                      }
                      if ($modelUserInfo->social2 == '0') {
                          $modelUserInfo->socialUrl2 = '';
                      }
                      if ($modelUserInfo->social3 == '0') {
                          $modelUserInfo->socialUrl3 = '';
                      }
                      if ($modelUserInfo->social4 == '0') {
                          $modelUserInfo->socialUrl4 = '';
                      }
                      if ($modelUserInfo->social5 == '0') {
                          $modelUserInfo->socialUrl5 = '';
                      }
                      if ($modelUserInfo->social6 == '0') {
                          $modelUserInfo->socialUrl6 = '';
                      }
                      $modelUserInfo->social1 = ($modelUserInfo->socialUrl1 == '')?0:1;
                      $modelUserInfo->social2 = ($modelUserInfo->socialUrl2 == '')?0:1;
                      $modelUserInfo->social3 = ($modelUserInfo->socialUrl3 == '')?0:1;
                      $modelUserInfo->social4 = ($modelUserInfo->socialUrl4 == '')?0:1;
                      $modelUserInfo->social5 = ($modelUserInfo->socialUrl5 == '')?0:1;
                      $modelUserInfo->social6 = ($modelUserInfo->socialUrl6 == '')?0:1;

                      // $citysArr = $this->batchUpdate($modelUserInfo->addressUserInfos);
                      // if (is_array($citysArr)) {
                      //     $modelUserInfo->city = json_encode($citysArr);
                      // }

                      if ($modelUserInfo->save()) {
                          $transaction->commit();
                          Yii::$app->session->setFlash('success', 'Update Success');
                      }
                      else
                          $transaction->rollback();
                  }
                  else
                      $transaction->rollback();
              }
              if($avatar = $modelUserInfo->avatar){
                  $serverNameArr =  explode('.', $_SERVER["HTTP_HOST"]);
                  unset($serverNameArr[0]);
                  $serverName = implode('.', $serverNameArr);
                  $modelUserInfo->initialPreviewImg[] =  '<img src="http://'.$serverName.'/uploads/avatar/' . $modelUserInfo->id . '/' . $avatar . '">';
              }
              if($avatar_mob = $modelUserInfo->avatar_mob){
                  $serverNameArr =  explode('.', $_SERVER["HTTP_HOST"]);
                  unset($serverNameArr[0]);
                  $serverName = implode('.', $serverNameArr);
                  $modelUserInfo->initialPreviewImgMob[] =  '<img src="http://'.$serverName.'/uploads/avatar/' . $modelUserInfo->id . '/' . $avatar_mob . '">';
              }
              if(Yii::$app->request->isPost){
                  if (isset($_POST['update'])) {
                      return $this->redirect(['/user-info/update', 'id' => $modelUser->id]);
                  }
                  if (!isset($_POST['createAddress']) && !isset($_GET['deleteAddress'])) {
                      return $this->redirect(['/user-info']);
                  }
              }
      */

        return $this->render('update', [
            'modelUser' => $modelUser,
            'modelUserInfo' => $modelUserInfo,
        ]);
    }

    /**
     * Deletes an existing UserInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id, "backend\models\User")->delete();

        return $this->redirect(['index']);
    }

    public function fileUpload($model, $path, $field, $file){
        if(Yii::$app->request->isPost){
            BaseFileHelper::createDirectory($path);

            $name = $field.".".$file->extension;

            $file->saveAs($path .DIRECTORY_SEPARATOR .$name);

            $image  = $path .DIRECTORY_SEPARATOR .$name;
            //$new_name = $path .DIRECTORY_SEPARATOR."small_".$name;

            $model->$field = $name;
            $model->save();

            $size = getimagesize($image);
            $width = $size[0];
            $height = $size[1];

            // Image::frame($image, 0, '666', 0)
            //     ->crop(new Point(0, 0), new Box($width, $height))
            //     ->resize(new Box(100,100))
            //     ->save($new_name, ['quality' => 100]);

            return true;

        }
    }

    public function fileUpload1($model, $path, $field, $file){
        if(Yii::$app->request->isPost){
            BaseFileHelper::createDirectory($path);

            $name = 'user_'.$field.".".$file->extension;

            $file->saveAs($path .DIRECTORY_SEPARATOR .$name);

            $image  = $path .DIRECTORY_SEPARATOR .$name;
            //$new_name = $path .DIRECTORY_SEPARATOR."small_".$name;

            $model->$field = $name;
            $model->save();

            $size = getimagesize($image);
            $width = $size[0];
            $height = $size[1];

            // Image::frame($image, 0, '666', 0)
            //     ->crop(new Point(0, 0), new Box($width, $height))
            //     ->resize(new Box(100,100))
            //     ->save($new_name, ['quality' => 100]);

            return true;

        }
    }

    /**
     * Finds the UserInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return UserInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $modelName)
    {
        if (($model = $modelName::findOne($id)) !== null) {
                return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

// публикация поста
    public function actionPublish($id)
    {

        if ($id) {

            $modelUserInfo = $this->findModel($id, "backend\models\UserInfo");
            $modelUser = $this->findModel($id, "backend\models\User");
            $modelUserInfo->active = 1;
            $modelUser->status = 1;

            if($modelUserInfo->save() &&  $modelUser->save() ){
                Yii::$app->session->setFlash('success', 'Пользователь активирован.');
            }else{
                Yii::$app->session->setFlash('error', 'Не удачная попытка активации!');
            }
        }

        return $this->redirect(Url::to(['user/index']));

    }



}
