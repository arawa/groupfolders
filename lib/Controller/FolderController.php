<?php
/**
 * @copyright Copyright (c) 2017 Robin Appelman <robin@icewind.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\GroupFolders\Controller;

use OCA\GroupFolders\Folder\FolderManager;
use OCA\GroupFolders\Mount\MountProvider;
use OCA\GroupFolders\Settings\SettingsManager;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\Files\IRootFolder;
use OCP\IGroupManager;
use OCP\IRequest;

class FolderController extends OCSController {
	/** @var FolderManager */
	private $manager;
	/** @var IGroupManager */
	private $groupManager;
	/** @var MountProvider */
	private $mountProvider;
	/** @var IRootFolder */
	private $rootFolder;
	/** @var SettingsManager */
	private $settingsManager;
	/** @var string */
	private $userId;

	public function __construct(
		$AppName,
		IGroupManager $groupManager,
		IRequest $request,
		FolderManager $manager,
		MountProvider $mountProvider,
		IRootFolder $rootFolder,
		SettingsManager $settingsManager,
		$userId
	) {
		parent::__construct($AppName, $request);
		$this->groupManager = $groupManager;
		$this->manager = $manager;
		$this->mountProvider = $mountProvider;
		$this->rootFolder = $rootFolder;
		$this->settingsManager = $settingsManager;
		$this->userId = $userId;

		$this->registerResponder('xml', function ($data) {
			return $this->buildOCSResponseXML('xml', $data);
		});
	}

	/**
	 *
	 * @param string $userId
	 * @return bool // true when the user may access the Controller's methods
	 *
	 */
	private function mayAccess($userId) {
		// Admins may always access the Controller's methods
		if ($this->groupManager->isAdmin($userId)) {
			return true;
		}

		// Check if user has been granted access
		$admins = $this->settingsManager->getAdminUsers();
		foreach($admins as $admin) {
			if ($admin['type'] === 'user') {
				if ($this->userId === $admin['id']) {
					return true;
				}
			} else if ($admin['type'] === 'group') {
				// TODO: Check user group membership
			} else {
				// TODO: error or Circles
			}
		};

		return $false;
	}

	/**
	 * @NoAdminRequired
	 * @return DataResponse
	 */
	public function getFolders() {
		if ($this->mayAccess($this->userId)) {
			return new DataResponse($this->manager->getAllFoldersWithSize($this->getRootFolderStorageId()));
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @return DataResponse
	 */
	public function getFolder($id) {
		if ($this->mayAccess($this->userId)) {
			return new DataResponse($this->manager->getFolder((int)$id, $this->getRootFolderStorageId()));
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	private function getRootFolderStorageId() {
		if ($this->mayAccess($this->userId)) {
			return $this->rootFolder->getMountPoint()->getNumericStorageId();
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param string $mountpoint
	 * @return DataResponse
	 */
	public function addFolder($mountpoint) {
		if ($this->mayAccess($this->userId)) {
			$id = $this->manager->createFolder($mountpoint);
			return new DataResponse(['id' => $id]);
		} else {
			// TODO: Return proper error here
			return new DataResponse(['error','unauthorized']);
		}

	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @return DataResponse
	 */
	public function removeFolder($id) {
		if ($this->mayAccess($this->userId)) {
			$folder = $this->mountProvider->getFolder($id);
			if ($folder) {
				$folder->delete();
			}
			$this->manager->removeFolder($id);
			return new DataResponse(['success' => true]);
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @param string $mountPoint
	 * @return DataResponse
	 */
	public function setMountPoint($id, $mountPoint) {
		if ($this->mayAccess($this->userId)) {
			$this->manager->setMountPoint($id, $mountPoint);
			return new DataResponse(['success' => true]);
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @param string $group
	 * @return DataResponse
	 */
	public function addGroup($id, $group) {
		if ($this->mayAccess($this->userId)) {
			$this->manager->addApplicableGroup($id, $group);
			return new DataResponse(['success' => true]);
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @param string $group
	 * @return DataResponse
	 */
	public function removeGroup($id, $group) {
		if ($this->mayAccess($this->userId)) {
			$this->manager->removeApplicableGroup($id, $group);
			return new DataResponse(['success' => true]);
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @param string $group
	 * @param string $permissions
	 * @return DataResponse
	 */
	public function setPermissions($id, $group, $permissions) {
		if ($this->mayAccess($this->userId)) {
			$this->manager->setGroupPermissions($id, $group, $permissions);
			return new DataResponse(['success' => true]);
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}
	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @param string $group
	 * @param bool $manageAcl
	 * @return DataResponse
	 */
	public function setManageACL($id, $mappingType, $mappingId, $manageAcl) {
		if ($this->mayAccess($this->userId)) {
			$this->manager->setManageACL($id, $mappingType, $mappingId, $manageAcl);
			return new DataResponse(['success' => true]);
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @param float $quota
	 * @return DataResponse
	 */
	public function setQuota($id, $quota) {
		if ($this->mayAccess($this->userId)) {
			$this->manager->setFolderQuota($id, $quota);
			return new DataResponse(['success' => true]);
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @param bool $acl
	 * @return DataResponse
	 */
	public function setACL($id, $acl) {
		if ($this->mayAccess($this->userId)) {
			$this->manager->setFolderACL($id, $acl);
			return new DataResponse(['success' => true]);
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @param string $mountpoint
	 * @return DataResponse
	 */
	public function renameFolder($id, $mountpoint) {
		if ($this->mayAccess($this->userId)) {
			$this->manager->renameFolder($id, $mountpoint);
			return new DataResponse(['success' => true]);
		} else {
			//TODO: Should return an access denied here
			return new DataResponse(['error','unauthorized']);
		}
	}

	/**
	 * Overwrite response builder to customize xml handling to deal with spaces in folder names
	 *
	 * @param string $format json or xml
	 * @param DataResponse $data the data which should be transformed
	 * @since 8.1.0
	 * @return \OC\AppFramework\OCS\BaseResponse
	 */
	private function buildOCSResponseXML($format, DataResponse $data) {
		/** @var array $folderData */
		$folderData = $data->getData();
		if (isset($folderData['id'])) {
			// single folder response
			$folderData = $this->folderDataForXML($folderData);
		} else if (is_array($folderData) && count($folderData) && isset(current($folderData)['id'])) {
			// folder list
			$folderData = array_map([$this, 'folderDataForXML'], $folderData);
		}
		$data->setData($folderData);
		return new \OC\AppFramework\OCS\V1Response($data, $format);
	}

	private function folderDataForXML($data) {
		$groups = $data['groups'];
		$data['groups'] = [];
		foreach($groups as $id => $permissions) {
			$data['groups'][] = ['@group_id' => $id, '@permissions' => $permissions];
		}
		return $data;
	}

	/**
	 * @NoAdminRequired
	 * @param $id
	 * @param $fileId
	 * @param string $search
	 * @return DataResponse
	 */
	public function aclMappingSearch($id, $fileId, $search = ''): DataResponse {
		$users = [];
		$groups = [];

		if ($this->manager->canManageACL($id, $this->userId) === true) {
			$groups = $this->manager->searchGroups($id, $search);
			$users = $this->manager->searchUsers($id, $search);
		}
		return new DataResponse([
			'users' => $users,
			'groups' => $groups,
		]);


	}
}
