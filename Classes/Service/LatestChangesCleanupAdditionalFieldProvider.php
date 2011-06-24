<?php
/**
 * Created by JetBrains PhpStorm.
 * User: xavier
 * Date: 23.06.11
 * Time: 15:42
 * To change this template use File | Settings | File Templates.
 */

class tx_egovapi_service_latestChangesCleanupAdditionalFieldProvider implements tx_scheduler_AdditionalFieldProvider {

	/**
	 * @var string
	 */
	protected $extKey = 'egovapi';

	/**
	 * Default constructor.
	 */
	public function __construct() {
		$config = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey]);
		$config['data.']['communities'] = 'EXT:egovapi/Resources/Private/Data/communities.csv';

		$dao = t3lib_div::makeInstance('tx_egovapi_dao_dao', $config);
		tx_egovapi_domain_repository_factory::injectDao($dao);
	}

	/**
	 * Gets additional fields to render in the form to add/edit a task
	 *
	 * @param array Values of the fields from the add/edit task form
	 * @param tx_scheduler_Task The task object being eddited. Null when adding a task!
	 * @param tx_scheduler_Module Reference to the scheduler backend module
	 * @return array A two dimensional array, array('Identifier' => array('fieldId' => array('code' => '', 'label' => '', 'cshKey' => '', 'cshLabel' => ''))
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {
		/** @var $task tx_scheduler_Task */
		$additionalFields = array();
		$additionalFields['task_latestChangesCleanup_allCommunities'] = $this->getAllCommunitiesAdditionalField($taskInfo, $task, $parentObject);
		$additionalFields['task_latestChangesCleanup_community'] = $this->getCommunityAdditionalField($taskInfo, $task, $parentObject);

		return $additionalFields;
	}

	/**
	 * Adds a select field of available communities.
	 *
	 * @param array $taskInfo
	 * @param null|tx_egovapi_service_latestChangesCleanupTask $task
	 * @param tx_scheduler_Module $parentObject
	 * @return array
	 */
	protected function getAllCommunitiesAdditionalField(array &$taskInfo, tx_egovapi_service_latestChangesCleanupTask $task = NULL, tx_scheduler_Module $parentObject) {
		if ($parentObject->CMD === 'edit') {
			$checked = $task->allCommunities === TRUE ? 'checked="checked" ' : '';
		} else {
			$checked = '';
		}

		$fieldName = 'tx_scheduler[egovapi_latestChangesCleanup_allCommunities]';
		$fieldId = 'task_latestChangesCleanup_allCommunities';
		$fieldHtml = array();
		$fieldHtml[] = '<input type="checkbox" ' .
			'name="' . $fieldName . '" ' .
			$checked .
			'onChange="actOnChangeEgovapiLatestChangesCleanupAllCommunities(this)" ' .
			'id="' . $fieldId . '" />';
		$fieldHtml[] = '<script type="text/javascript">';
		$fieldHtml[] = 'function actOnChangeEgovapiLatestChangesCleanupAllCommunities(theCheckbox) {';
		$fieldHtml[] = '	if (theCheckbox.checked) {';
		$fieldHtml[] = '		Ext.fly("task_latestChangesCleanup_community").set({disabled: "disabled"});';
		$fieldHtml[] = '	} else {';
		$fieldHtml[] = '		Ext.fly("task_latestChangesCleanup_community").dom.removeAttribute("disabled");';
		$fieldHtml[] = '	}';
		$fieldHtml[] = '}';
		$fieldHtml[] = '</script>';

		$fieldConfiguration = array(
			'code' => implode(LF, $fieldHtml),
			'label' => 'LLL:EXT:egovapi/Resources/Private/Language/locallang_mod.xml:label.latestChangesCleanup.allCommunities',
			//'cshKey' => '_MOD_tools_txschedulerM1',
			'cshLabel' => $fieldId,
		);

		return $fieldConfiguration;
	}

	/**
	 * Adds a select field of available communities.
	 *
	 * @param array $taskInfo
	 * @param null|tx_egovapi_service_latestChangesCleanupTask $task
	 * @param tx_scheduler_Module $parentObject
	 * @return array
	 */
	protected function getCommunityAdditionalField(array &$taskInfo, tx_egovapi_service_latestChangesCleanupTask $task = NULL, tx_scheduler_Module $parentObject) {
		/** @var tx_egovapi_domain_repository_communityRepository $communityRepository */
		$communityRepository = tx_egovapi_domain_repository_factory::getRepository('community');
		/** @var tx_egovapi_domain_model_community[] $communities */
		$communities = $communityRepository->findAll();

		$options = array();
		$options[] = '<option value=""></option>';
		$options[] = '<option value="00-00">CH</option>';
		$previousCanton = '';
		foreach ($communities as $community) {
			if ($previousCanton !== substr($community->getId(), 0, 2)) {
				if ($previousCanton !== '') {
					$options[] = '</optgroup>';
				}
				$name = $community->getName();
				// Strip off "Kanton" or "Canton"
				$name = substr($name, strpos($name, ' ') + 1);
				$options[] = '<optgroup label="' . $name . '">';
			}

			$selected = $community->getId() === $task->community ? ' selected="selected"' : '';
			$options[] = '<option ' .
				'value="' . $community->getId() . '"' .
				$selected . '>' .
				$community->getName() .
				'</option>';

			$previousCanton = substr($community->getId(), 0, 2);
		}
		$options[] = '</optgroup>';

		$disabled = $task->allCommunities === TRUE ? 'disabled="disabled" ' : '';

		$fieldName = 'tx_scheduler[egovapi_latestChangesCleanup_community]';
		$fieldId = 'task_latestChangesCleanup_community';
		$fieldHtml = '<select ' .
			'name="' . $fieldName . '" ' .
			$disabled .
			'id="' . $fieldId . '" ' .
			'style="width:300px">' .
			implode(LF, $options) .
			'</select>';

		$fieldConfiguration = array(
			'code' => $fieldHtml,
			'label' => 'LLL:EXT:egovapi/Resources/Private/Language/locallang_mod.xml:label.latestChangesCleanup.community',
			'cshLabel' => $fieldId,
		);

		return $fieldConfiguration;
	}

	/**
	 * Validates the additional fields' values
	 *
	 * @param array $submittedData
	 * @param tx_scheduler_Module $parentObject
	 * @return boolean TRUE if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
		$validData = $this->validateAllCommunitiesAdditionalField($submittedData, $parentObject);
		$validData &= $this->validateCommunityAdditionalField($submittedData, $parentObject);

		return $validData;
	}

	/**
	 * Validates the all communities field.
	 *
	 * @param array $submittedData
	 * @param tx_scheduler_Module $parentObject
	 * @return boolean
	 */
	protected function validateAllCommunitiesAdditionalField(array &$submittedData, tx_scheduler_Module $parentObject) {
		$validData = FALSE;
		if (!isset($submittedData['egovapi_latestChangesCleanup_allCommunities'])) {
			$validData = TRUE;
		} elseif ($submittedData['egovapi_latestChangesCleanup_allCommunities'] === 'on') {
			$validData = TRUE;
		}

		return $validData;
	}

	/**
	 * Validates the community field.
	 *
	 * @param array $submittedData
	 * @param tx_scheduler_Module $parentObject
	 * @return boolean
	 */
	protected function validateCommunityAdditionalField(array &$submittedData, tx_scheduler_Module $parentObject) {
		$validData = FALSE;
		$allCommunities = isset($submittedData['egovapi_latestChangesCleanup_allCommunities'])
						  && $submittedData['egovapi_latestChangesCleanup_allCommunities'] === 'on';
		if ($allCommunities) {
			$validData = TRUE;
		} elseif ($submittedData['egovapi_latestChangesCleanup_community'] !== '') {
			$validData = TRUE;
		} else {
				// Issue error message
			$parentObject->addMessage($GLOBALS['LANG']->sL('LLL:EXT:egovapi/Resources/Private/Language/locallang_mod.xml:msg.invalidCommunity'), t3lib_FlashMessage::ERROR);
		}

		return $validData;
	}

	/**
	 * Takes care of saving the additional fields' values in the task's object
	 *
	 * @param array An array containing the data submitted by the add/edit task form
	 * @param tx_egovapi_service_latestChangesCleanupTask Reference to the scheduler backend module
	 * @return void
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		/** @var $task tx_egovapi_service_latestChangesCleanupTask */

		$task->allCommunities = $submittedData['egovapi_latestChangesCleanup_allCommunities'] === 'on' ? TRUE : FALSE;
		if (!$task->allCommunities) {
			$task->community = $submittedData['egovapi_latestChangesCleanup_community'];
		}

			// Reset last run which will implicitly flush cache next time the task is run
		$task->lastRun = 0;
	}

}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Service/LatestChangesCleanupAdditionalFieldProvider.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/egovapi/Classes/Service/LatestChangesCleanupAdditionalFieldProvider.php']);
}

?>