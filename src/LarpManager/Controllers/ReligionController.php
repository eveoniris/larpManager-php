<?php
namespace LarpManager\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Silex\Application;
use LarpManager\Form\ReligionForm;
use LarpManager\Form\ReligionBlasonForm;
use LarpManager\Form\ReligionLevelForm;
use Doctrine\ORM\Query;

/**
 * LarpManager\Controllers\ReligionController
 * 
 * @author kevin
 *
 */
class ReligionController
{
	/**
	 * API: fourni la liste des religions
	 * GET /api/religion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function apiListAction(Request $request, Application $app)
	{
		$qb = $app['orm.em']->createQueryBuilder();
		$qb->select('Religion')
			->from('\LarpManager\Entities\Religion','Religion');

		$query = $qb->getQuery();
	
		$religions = $query->getResult(Query::HYDRATE_ARRAY);
		return new JsonResponse($religions);
	}
	
	/**
	 * affiche la liste des religions
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function indexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Religion');
		$religions = $repo->findAllOrderedByLabel();
		
		return $app['twig']->render('religion/index.twig', array('religions' => $religions));
	}
	
	
	/**
	 * Liste des religions (pour les joueurs)
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function listAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\Religion');
		$religions = $repo->findAllOrderedByLabel();
		
		return $app['twig']->render('public/religion/list.twig', array('religions' => $religions));
	}
	
	/**
	 * Detail d'une religion
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function detailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
		
		$religion = $app['orm.em']->find('\LarpManager\Entities\Religion',$id);
		
		return $app['twig']->render('religion/detail.twig', array('religion' => $religion));
	}
	
	/**
	 * Ajoute une religion
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function addAction(Request $request, Application $app)
	{
		$religion = new \LarpManager\Entities\Religion();
		
		$form = $app['form.factory']->createBuilder(new ReligionForm(), $religion)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
		
		$form->handleRequest($request);

		// si l'utilisateur soumet une nouvelle religion
		if ( $form->isValid() )
		{
			$religion = $form->getData();
			
			/**
			 * Création du topic associés à cette religion
			 * Ce topic doit être placé dans le topic "culte"
			 * @var \LarpManager\Entities\Topic $topic
			 */
			$topic = new \LarpManager\Entities\Topic();
			$topic->setTitle($religion->getLabel());
			$topic->setDescription($religion->getDescription());
			$topic->setUser($app['user']);
			$topic->setTopic($app['larp.manager']->findTopic('TOPIC_CULTE'));
			$topic->setRight('CULTE');
				
			$app['orm.em']->persist($topic);
			$app['orm.em']->flush();
			
			$religion->setTopic($topic);
			$app['orm.em']->persist($religion);
			$app['orm.em']->flush();
			
			$topic->setObjectId($religion->getId());
			$app['orm.em']->flush();
			
			$app['session']->getFlashBag()->add('success', 'La religion a été ajoutée.');
				
