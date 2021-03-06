<?php
namespace Azt3k\SS\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldViewButton;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use Azt3k\SS\GridField\AbcGridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldComponent;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldConfig_Base;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordViewer;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Azt3k\SS\GridField\AbcGridFieldDetailForm;


/**
 * Encapsulates a collection of components following the {@link GridFieldComponent} interface.
 * While the {@link GridField} itself has some configuration in the form of setters,
 * most of the details are dealt with through components.
 *
 * For example, you would add a {@link GridFieldPaginator} component to enable
 * pagination on the listed records, and configure it through {@link GridFieldPaginator->setItemsPerPage()}.
 *
 * In order to reduce the amount of custom code required, the framework provides
 * some default configurations for common use cases:
 * - {@link GridFieldConfig_Base} (added by default to GridField)
 * - {@link GridFieldConfig_RecordEditor}
 * - {@link GridFieldConfig_RelationEditor}
 */
class AbcGridFieldConfig extends GridFieldConfig{

	/**
	 *
	 * @return GridFieldConfig
	 */
	public static function create(...$args){
		return new self($args);
	}

	/**
	 *
	 * @var ArrayList
	 */
	protected $components = null;

	/**
	 *
	 */
	public function __construct() {
		$this->components = new ArrayList();
	}

	/**
	 * @param GridFieldComponent $component
	 * @param string $insertBefore The class of the component to insert this one before
	 */
	public function addComponent(GridFieldComponent $component, $insertBefore = null) {
		if($insertBefore) {
			$existingItems = $this->getComponents();
			$this->components = new ArrayList;
			$inserted = false;
			foreach($existingItems as $existingItem) {
				if(!$inserted && $existingItem instanceof $insertBefore) {
					$this->components->push($component);
					$inserted = true;
				}
				$this->components->push($existingItem);
			}
			if(!$inserted) $this->components->push($component);
		} else {
			$this->getComponents()->push($component);
		}
		return $this;
	}

	/**
	 * @param GridFieldComponent One or more components
	 */
	public function addComponents($component = NULL) {
		$components = func_get_args();
		foreach($components as $component) $this->addComponent($component);
		return $this;
	}

	/**
	 * @param GridFieldComponent $component
	 * @return GridFieldConfig $this
	 */
	public function removeComponent(GridFieldComponent $component) {
		$this->getComponents()->remove($component);
		return $this;
	}

	/**
	 * @param String Class name or interface
	 * @return GridFieldConfig $this
	 */
	public function removeComponentsByType($type) {
		$components = $this->getComponentsByType($type);
		foreach($components as $component) {
			$this->removeComponent($component);
		}
		return $this;
	}

	/**
	 * @return ArrayList Of GridFieldComponent
	 */
	public function getComponents() {
		if(!$this->components) {
			$this->components = new ArrayList();
		}
		return $this->components;
	}

	/**
	 * Returns all components extending a certain class, or implementing a certain interface.
	 *
	 * @param String Class name or interface
	 * @return ArrayList Of GridFieldComponent
	 */
	public function getComponentsByType($type) {
		$components = new ArrayList();
		foreach($this->components as $component) {
			if($component instanceof $type) $components->push($component);
		}
		return $components;
	}

	/**
	 * Returns the first available component with the given class or interface.
	 *
	 * @param String ClassName
	 * @return GridFieldComponent
	 */
	public function getComponentByType($type) {
		foreach($this->components as $component) {
			if($component instanceof $type) return $component;
		}
	}
}

/**
 * A simple readonly, paginated view of records,
 * with sortable and searchable headers.
 */
class AbcGridFieldConfig_Base extends GridFieldConfig_Base {

	/**
	 *
	 * @param int $itemsPerPage - How many items per page should show up per page
	 * @return GridFieldConfig_Base
	 */
	public static function create(...$args){
		return new self($args);
	}

