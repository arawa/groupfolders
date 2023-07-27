<?php

namespace OCA\GroupFolders\Listeners;

use OCP\Files\IRootFolder;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCA\GroupFolders\Folder\FolderManager;
use OCA\Workspace\Events\WorkspaceCreateEvent;

class WorkspaceCreateListener implements IEventListener
{
	public function __construct(private FolderManager $folderManager,
		private IRootFolder $rootFolder,
	)
	{
	}

	private function getRootFolderStorageId(): ?int {
		return $this->rootFolder->getMountPoint()->getNumericStorageId();
	}

	public function handle(Event $event): void
	{
		if (!($event instanceof WorkspaceCreateEvent)) {
			return;
		}

		$workspace = $event->getSpace();
		$workspaceManagerGroup = $event->getWorkspaceManagerGroup();
		$workspaceUserGroup = $event->getWorkspaceUserGroup();

		$folderId = $this->folderManager->createFolder($workspace->getSpaceName());

		$this->folderManager->setFolderACL($folderId, true);

		$this->folderManager->addApplicableGroup(
            $folderId,
            $workspaceManagerGroup->getGID()
        );

		$this->folderManager->addApplicableGroup(
            $folderId,
            $workspaceUserGroup->getGID()
        );

		$this->folderManager->setManageACL(
            $folderId,
            "group",
            $workspaceManagerGroup->getGid(),
            true
        );

		// $groupfolder = $this->folderManager->getFolder($folderId, $this->getRootFolderStorageId());
		// create a event to send the groupfolder finally
		// $emit->on('WorkspaceGetGroupfolder', function() use ($groupfolder) {
		// 	return $this->folderManager->get($groupfolder->getFolderId());
		// });
	}
}
