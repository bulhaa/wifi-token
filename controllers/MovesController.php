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
        $behaviors = parent::behaviors();
        $this->enableCsrfValidation = false;

        $behaviors['corsFilter'] = [

            'class' => \yii\filters\Cors::className(),

            'cors' => [

                'Origin' => ['*'],

                 'Access-Control-Allow-Origin' => ['*', 'http://10.59.1.1'],

                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],

                'Access-Control-Request-Headers' => ['*'],

                'Access-Control-Allow-Credentials' => null,

                'Access-Control-Max-Age' => 86400,

                'Access-Control-Expose-Headers' => []

            ]

        ];        

        return $behaviors;

        // return [
        //     // 'access' => [
        //     //     'class' => AccessControl::className(),
        //     //     'only' => ['logout'],
        //     //     'rules' => [
        //     //         [
        //     //             'actions' => ['logout'],
        //     //             'allow' => true,
        //     //             'roles' => ['@'],
        //     //         ],
        //     //     ],
        //     // ],
        //     // 'verbs' => [
        //     //     'class' => VerbFilter::className(),
        //     //     'actions' => [
        //     //         'delete' => ['POST'],
        //     //     ],
        //     // ],
        // ];
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
        if(isset($_POST['details']) && $_POST['details'] != "The current list is empty.,undefined"){
            $details = $_POST['details'];
            $rows = explode('&', $details);


            foreach ($rows as $key => $value) {
                $values = explode(',', $value);

                $ip_parts = explode(".", $values[0]);
                
                $device = Devices::find()->where([
                    'full_ip' => $values[0],
                ])->andWhere(['not', ['token_id' => null]])->andWhere(['mac' => null])->one();
                // ])->andWhere(['not', ['token_id' => null]])->one();

                if(!$device){
                    $device = Devices::find()->where([
                        'mac' => $values[1],
                        'full_ip' => $values[0],
                    ])->andWhere(['not', ['token_id' => null]])->one();
                }

                if(!$device){
                    $device = Devices::find()->where([
                        'mac' => $values[1],
                        'full_ip' => $values[0],
                    ])->one();
                }

                if(!$device){
                    $device = Devices::find()->where([
                        'mac' => $values[1],
                    ])->one();
                }

                // if(!$device){
                //     $device = Devices::find()->where([
                //         'full_ip' => $values[0],
                //     ])->one();
                // }

                if(!$device){
                    $device = new Devices();
                }

                $device->mac = $values[1];
                $device->full_ip = $values[0];
                // var_dump($ip_parts); die;
                $device->ip = $ip_parts[3];

                $diff = $values[2] - $device->prev_usage;
                $token = $device->token;
                $diff = $diff >= 0 ? $diff : $values[2];
                $device->total_usage += $diff ;

                if($token){
                    // var_dump($token->id); die;
                    $now = date_create(date("Y-m-d H:i:s"));
                    $first_used_at = date_create($token->first_used_at);

                    $interval = date_diff($first_used_at, $now);

                    $diff_in_minutes = $interval->format('%a') * 24 * 60 + $interval->format('%h') * 60 + $interval->format('%i');

                    if($diff_in_minutes > $token->validity_in_minutes)
                        $token->expired = 1;
                    else
                        $token->expired = 0;

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

                if($token && $token->last_reset_nDay != $interval->format('%a')){
                    $token->last_reset_nDay = $interval->format('%a');
                    $token->last_reset_day = date("d");
                    $token->used_today = 0;
                    $token->save();
                }

                $device->last_updated_at = date("Y-m-d H:i:s");
                $device->prev_usage = $values[2];
                $device->save();

                // echo "<script type=\"text/javascript\">console.log('row: ";

                // ob_start();
                // var_dump($device->ip,' test ', $token); 
                // $_var_dump_result = ob_get_clean();

                // echo str_replace(array("\n", "\r"), '<br>', $_var_dump_result);
                // echo $_var_dump_result;
                
                // echo "');</script>";

                if($token && $token->last_reset_month != date("m")){
                    Yii::$app->db->createCommand("UPDATE `devices` SET `total_usage`= 0 WHERE 1=1;")->execute();
                    Yii::$app->db->createCommand("UPDATE `tokens` SET `total_used`= 0,`last_reset_month`=".date("m")." WHERE 1=1;")->execute();
                }

            }
        // die;

        }


        $date = date("Y-m-d H:i:s");
        $time = strtotime($date);
        $time = $time - (5 * 60);
        $fiveMinBefore = date("Y-m-d H:i:s", $time);

        $list = (new \yii\db\Query())
            ->select(['ip', 'mac', 'tokens.max_allowed', 'tokens.total_used', 'tokens.daily_allowed', 'tokens.used_today', 'tokens.expired', 'full_ip'])
            ->from('devices')
            ->leftJoin('tokens', 'devices.token_id = tokens.id')
            ->where(['or',['>', 'devices.last_updated_at', $fiveMinBefore],['or',['full_ip' => controllerIP],['full_ip' => whitelistedIP1]]])
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
        $package_total = package_total_in_GB * $GB;

        $sum = (new \yii\db\Query())
            ->select(['SUM(total_used) AS sum'])
            ->from('tokens')
            ->all();

        $total_used = total_unrecorded_usage_in_GB * $GB + $sum[0]['sum'] * 1;
        // var_dump($total_used); die;

        $output = []; 
        $nTotalSpeed = 0;
        foreach ($list as $key => $device) {
            // $total_used += $device['total_used']*1;


            if( ($device['max_allowed'] == null || $device['expired']*1 == 1) && $device['full_ip'] != controllerIP && $device['full_ip'] != whitelistedIP1 )
                array_push($output, array("ip" => $device['full_ip'], "c" => 'ban', "mac" => $device['mac'], )); // ban
            else if( ( $device['used_today']*1 < $device['daily_allowed']*1 || $device['total_used']*1 < $device['max_allowed']*1 ) || $device['full_ip'] == controllerIP || $device['full_ip'] == whitelistedIP1 ){
                array_push($output, array("ip" => $device['full_ip'], "c" => 'allow', "mac" => $device['mac'], )); // allow
                $nTotalSpeed += 1000;
            }
            else{
                array_push($output, array("ip" => $device['full_ip'], "c" => 'limit', "mac" => $device['mac'], )); // limit
                $nTotalSpeed += 100;
            }
        }

        $allowed_usage = $package_total * date("d") / 30;

        $remaining = $allowed_usage - $total_used;

        $date = date("Y-m-d H:i:s");
        $time = strtotime($date);
        $time = $time - (24 * 60 * 60);
        $twentyFourHoursBefore = date("Y-m-d H:i:s", $time);

        $count = (new \yii\db\Query())
            ->select(['COUNT(DISTINCT(token_id)) AS count'])
            ->from('devices')
            ->where(['>', 'devices.last_updated_at', $twentyFourHoursBefore])
            ->all();
        $nDevices = $count[0]['count'];

        if($nDevices == 0)
            $nDevices = 1;

        // $usage_ratio = $total_used / $allowed_usage;
        $usage_ratio = 1;
// var_dump($usage_ratio); die;
        // if($remaining < $package_total / 30){
            $remaining = ($package_total / 30) / $usage_ratio;
        // }

        if($nTotalSpeed < 10000)
            $remaining *= 2;

        $dynamic_daily_allowance = $remaining / $nDevices;
        // echo $dynamic_daily_allowance; die;

        // if($total_used < 10*$GB)
            $dynamic_daily_allowance = 1024 * $MB;
        // else 
        //     $dynamic_daily_allowance = 1024 * $MB / $usage_ratio;

        Yii::$app->db->createCommand("UPDATE `tokens` SET `daily_allowed`= ".$dynamic_daily_allowance." WHERE dynamic_allowance=1;")->execute();

        // $array = ArrayHelper::toArray($list);

        $this->layout=false;

        // print_r($GLOBALS); die;

        if( isset($_POST['details']) ){
            echo json_encode($output); die;
        }else{
            return $this->render('update', [
                'json' => json_encode($output),
            ]);
        }
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
