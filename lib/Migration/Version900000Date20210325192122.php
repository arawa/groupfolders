<?php

declare(strict_types=1);

namespace OCA\GroupFolders\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version900000Date20210325192122 extends SimpleMigrationStep {
	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
		/** @var ISchemaWrapper $schema */
                $schema = $schemaClosure();

                if (!$schema->hasTable('group_folders_admins')) {
                        $table = $schema->createTable('group_folders_admins');
                        $table->addColumn('id', 'string', [
                                'notnull' => true,
                                'length' => 64,
                        ]);
                        $table->addColumn('type', 'string', [
                                'notnull' => true,
                                'length' => 64,
                        ]);
                }

		return $schema;
	}
}
