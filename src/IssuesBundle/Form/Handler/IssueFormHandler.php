<?php

namespace IssuesBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use IssuesBundle\Entity\Issue;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IssueFormHandler
 * @package IssuesBundle\Form\Handler
 */
class IssueFormHandler
{
    /**
     * @var FormInterface
     */
    protected $form;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     *
     * @param FormInterface $form
     * @param Request       $request
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(FormInterface $form, Request $request, EntityManagerInterface $entityManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Issue $entity
     *
     * @return bool
     */
    public function process(Issue $entity)
    {
        $this->form->setData($entity);

        if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
            $this->form->submit($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($entity);

                return true;
            }
        }
        
        return false;
    }
    /**
     * @param Issue $entity
     */
    protected function onSuccess(Issue $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
