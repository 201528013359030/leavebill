<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Leavebill;
use app\models\LeavebillSearch;

class WorkflowModel extends Model {
	// 开启请假条流程
	public function saveStartProcess($leaveBill, $uid) {
		$searchModel = new LeavebillSearch ();
		// <<<<<<< HEAD

		// /***************fyq 2016-10-12 13:33:26***************************/
		// // $leaveBill = LeavebillSearch::find()->where ( [
		// // // 'username'=> '3@15'
		// // 'id' => $leaveBillId
		// // ] )->asArray ()->One();
		// // $leaveBill['state']=1;
		// /********************fyq 2016-10-12 13:33:32*****************/
		// $key = "LeaveBill:1:43";
		// =======
		// $leaveBill = LeavebillSearch::find()->where ( [
		// 'id' => $leaveBillId
		// ] )->One();
		$leaveBill->state = 1;
// 		$key = "LeaveBill:1:43";
		$key = "LeaveBill";
		// $key="LeaveBill:1:2694";
		// >>>>>>> refs/remotes/basic/master
		// $bussinessKey=$key.".".$leaveBill['id'];
		$bussinessKey = "LeaveBill" . "." . $leaveBill ['id'];
		$variables = [
				[
						'name' => 'inputUser',
						'value' => $uid
				],
				[
						'name' => 'isAbandon',
						'value' => '1'
				]
		];
		$activitiModel = new ActivitiModel ();
		// 开启流程
		$result = $activitiModel->StartProcessInstance ( $key, $bussinessKey, $variables );
		// var_dump($result);
		// <<<<<<< HEAD
// 		var_dump($result);
// // 		return ;
//
// 		$processInstanceId = $result->id ;
// 		// var_dump ( $processInstanceId );
// 		$task = $activitiModel->queryTasks ( $processInstanceId );
// 		// var_dump($task);
// 		// =======
		// 获取流程ID
		isset($result->id) or die('创建流程失败');
		$processInstanceId = $result->id;
		// 获取任务
		$task = $activitiModel->queryTasks ( $processInstanceId );
		// >>>>>>> refs/remotes/basic/master
		isset($task->data) or die('创建任务失败');
		$taskId = $task->data [0]->id;
		$variables2 = [
				[
						'name' => 'approvalPerson',
						'value' => $leaveBill ['approvalPerson']
				],
				[
						'name' => 'isAbandon',
						'value' => '1'
				]
		];
		// 完成任务
		$result2 = $activitiModel->completeTask ( $taskId, $variables2 );
		// <<<<<<< HEAD
		// var_dump ( $result2 );

		// $activitiModel->c
		// var_dump($result);
		if ($result2 == null || count ( $result2 ) <= 0) {
			// 保存请假条
			// echo '</br>';
			// echo $leaveBill->save ();

			// $result = $leaveBill->save ();
			return $result = [
					'status' => 'success',
					'model' => $leaveBill
			];
		} else {
			// echo "创建请假流程失败"
			return $result = [
					'status' => 'error'
			]
			;
		}
	}
	// 修改请假条（重新请求）
	public function updateStartLeaveBill($leaveBill) {
		$key = "LeaveBill";
		$leaveBillId = $leaveBill->id;
		$activitiModel = new ActivitiModel ();
		$processBusinessKey = $key . '.' . $leaveBillId;
		// 获取流程ID
		$result = $activitiModel->queryHistoricProcessInstances ( $processBusinessKey )->data;
		//var_dump($result);
		if ($result != null && count ( $result ) > 0) {
			$processInstanceId = $result [0]->id;
			//echo $processInstanceId;
		} else {
			return “无此请假流程”;
			// >>>>>>> refs/remotes/basic/master
		}
		// 查询此任务
		$task = $activitiModel->queryTasks ( $processInstanceId )->data;
		//var_dump($task);
		if (count ( $task ) > 0) {
			$taskId = $task [0]->id;
			$variables2 = [
					[
							'name' => 'approvalPerson',
							'value' => $leaveBill ['approvalPerson']
					],
					[
							'name' => 'isAbandon',
							'value' => '1'
					]
			];
			// 完成任务
			$result2 = $activitiModel->completeTask ( $taskId, $variables2 );
		} else {
			return "无此请假任务";
		}
		//echo "<br/>";
		// 保存请假条
		if($leaveBill->save ()){
			return "success";
		}else{
			var_dump($leaveBill->getErrors());
		}
// 		 echo "<br/>";
// 		 return "success";
	}
	// <<<<<<< HEAD
	// //public function submit

	// =======

