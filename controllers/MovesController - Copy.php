<?php

namespace app\controllers;

use Yii;
use app\models\Devices;
use app\models\Pieces;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * MovesController implements the CRUD actions for Moves model.
 */
class MovesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $this->enableCsrfValidation = false;
        return [
            // 'access' => [
            //     'class' => AccessControl::className(),
            //     'only' => ['logout'],
            //     'rules' => [
            //         [
            //             'actions' => ['logout'],
            //             'allow' => true,
            //             'roles' => ['@'],
            //         ],
            //     ],
            // ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'delete' => ['POST'],
            //     ],
            // ],
        ];
    }

    /**
     * Lists all Moves models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Moves::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Moves models.
     * @return mixed
     */
    public function actionUpdates()
    {
        $last_move_id = Yii::$app->request->get('last_move_id');
        $moves = (new \yii\db\Query())
            ->select(['moves.id', 'from_x', 'from_y', 'to_x', 'to_y', 'pieces.id AS piece_id', 'pieces.type_id', 'pieces.player_id'])
            ->from('moves')
            ->leftJoin('pieces', 'moves.from_piece_id = pieces.id')
            ->where(['>', 'moves.id', $last_move_id])
            // ->limit(10)
            ->all();

        // $moves = Moves::find()->where(['>', 'id', $last_move_id])->all();
        $array = ArrayHelper::toArray($moves);

        // $array2 = array('' => , );
        echo json_encode($array);
            // ob_end_clean();
    }

    /**
     * Displays a single Moves model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Moves model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Moves();
        $model->load(Yii::$app->request->post());

        $from_piece = Pieces::find()->where([
            'type_id' => $_POST['type_id'],
            'player_id' => Yii::$app->user->id,
        ])->one();
        $to_piece = Pieces::findOne($model->to_piece_id);
        // var_dump($model->from_x == '' && $model->from_y == '' && $from_piece == null); die;
        // create new piece
        if($model->from_x == '' && $model->from_y == '' && $from_piece == null){
            $from_piece = new Pieces();
            $from_piece->type_id = $_POST['type_id'];
            $from_piece->player_id = Yii::$app->user->id;
            $from_piece->level = 1;
            $from_piece->state_id = 0;
        }

        $from_piece->x = $model->to_x;
        $from_piece->y = $model->to_y;
        $from_piece->save();

        $model->from_piece_id = $from_piece->id;
        $model->time = date('Y-m-d H:i:s');

        if($to_piece != null){
            $to_piece->x = null;
            $to_piece->y = null;
            $to_piece->state_id = 1;
            $to_piece->save();
        }

        // var_dump($to_piece->getErrors()); die;


        $success = $model->save();

        echo json_encode(array('success' => $success, ));
    }

    // public function actionPrint()
    // {
    //     $user = User::find()->where(['id' => Yii::$app->user->id,])->one();
    //     if(Yii::$app->user)
    //     var_dump(Yii::$app->user); die;
    // }

    /**
     * Updates an existing Moves model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        if(isset($_POST['details'])){
            $details = $_POST['details'];
            $rows = explode('&', $details);


            foreach ($rows as $key => $value) {
                $values = explode(',', $value);

                $device = Devices::find()->where([
                    'ip' => $values[0],
                ])->andWhere(['not', ['token_id' => null]])->andWhere(['mac' => null])->one();

                if(!$device){
                    $device = Devices::find()->where([
                        'mac' => $values[1],
                        'ip' => $values[0],
                    ])->andWhere(['not', ['token_id' => null]])->one();
                }

                if(!$device){
                    $device = Devices::find()->where([
                        'mac' => $values[1],
                        'ip' => $values[0],
                    ])->one();
                }

                if(!$device){
                    $device = Devices::find()->where([
                        'mac' => $values[1],
                    ])->one();
                }

                // if(!$device){
                //     $device = Devices::find()->where([
                //         'ip' => $values[0],
                //     ])->one();
                // }

                if(!$device){
                    $device = new Devices();
                }

                $device->mac = $values[1];
                $device->ip = $values[0];

                $diff = $values[2] - $device->prev_usage;
                $token = $device->token;
                $diff = $diff > 0 ? $diff : $values[2];
                $device->total_usage += $diff ;

                if($token){
                    if($token->expires_at == null){
                        $today = date_create(date("Y-m-d"));
                        $first_used_at = date_create($token->first_used_at);

                        $interval = date_diff($first_used_at, $today);

                        if($interval->format('%a') >= 7)
                            $token->expired = 1;
                        else
                            $token->expired = 0;
                    }else{
                        $now = date_create(date("Y-m-d H:i:s"));
                        $expires_at = date_create($token->expires_at);
                        if($expires_at > $now)
                            $token->expired = 0;
                        else
                            $token->expired = 1;
                    }

                    $token->total_used += $diff;
                    $token->used_today += $diff;

                    // check if exceeded limit
                    // if($token->total_used > $token->max_allowed){
                    //     foreach ($token->Devices as $key => $value) {
                    //         array_push($ban_list, $value);
                    //     }
                        
                    // }
                    $token->save();
                }

                if($token && $token->last_reset_day != date("d")){
                    $token->last_reset_day = date("d");
                    $token->used_today = 0;
                    $token->save();
                }

                $device->last_updated_at = date("Y-m-d H:i:s");
                $device->prev_usage = $values[2];
                $device->save();
                    // var_dump($device->errors,); die;}

                if($token && $token->last_reset_month != date("m")){
                    Yii::$app->db->createCommand("UPDATE `devices` SET `total_usage`= 0 WHERE 1=1;")->execute();
                    Yii::$app->db->createCommand("UPDATE `tokens` SET `total_used`= 0,`last_reset_month`=".date("m")." WHERE 1=1;")->execute();
                }

            }

        }

        $date = date("Y-m-d H:i:s");
        $time = strtotime($date);
        $time = $time - (5 * 60);
        $sixtyMinBefore = date("Y-m-d H:i:s", $time);

        $list = (new \yii\db\Query())
            ->select(['ip', 'mac', 'max_allowed', 'tokens.total_used', 'tokens.daily_allowed', 'tokens.used_today', 'tokens.expired'])
            ->from('devices')
            ->leftJoin('tokens', 'devices.token_id = tokens.id')
            ->where(['or',['>', 'devices.last_updated_at', $sixtyMinBefore],['or',['ip' => '10.59.1.100'],['ip' => '10.59.1.250']]])
            // ->where(['or',['>', 'devices.last_updated_at', $sixtyMinBefore],['not', ['tokens.id' => null]]])
            // ->where(['>', 'devices.last_updated_at', $sixtyMinBefore])
            ->orderBy([
                'ip' => SORT_ASC, //specify sort order ASC for ascending DESC for descending      
                'token_id' => SORT_DESC, //specify sort order ASC for ascending DESC for descending      
                'last_updated_at' => SORT_DESC //specify sort order ASC for ascending DESC for descending      
                ])
            ->all();


        $GB = 1024 * 1024 * 1024;
        $MB = 1024 * 1024;

        $total_used = 140*$GB;
        foreach ($list as $key => $device) {
            $total_used += $device['total_used']*1;
        }

        $allowed_usage = 500 * $GB * date("d") / 30;
        $usage_ratio = $total_used / $allowed_usage;

        if($total_used < 10*$GB)
            $dynamic_daily_allowance = 1024 * $MB;
        else 
            $dynamic_daily_allowance = 1024 * $MB / $usage_ratio;

        Yii::$app->db->createCommand("UPDATE `tokens` SET `daily_allowed`= ".$dynamic_daily_allowance." WHERE 1=1;")->execute();

        // if(date("i")*1 % 60 == 0) // every 60 minutes
        //     Yii::$app->db->createCommand("DELETE FROM `devices` WHERE `token_id` IS NULL;")->execute();

        $array = ArrayHelper::toArray($list);

        $this->layout=false;
        return $this->render('update', [
            'json' => json_encode($array),
        ]);
    }

    /**
     * Deletes an existing Moves model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Moves model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Moves the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Moves::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
