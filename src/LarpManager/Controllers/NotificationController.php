<?php

/**
 * LarpManager - A Live Action Role Playing Manager
 * Copyright (C) 2016 Kevin Polez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace LarpManager\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

use LarpManager\Form\NewMessageForm;
use LarpManager\Entities\User;
use LarpManager\Entities\Notification;

/**
 * LarpManager\Controllers\NotificationController
 *
 * @author kevin
 *
 */
class NotificationController
{
	public function removeAction(Application $app, Request $request, Notification $notification)
	{
		if ( $notification->getUser() != $app['user'])
		{
			return $app->redirect($app['url_generator']->generate('homepage'),301);
		}
		
		$app['orm.em']->remove($notification);
		$app['orm.em']->flush();
		return $app->redirect($app['url_generator']->generate('homepage'),301);
	}
}