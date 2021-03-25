<?php
/**
 * @copyright Copyright (c) 2021 Cyrille Bollu (cyr.debian@bollu.be)
 *
 * @license <GNU AGPL version 3 or any later version
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\GroupFolders\Settings;

use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

class SettingsManager {
	/** @var IDBConnection */
	private $connection;

	public function __construct(IDBConnection $connection) {
		$this->connection = $connection;
	}

	/**
	 * @return (string)[]
	 *
	 * @psalm-return array<{id: string, type: string}>
	 */
	public function getAdminUsers(): array {
		$query = $this->connection->getQueryBuilder();

		$query->select('id', 'type')
			->from('group_folders_admins');

		$rows = $query->execute()->fetchAll();

		$admins = [];
		foreach ($rows as $row) {
			$id = $row['id'];
			$admins[$id] = [
				'id' => $id,
				'type' => $row['type']
			];
		}

		return $admins;
	}
}
