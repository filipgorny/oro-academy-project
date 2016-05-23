<?php

namespace IssuesBundle\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use IssuesBundle\Entity\Issue;
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
     * IssueFormHandler constructor.
     * @param FormInterface $form
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ) {
        $this->form = $form;
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Issue $entity
     *
     * @return bool
     */
    public function process(Issue $entity)
    {
        $entity->setOwner($this->tokenStorage->getToken()->getUser());
dump($entity->getOwner());
        exit;
        $this->form->setData($entity);

        if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
            $this->form->submit($this->request);
            var_dump($this->form->getData());
            exit;

            if ($this->form->isValid()) {
                $this->onSuccess($entity);

                return true;
            } else {
                dump($this->getErrorMessages($this->form));
                exit;
            }
        }

        return false;
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
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
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
