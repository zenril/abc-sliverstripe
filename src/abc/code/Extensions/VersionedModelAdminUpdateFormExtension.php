<?php
namespace Azt3k\SS\Extensions;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Versioned\Versioned;
use Azt3k\SS\GridField\VersionedGridFieldDetailForm;
use SilverStripe\Core\Extension;
/**
 * VersionedModelAdmin
 * replaces the scaffolded gridfield for versioned objects with a VersionedGridFieldDetailForm
 * See README for details
 *
 * @author Tim Klein, Dodat Ltd <tim[at]dodat[dot]co[dot]nz>
 */
class VersionedModelAdminUpdateFormExtension extends Extension {

	function onBeforeInit() {
		Versioned::set_stage('Stage');
	}

	function updateEditForm($form) {
		$fieldList = $form->Fields();

		foreach($fieldList as $field) {
			if($field instanceof GridField) {
				$class = $field->getList()->dataClass();
				if($class::has_extension("Versioned")) {
					$config = $field->getConfig();
					$config->removeComponentsByType('GridFieldDeleteAction')
						->removeComponentsByType('GridFieldDetailForm')
						->addComponents(new VersionedGridFieldDetailForm());
					$field->setConfig($config);
				}
			}
		}
	}

}