	/**
	 *
	 * @param int $itemsPerPage - How many items per page should show up
	 */
	public function __construct($itemsPerPage=null) {
		$this->addComponent(new GridFieldToolbarHeader());
		$this->addComponent($sort = new GridFieldSortableHeader());
		$this->addComponent($filter = new GridFieldFilterHeader());
		$this->addComponent(new GridFieldDataColumns());
		$this->addComponent($pagination = new GridFieldPaginator($itemsPerPage));

		$sort->setThrowExceptionOnBadDataType(false);
		$filter->setThrowExceptionOnBadDataType(false);
		$pagination->setThrowExceptionOnBadDataType(false);
	}
}

/**
 * Allows viewing readonly details of individual records.
 */
class AbcGridFieldConfig_RecordViewer extends GridFieldConfig_RecordViewer {

	public function __construct($itemsPerPage = null) {
		parent::__construct($itemsPerPage);

		$this->addComponent(new GridFieldViewButton());
		$this->addComponent(new AbcGridFieldDetailForm());
	}

}

/**
 *
 */
class AbcGridFieldConfig_RecordEditor extends GridFieldConfig_RecordEditor {

	/**
	 *
	 * @param int $itemsPerPage - How many items per page should show up
	 * @return GridFieldConfig_RecordEditor
	 */
	public static function create(...$args){
		return new self($args);
	}

	/**
	 *
	 * @param int $itemsPerPage - How many items per page should show up
	 */
	public function __construct($itemsPerPage=null) {

		$this->addComponent(new GridFieldButtonRow('before'));
		$this->addComponent(new GridFieldAddNewButton('buttons-before-left'));
		$this->addComponent(new GridFieldToolbarHeader());
		$this->addComponent($sort = new GridFieldSortableHeader());
		$this->addComponent($filter = new GridFieldFilterHeader());
		$this->addComponent(new GridFieldDataColumns());
		$this->addComponent(new GridFieldEditButton());
		$this->addComponent(new GridFieldDeleteAction());
		$this->addComponent($pagination = new GridFieldPaginator($itemsPerPage));
		$this->addComponent(new AbcGridFieldDetailForm());

		$sort->setThrowExceptionOnBadDataType(false);
		$filter->setThrowExceptionOnBadDataType(false);
		$pagination->setThrowExceptionOnBadDataType(false);
	}
}


/**
 * Similar to {@link GridFieldConfig_RecordEditor}, but adds features
 * to work on has-many or many-many relationships.
 * Allows to search for existing records to add to the relationship,
 * detach listed records from the relationship (rather than removing them from the database),
 * and automatically add newly created records to it.
 *
 * To further configure the field, use {@link getComponentByType()},
 * for example to change the field to search.
 * <code>
 * GridFieldConfig_RelationEditor::create()
 * 	->getComponentByType('GridFieldAddExistingAutocompleter')->setSearchFields('MyField');
 * </code>
 */
class AbcGridFieldConfig_RelationEditor extends GridFieldConfig_RelationEditor {

	/**
	 *
	 * @param int $itemsPerPage - How many items per page should show up
	 * @return GridFieldConfig_RelationEditor
	 */
	public static function create(...$args){
		return new self($args);
	}

	/**
	 *
	 * @param int $itemsPerPage - How many items per page should show up
	 */
	public function __construct($itemsPerPage=null) {

		$this->addComponent(new GridFieldButtonRow('before'));
		$this->addComponent(new GridFieldAddNewButton('buttons-before-left'));
		$this->addComponent(new GridFieldAddExistingAutocompleter('buttons-before-left'));
		$this->addComponent(new GridFieldToolbarHeader());
		$this->addComponent($sort = new GridFieldSortableHeader());
		$this->addComponent($filter = new GridFieldFilterHeader());
		$this->addComponent(new GridFieldDataColumns());
		$this->addComponent(new GridFieldEditButton());
		$this->addComponent(new GridFieldDeleteAction());
		$this->addComponent($pagination = new GridFieldPaginator($itemsPerPage));
		$this->addComponent(new AbcGridFieldDetailForm());

		$sort->setThrowExceptionOnBadDataType(false);
		$filter->setThrowExceptionOnBadDataType(false);
		$pagination->setThrowExceptionOnBadDataType(false);
	}
}
