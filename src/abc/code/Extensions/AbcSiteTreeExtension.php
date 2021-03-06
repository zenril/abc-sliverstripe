<?php
namespace Azt3k\SS\Extensions;
use SilverStripe\Control\Director;
use SilverStripe\ORM\DataExtension;

/**
 * @author AzT3K
 */
class AbcSiteTreeExtension extends DataExtension {

    private static $indexes = array(
        'Title' => true,
        'Content'  => array(
            'type' => 'fulltext',
            'columns' => ["Content"]
        )
    );

    public function HashedPath($file, $extension = null) {
        $absPath = Director::getAbsFile(trim($file  . ($extension ? '.' . $extension : ''), '/'));
        return $file . '?h=' . sha1_file($absPath);
    }

    public function TimestampedPath($file, $extension = null) {
        $absPath = Director::getAbsFile(trim($file . ($extension ? '.' . $extension : ''), '/'));
        return $file . '?m=' . filemtime($absPath);
    }

}
