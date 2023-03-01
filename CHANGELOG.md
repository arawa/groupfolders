## 9.2.1

### Fixed

- Remove the personal section from settings ([#44](https://github.com/arawa/groupfolders/pull/44))

## 9.2.0


### Add

- Add the groups/users that manage the groupfolder.

## 9.1.0

### Add

- Delegate the sub-admin right to groups

### Fixed

- [#3a15b74](https://github.com/arawa/groupfolders/commit/34fbc30985620e2ea7f31d7dec77643dfc76ce27) Remove the at symbol in the annotation `RequireGroupFolderAdmin` from lib/DelegateAdminsMiddleware

## 9.0.3

### Fixed

- #1649 Display unsupported messages when trying to scan object store based group folder
- #1632 Correctly calculate directory sizes when using an object store primary storage @CarlSchwan
- #1611 Avoid double encoding the group name in the ACL options @juliushaertl


## 9.0.2

* [#1486](https://github.com/nextcloud/groupfolders/pull/1486) Only return user result once
* [#1539](https://github.com/nextcloud/groupfolders/pull/1539) Cancel ACL user/group search requests
* [#1544](https://github.com/nextcloud/groupfolders/pull/1544) Enforce string for folder id when obtaining the trash folder


## 9.0.1

### Fixed
* [#1332](https://github.com/nextcloud/groupfolders/pull/1332) Add tooltip for user/group name in sidebar ACL list
* [#1336](https://github.com/nextcloud/groupfolders/pull/1336) Fix "contenthash" not included in chunk filename
* [#1341](https://github.com/nextcloud/groupfolders/pull/1341) Cast groupfolder id to string when trying to create a new folder
* [#1347](https://github.com/nextcloud/groupfolders/pull/1347) Check for naming conflicts before returning the user mounts
* [#1348](https://github.com/nextcloud/groupfolders/pull/1348) Fix deletion failing even if there's an entry in the folder listing
* [#1397](https://github.com/nextcloud/groupfolders/pull/1397) preventDefault on folder create submit event
* [#1400](https://github.com/nextcloud/groupfolders/pull/1400) fix wrong method call to check restore permissions
* [#1430](https://github.com/nextcloud/groupfolders/pull/1430) Sidebar view: refresh ACL entries when fileInfo prop changes
* [#1435](https://github.com/nextcloud/groupfolders/pull/1435) Fixed searching for groups in the sharing sideview
* [#1465](https://github.com/nextcloud/groupfolders/pull/1465) Obtain cacheEntry for created folders and handle errors more gracefully

## 9.0.0

### Added

- Nextcloud 21 compatibility
- Improved database queries
- OCC command to empty the trashbin

### Fixed

- Load files client extension through file list plugin
- Make sure to only move in cache if it was not already done by the storage
- Fix occ when files_trashbin is disabled
- Add description of command to empty trashbin @rakekniven
- Fix oracle compatibility
- Add missing exit codes @Chartman123
- Add missing return code to avoid issues with occ commands
- Fix output path for chunks
- Fix file drop shared folders
- Check folder permissions when restoring a trashbin item

## 8.0.0

- Show inherited ACLs in the files sidebar
- Improve performance when querying managing users/groups
- Fix issue causing "$path is an integer" logging in versions backend
- Nextcloud 20 compatiblity

## 6.0.6

- ACL: Increase performance by selecting on indexed column @Deltachaos
- Use a lazy folder @rullzer

## 6.0.5

- Nextcloud 19 compatibility

## 6.0.4

- Check ACL before restoring files from the trashbin
- Do not allow restoring files at an existing target
- Return the mountpoint owner as a fallback
- Bump dependencies

## 6.0.3

- Do not ship unneeded files with the release

## 6.0.2

- Allow to detect the file path for shares inside of groupfolders, e.g. when they are matched in workflow rules
- Bump dependencies

## 6.0.1

- Fix sharing files from groupfolders trough ocs api
- Show the full path including groupfolder in trashbin

## 6.0.0

- Nextcloud 18 compatibility
- Search for users by display name
- Only check for admin permissions if needed
- Check for ACL list in trash backend
- Bump dependencies

## 5.0.4
- Fix etag propagation which caused the desktop client not syncing changes
- Check if the parent folder is updatable when moving

## 5.0.3
- Handle advanced permission rules for users/groups that no longer exist

## 5.0.2
- Allow longer path as groupfolder mount points

## 5.0.1
- Improved error handling when removing items from trash
- Fix groupfolders breaking updating calendar details    

## 5.0.0
- 17 compatiblity
- Use groupfolder storage for versioning

## 4.1.2
- Allow longer path as groupfolder mount points

## 4.1.1
- Improved error handling when removing items from trash
- Fix groupfolders breaking updating calendar details

## 4.1.0
- Allow groups to manage ACL permissions
- Bump dependencies
- Fix IE11 compatibility
- Check for naming conflicts before returning the user mouns

## 4.0.5
- Bump dependencies
- Update translations
- Proper values returned from Storage Wrapper fixing some etag bugs

## 4.0.4
- Fix issue with ACL cache returning empty result sets
- Bump dependencies

## 4.0.3
- Fix Collabora documents opening in read only in some cases
- Improve dark mode support

## 4.0.2
- Fix handling of public pages
- Fix advanced permissions not applying on the root of a groupfolder

## 4.0.1
- Fix not being able to delete advanced permission rules from the web interface
- Use display names in advanced permissions interface
- Fix not being able to open files in Collabora Online that don't have share permissions

## 4.0.0
- Access control list support for advanced permission management
- Improve performance of listing group folders with large filecache tables
- Block deleting of folders that have non-deletable items in them
- Improved admin page layout
- Fix groupfolder icons sometimes not being themed correctly.
- Fix moving shared groupfolder items to trashbin.

## 3.0.1

This release is aimed for Nextcloud 14/15 users who upgraded to 3.0.0 which was
falsely marked as compatible for those Nextcloud releases.

Additionally the following fixes are included

- Fix groupfolder icons sometimes not being themed correctly.
- Fix moving shared groupfolder items to trashbin. 

## 1.2.0

 - Allow changing the mount point of existing group folders
 - Add OCS api for managing folders
 - Fix folder icons in public shares
 - Merge permissions if a user has access to a folder trough multiple groups