	// >>>>>>> refs/remotes/basic/master
	// 获取请假条信息
	public function findCommentByLeaveBillId($leaveBillId) {
		$activitiModel = new ActivitiModel ();
		$key = "LeaveBill";
		$processBusinessKey = $key . '.' . $leaveBillId;
		$result = $activitiModel->queryHistoricProcessInstances ( $processBusinessKey )->data;
		if ($result != null && count ( $result ) > 0) {
			$processInstanceId = $result [0]->id;
		} else {
			return null;
		}
		// <<<<<<< HEAD

		// //var_dump($processInstanceId);
		// //$processInstanceId=$result->id;
		// //echo $processInstanceId;
		// //$taskList=$activitiModel->queryHistoricProcessInstances($processInstanceId)->data;
		// =======
		// >>>>>>> refs/remotes/basic/master
		$utils = new UtilsModel ();
		$taskList = $activitiModel->queryHistoricTaskInstancesById ( $processInstanceId )->data;
		$result = array ();
		foreach ( $taskList as $task ) {
			$comments = $activitiModel->getCommentOnTask ( $task->id );
			// <<<<<<< HEAD
			// //var_dump($comments);
			// $task=$utils->object2array($task);
			// =======
			$task = $utils->object2array ( $task );
			// >>>>>>> refs/remotes/basic/master
			$task ['comments'] = $comments;
			array_push ( $result, $task );
		}
		return $result;
	}

	// 审批
// <<<<<<< HEAD
// 	public function saveSubmitTaskByLeaveBillId($leaveBill, $outcome, $comment = null) {
// =======
	public function saveSubmitTaskByLeaveBillId($leaveBill, $outcome, $uid, $comment = null) {
		$session=Yii::$app->session;
		$name=$session->get('name');
// >>>>>>> b2e094b669866dfa80c52f2235d1dc302934629d
		$activitiModel = new ActivitiModel ();
		$leaveBillId = $leaveBill->id;
		$key = "LeaveBill";
		$businessKey = $key . '.' . $leaveBillId;
// <<<<<<< HEAD
// 		$result = $activitiModel->queryHistoricProcessInstances ( $businessKey )->data;
// // 		$employee = Employee::find ()->where ( [
// // 				'username' => $uid
// // 		] )->asArray ()->all ();
// // 		if ($employee != null && count ( $employee ) > 0) {
// // 			$employee = $employee [0];
// // 		} else {
// // 			return "无此用户";
// // 		}
// =======
		$result = $activitiModel->queryHistoricProcessInstances ( $businessKey );
		$employee = Employee::find ()->where ( [
				'username' => $uid
		] )->asArray ()->all ();
		if ($employee != null && count ( $employee ) > 0) {
			$employee = $employee [0];
		} else {
			return "无此用户";
		}
// >>>>>>> b2e094b669866dfa80c52f2235d1dc302934629d
		if ($result != null && count ( $result ) > 0) {
			$processInstanceId = $result [0]->id;
			// <<<<<<< HEAD
			// }else{
			// return null;
			// }
			// echo $processInstanceId;

			// =======
		} else {
			return "无此流程";
		}
		// >>>>>>> refs/remotes/basic/master
		$session=Yii::$app->session;
		$task = $activitiModel->queryTasks ( $processInstanceId );
		if (count ( $task->data ) <= 0) {
			return "无此任务";
		}
		$task = $task->data [0];
		$taskId = $task->id;
		if ($task != null) {
			if ("1" == $outcome) {
				$leaveBill->state = 2;
				if ($comment != null) {
// <<<<<<< HEAD
// 					$message = $session->get('name') . "已同意.  意见:" . $comment;
// 				} else {
// 					$message =$session->get('name') . "已同意.";
// =======
					$message = $name . "已同意.\t意见:" . $comment;
				} else {
					$message = $name . "已同意.";
// >>>>>>> b2e094b669866dfa80c52f2235d1dc302934629d
				}
				$activitiModel->createCommentOnTask ( $taskId, $message );
				$variables = [
						[
								'name' => 'outcome',
								'value' => $outcome
						]
				];
				$activitiModel->completeTask ( $taskId, $variables );
				// <<<<<<< HEAD
				// //保存leaveBill

				// =======
				// 保存leaveBill
				// >>>>>>> refs/remotes/basic/master
				$leaveBill->save ();
			} elseif ("0" == $outcome) {
				$leaveBill->state = 3;
				if ($comment != null) {
					$message = $session->get('name') . "已拒绝.   意见:" . $comment;
				} else {
					$message = $session->get('name') . "已拒绝.";
				}
				$activitiModel->createCommentOnTask ( $taskId, $message );
				$variables = [
						[
								'name' => 'outcome',
								'value' => $outcome
						],
						[
								'name' => 'approvalPerson',
// <<<<<<< HEAD
// 								//'value' => $uid
// 								'value' => $session->get('uid')
// 						]
// =======
								'value' => $uid
						]
// >>>>>>> b2e094b669866dfa80c52f2235d1dc302934629d
				];
				$activitiModel->completeTask ( $taskId, $variables );
				// 保存leaveBill
				$leaveBill->save ();
			} elseif ("3" == $outcome) {
				$leaveBill->state = 4;
				if ($comment != null) {
					$message = $session->get('name') . "已放弃.  原因:" . $comment;
				} else {
					$message = $session->get('name') . "已放弃.";
				}
				$activitiModel->createCommentOnTask ( $taskId, $message );
				$variables = [
						[
								'name' => 'isAbandon',
								'value' => '0'
						]
				];
				$activitiModel->completeTask ( $taskId, $variables );
				// 保存leaveBill
				$leaveBill->save ();
			}
		}
	}
}