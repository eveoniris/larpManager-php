<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-annotation) on 2016-08-26 08:23:27.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace LarpManager\Entities;

use LarpManager\Entities\BaseParticipantHasRestauration;

/**
 * LarpManager\Entities\ParticipantHasRestauration
 *
 * @Entity()
 */
class ParticipantHasRestauration extends BaseParticipantHasRestauration
{
	public function __construct()
	{
		$this->setDate(new \Datetime('NOW'));	
	}
}