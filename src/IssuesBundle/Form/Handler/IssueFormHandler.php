<?php

namespace IssuesBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use IssuesBundle\Entity\Issue;
use IssuesBundle\Model\Service\Collaboration;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class IssueFormHandler
 * @package IssuesBundle\Form\Handler
 */
class IssueFormHandler
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Collaboration
     */
    private $collaboration;

    /**
     * IssueFormHandler constructor.
     * @param FormInterface $form
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param Collaboration $collaboration
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        Collaboration $collaboration
    ) {
        $this->form = $form;
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->collaboration = $collaboration;
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
     * @param \Symfony\Component\Form\Form $form
     * @return array
     */
    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();

                continue;
            }

            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * @param Issue $entity
     */
    private function onSuccess(Issue $entity)
    {
        $this->fillDefaultValues($entity);

        $this->collaboration->updateCollaborators($entity);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param Issue $entity
     */
    private function fillDefaultValues(Issue $entity)
    {
        if (!$entity->getReporter()) {
            $entity->setReporter($this->tokenStorage->getToken()->getUser());
        }

        if (!$entity->getAssignee()) {
            $entity->setAssignee($this->tokenStorage->getToken()->getUser());
        }
    }
}
