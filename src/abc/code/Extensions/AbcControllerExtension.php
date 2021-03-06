<?php
namespace Azt3k\SS\Extensions;
use SilverStripe\Control\Director;
use Azt3k\SS\Classes\RequirementsHelper;
use SilverStripe\Core\Extension;

/**
 * @author AzT3K
 */
class AbcControllerExtension extends Extension {

	public function onAfterInit() {
		RequirementsHelper::process_requirements();
	}

    public function HashedPath($file, $extension = null) {
        $absPath = Director::getAbsFile(trim($file  . ($extension ? '.' . $extension : ''), '/'));
        return $file . '?h=' . sha1_file($absPath);
    }

    public function TimestampedPath($file, $extension = null) {
        $absPath = Director::getAbsFile(trim($file . ($extension ? '.' . $extension : ''), '/'));
        return $file . '?m=' . filemtime($absPath);
    }

}