			// l'utilisateur est redirigé soit vers la liste des religions, soit vers de nouveau
			// vers le formulaire d'ajout d'une religion
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('religion'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('religion.add'),301);
			}
		}
		
		return $app['twig']->render('religion/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie une religion. Si l'utilisateur clique sur "sauvegarder", la religion est sauvegardée et
	 * l'utilisateur est redirigé vers la liste des religions. 
	 * Si l'utilisateur clique sur "supprimer", la religion est supprimée et l'utilisateur est
	 * redirigé vers la liste des religions. 
	 * 
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateAction(Request $request, Application $app)
	{	
		$id = $request->get('index');
		
		$religion = $app['orm.em']->find('\LarpManager\Entities\Religion',$id);
		
		$form = $app['form.factory']->createBuilder(new ReligionForm(), $religion)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
		
		$form->handleRequest($request);
		
		if ( $form->isValid() )
		{
			$religion = $form->getData();
		
			if ( $form->get('update')->isClicked())
			{
				$app['orm.em']->persist($religion);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La religion a été mise à jour.');
		
				return $app->redirect($app['url_generator']->generate('religion.detail',array('index' => $id)),301);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($religion);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'La religion a été supprimée.');
				return $app->redirect($app['url_generator']->generate('religion'),301);
			}
		}		

		return $app['twig']->render('religion/update.twig', array(
				'religion' => $religion,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Met à jour le blason d'une religion
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function updateBlasonAction(Request $request, Application $app)
	{
		$religion = $request->get('religion');
	
		$form = $app['form.factory']->createBuilder(new ReligionBlasonForm(), $religion)
			->add('update','submit', array('label' => "Sauvegarder"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$files = $request->files->get($form->getName());
				
			$path = __DIR__.'/../../../private/img/blasons/';
			$filename = $files['blason']->getClientOriginalName();
			$extension = $files['blason']->guessExtension();
	
			if (!$extension || ! in_array($extension, array('png', 'jpg', 'jpeg','bmp'))) {
				$app['session']->getFlashBag()->add('error','Désolé, votre image ne semble pas valide (vérifiez le format de votre image)');
				return $app->redirect($app['url_generator']->generate('religion.detail',array('index' => $religion->getId())),301);
			}
				
			$blasonFilename = hash('md5',$app['user']->getUsername().$filename . time()).'.'.$extension;
				
			$image = $app['imagine']->open($files['blason']->getPathname());
			$image->resize($image->getSize()->widen(160));
			$image->save($path. $blasonFilename);
				
			$religion->setBlason($blasonFilename);
			$app['orm.em']->persist($religion);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success','Le blason a été enregistré');
			return $app->redirect($app['url_generator']->generate('religion.detail',array('index' => $religion->getId())),301);
		}
	
		return $app['twig']->render('admin/religion/blason.twig', array(
				'religion' => $religion,
				'form' => $form->createView(),
		));
	}
	
	/**
	 * affiche la liste des niveau de fanatisme
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function levelIndexAction(Request $request, Application $app)
	{
		$repo = $app['orm.em']->getRepository('\LarpManager\Entities\ReligionLevel');
		$religionLevels = $repo->findAllOrderedByIndex();
	
		return $app['twig']->render('religion/level/index.twig', array('religionLevels' => $religionLevels));
	}
	
	/**
	 * Detail d'un niveau de fanatisme
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function levelDetailAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$religionLevel = $app['orm.em']->find('\LarpManager\Entities\ReligionLevel',$id);
	
		return $app['twig']->render('religion/level/detail.twig', array('religionLevel' => $religionLevel));
	}
	
	/**
	 * Ajoute un niveau de fanatisme
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function levelAddAction(Request $request, Application $app)
	{
		$religionLevel = new \LarpManager\Entities\ReligionLevel();
	
		$form = $app['form.factory']->createBuilder(new ReligionLevelForm(), $religionLevel)
			->add('save','submit', array('label' => "Sauvegarder"))
			->add('save_continue','submit', array('label' => "Sauvegarder & continuer"))
			->getForm();
	
		$form->handleRequest($request);
	
		// si l'utilisateur soumet une nouvelle religion
		if ( $form->isValid() )
		{
			$religionLevel = $form->getData();
								
			$app['orm.em']->persist($religionLevel);
			$app['orm.em']->flush();
				
			$app['session']->getFlashBag()->add('success', 'Le niveau de religion a été ajoutée.');
	
			// l'utilisateur est redirigé soit vers la liste des niveaux de religions, soit vers de nouveau
			// vers le formulaire d'ajout d'un niveau de religion
			if ( $form->get('save')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('religion.level'),301);
			}
			else if ( $form->get('save_continue')->isClicked())
			{
				return $app->redirect($app['url_generator']->generate('religion.level.add'),301);
			}
		}
	
		return $app['twig']->render('religion/level/add.twig', array(
				'form' => $form->createView(),
		));
	}
	
	/**
	 * Modifie un niveau de religion. Si l'utilisateur clique sur "sauvegarder", le niveau de religion est sauvegardée et
	 * l'utilisateur est redirigé vers la liste des niveaux de religions.
	 * Si l'utilisateur clique sur "supprimer", le niveau religion est supprimée et l'utilisateur est
	 * redirigé vers la liste de niveaux de religions.
	 *
	 * @param Request $request
	 * @param Application $app
	 */
	public function levelUpdateAction(Request $request, Application $app)
	{
		$id = $request->get('index');
	
		$religionLevel = $app['orm.em']->find('\LarpManager\Entities\ReligionLevel',$id);
	
		$form = $app['form.factory']->createBuilder(new ReligionLevelForm(), $religionLevel)
			->add('update','submit', array('label' => "Sauvegarder"))
			->add('delete','submit', array('label' => "Supprimer"))
			->getForm();
	
		$form->handleRequest($request);
	
		if ( $form->isValid() )
		{
			$religionLevel = $form->getData();
	
			if ( $form->get('update')->isClicked())
			{
				$app['orm.em']->persist($religionLevel);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le niveau de religion a été mise à jour.');
	
				return $app->redirect($app['url_generator']->generate('religion.level.detail',array('index' => $id)),301);
			}
			else if ( $form->get('delete')->isClicked())
			{
				$app['orm.em']->remove($religionLevel);
				$app['orm.em']->flush();
				$app['session']->getFlashBag()->add('success', 'Le niveau de religion a été supprimée.');
				return $app->redirect($app['url_generator']->generate('religion.level'),301);
			}
		}
	
		return $app['twig']->render('religion/level/update.twig', array(
				'religionLevel' => $religionLevel,
				'form' => $form->createView(),
		));
	}
	
}