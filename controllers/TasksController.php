<?php

namespace app\controllers;

use app\models\Task;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
            ->joinWith('status')
            ->where(['status.name' => 'Новое'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }
}
