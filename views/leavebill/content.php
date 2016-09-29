<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta id="viewport" name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<title>请假条列表-详细</title>
<?php use yii\helpers\Html;?>
<?=Html::cssFile('../views/css/leave.css')?>
</head>
<body>

<div class="off-canvas-wrap" data-offcanvas>
	<div class="inner-wrap">
		
		<!--请假条内容部分-->
		<div class="row">
			
			<div class="row-tab">&nbsp;</div>
			
			<div class="content">
				
				<div class="conInfo">
					<p><?=Html::encode($model['spuser']) ?>,您好:<br><br>我是<span class="fb"><?=Html::encode($model['username']) ?>-<?=Html::encode($model['dep']) ?></span>,<span class="fb"><?=Html::encode($model['reason']) ?></span>，需请<span class="fb"><?php if ($model['leaveType']==1){?>事假<?php }elseif ($model['leaveType']==2){?>病假<?php }elseif ($model['leaveType']==3){?>婚假<?php }elseif ($model['leaveType']==4){?>丧假<?php }elseif ($model['leaveType']==5){?>年假<?php }else{?>其他<?php }?></span> <span class="fb"><?php  $model=['days']?> 天</span>
					</p>
					<p><mark class="fmak">
					<span class="fr date">
<!-- 	  						<s:date name="applyTime" format="YYYY-MM-dd HH:mm"/> -->
									<?=Html::encode($model['leaveStartTime']) ?>
	  				</span>
					~ 
					<span class="fr date">
<!-- 	  						<s:date name="applyTime" format="YYYY-MM-dd HH:mm"/> -->
									<?=Html::encode($model['leaveEndTime']) ?>
	  				</span>
					</mark></p>
					<p><?=Html::encode($model['remark']) ?></p>
					<p>请您审批</p>
				</div>
				
				
			</div>
			
		</div>
		<!--end 请假条内容部分-->
		
		<div class="row-tab">&nbsp;</div>
		
		<!--跟踪流程-->
		<div class="row">
			
			<h3 class="freightTit">流程跟踪</h3>
			<ul class="freightUl">	
<!-- 				<s:if test="#list!=null && #list.size()>0"> -->
				<?php if(($dataAgree!=null)&&(count($dataAgree)>0)){?>
				<s:iterator value="#list">
					<li class="mcurrent">
					<span class="note"></span>				
					<p><s:property value="fullMessage"/></p>
					<p class="date"><s:date name="time" format="YYYY-MM-dd HH:mm"/></p>
					</li>
				</s:iterator>
				<?php } ?>
			
				<li>
					<span class="note"></span>				
					<p>创建请假条</p>
					<p class="date">
					<span class="fr date">
<!-- 	  						<s:date name="applyTime" format="YYYY-MM-dd HH:mm"/> -->
									<?=Html::encode($model['applyTime']) ?>
	  				</span>
					</p>
				</li>
			</ul>	
			
		</div>
		<!--end 跟踪流程-->
		<?php  if($model['state']==3){?>
			<div class="row">
	  		<div class="small-6 columns">	  			
	  			<button type="button" class="button disabled expand" name="outcome" onclick="window.location.href='workflowAction_toUpdate.action?id=<?php $model['id']?>">再次提交</button>
	  		</div>
	  		
	  		<div class="small-6 columns">
	  			<button type="button" class="button secondary expand" name="outcome" onclick="window.location.href='workflowAction_submitTaskByLeaveBillId.action?id=<?php $model['id']?>">放弃</button>
	  		</div>
	  	</div>
	  	<?php }?>
	</div>
</div>
</body>
</html>