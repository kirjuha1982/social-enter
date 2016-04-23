<?php

use yii\helpers\Html;
use yii\web\View;

/** @var $this View */
/** @var $id string */
/** @var $services stdClass[] See EAuth::getServices() */
/** @var $action string */
/** @var $template string */
/** @var $class string */
/** @var $icon string */
/** @var $class_css string */
/** @var $popup bool */
/** @var $assetBundle string Alias to AssetBundle */

Yii::createObject(['class' => $assetBundle])->register($this);

// Open the authorization dilalog in popup window.
if ($popup) {
	$options = [];
	foreach ($services as $name => $service) {
		$options[$service->id] = $service->jsArguments;
	}
	$this->registerJs('$("#' . $id . '").eauth(' . json_encode($options) . ');');
}

?>

<?php if($template):?>
<div class="eauth" id="<?php echo $id; ?>">



	<?php if($template == 'icon'):?>
		<?php
		foreach ($services as $name => $service) {
			echo Html::a($service->title, [$action, 'service' => $name], [
					'class' => $class_css,
					'data-eauth-service' => $service->id,
			]).' ';
		}
		?>


	<?php else: ?>



		<?php
		foreach ($services as $name => $service) {
		echo Html::a($icon.' '.$service->title, [$action, 'service' => $name], [
					'class' => $class_css,
					'data-eauth-service' => $service->id,
			]);
		}
		?>


	<?php endif; ?>
</div>
<?php else:?>

<div class="eauth" id="<?php echo $id; ?>">
	<ul class="eauth-list">
		<?php
		foreach ($services as $name => $service) {
			echo '<li class="eauth-service eauth-service-id-' . $service->id . '">';
			echo Html::a($service->title, [$action, 'service' => $name], [
				'class' => 'eauth-service-link',
				'data-eauth-service' => $service->id,
			]);
			echo $template.'</li>';
		}
		?>
	</ul>
</div>
<?php endif; ?>