<?php

namespace Tlt\AdmnBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tlt\AdmnBundle\Entity\System;
use Tlt\AdmnBundle\Entity\Choose;
use Tlt\AdmnBundle\Form\Type\ChooseType;
use Tlt\AdmnBundle\Form\Type\SystemType;

class SystemController extends Controller
{
	/**
     * @Route("/systems/index", name="admin_systems_index")
     * @Template("TltAdmnBundle:System:index.html.twig")
     */
	public function indexAction(Request $request)
    {
		$form = $this->createForm(
			new ChooseType($this->getDoctrine()),
			new Choose(),
			array(
				'department' => array(
					'available'=>true,
					'showAll' => true
				),
			)
		);
		
		$form->handleRequest($request);
		
		$systems = null;
		
		if ($form->isValid()) {
			if ($form['department']->getData()!=0) {
				$systems = $this->getDoctrine()
					->getRepository('TltAdmnBundle:System')
					->findAllFromOneDepartmentOrderedByName($form['department']->getData());
			} else {
				$systems = $this->getDoctrine()
					->getRepository('TltAdmnBundle:System')
					->findAllOrderedByName();
			}
		}

        return $this->render(
						'TltAdmnBundle:System:index.html.twig',
						array(
							'form' => $form->createView(),
							'systems' => $systems
						)
					);
    }

	/**
     * @Route("/systems/add", name="admin_systems_add")
     * @Template("TltAdmnBundle:System:add.html.twig")
     */
	public function addAction(Request $request)
	{
		$system = new System();
		$form = $this->createForm( new SystemType(), $system);
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$user	=	$this->getUser();
			$system->setInsertedBy($user->getUsername());
			$system->setModifiedBy($user->getUsername());
			$system->setFromHost($this->container->get('request')->getClientIp());
			
			// perform some action, such as saving the task to the database
			$em = $this->getDoctrine()->getManager();
			$em->persist($system);
			$em->flush();
			
			return $this->redirect($this->generateUrl('admin_systems_success', array('action'=>'add')));
		}
		
		return $this->render('TltAdmnBundle:System:add.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
	/**
     * @Route("/systems/edit/{id}", name="admin_systems_edit")
     * @Template("TltAdmnBundle:System:edit.html.twig")
     */
	public function editAction(Request $request, $id)
	{
		$system = $this->getDoctrine()
			->getRepository('TltAdmnBundle:System')
			->find($id);
		
		$form = $this->createForm( new SystemType(), $system);
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$user	=	$this->getUser();
			$system->setModifiedBy($user->getUsername());
			$system->setFromHost($this->container->get('request')->getClientIp());
			
			// perform some action, such as saving the task to the database
			$em = $this->getDoctrine()->getManager();
			$em->flush();
			
			return $this->redirect($this->generateUrl('admin_systems_success', array('action'=>'edit')));
		}
		
		return $this->render('TltAdmnBundle:System:edit.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
	/**
     * @Route("/systems/success/{action}", name="admin_systems_success")
     * @Template("TltAdmnBundle:System:success.html.twig")
     */
	public function successAction($action)
	{
		return $this->render('TltAdmnBundle:System:success.html.twig', array('action'=>$action));
	}
}
