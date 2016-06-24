<?php
/**
 * Created by PhpStorm.
 * User: anjey
 * Date: 24.06.16
 * Time: 10:45
 */

namespace Pixelant\PxaSocialFeed\Task;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use Pixelant\PxaSocialFeed\Utility\ConfigurationUtility;

/**
 * Class CleanUpAdditionalFieldProvider
 * @package Pixelant\PxaSocialFeed\Task
 */
class CleanUpTaskAdditionalFieldProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface {

    /**
     * @param array $taskInfo
     * @param \Pixelant\PxaSocialFeed\Task\CleanUpTask $task
     * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject
     * @return array
     */
    public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {
        $additionalFields = [];

        if ($parentObject->CMD == 'add') {
            $taskInfo['days'] = 0;
        }

        if ($parentObject->CMD == 'edit') {
            $taskInfo['days'] = $task->getDays();
        }

        $additionalFields['configs'] = [
            'code'     =>  ConfigurationUtility::getDaysInput($taskInfo['days']),
            'label'    => 'LLL:EXT:pxa_social_feed/Resources/Private/Language/locallang_db.xlf:scheduler.days',
            'cshKey'   => '',
            'cshLabel' => ''
        ];

        return $additionalFields;
    }

    /**
     * @param array $submittedData
     * @param \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject
     * @return bool
     */
    public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {
        // nothing to validate, just list of uids
        $valid = FALSE;

        if(!isset($submittedData['days']) && !intval($submittedData['days'])) {
            $parentObject->addMessage('Bad days value', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
        } else {
            $valid = TRUE;
        }

        return $valid;
    }

    /**
     * @param array $submittedData
     * @param \Pixelant\PxaSocialFeed\Task\CleanUpTask $task
     */
    public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task) {
        $task->setDays($submittedData['days']);
    }
}