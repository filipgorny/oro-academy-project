<?php

namespace IssuesBundle\Model\Service;

use IssuesBundle\Entity\Issue;

/**
 * Manager domain service, for adding users to collaboration list in the Issue model.
 *
 * Class Collaboration
 * @package IssuesBundle\Model
 */
class Collaboration
{
    public function updateCollaborators(Issue $issue, $newUsers = [])
    {
        $collaborators = $issue->getCollaborators();

        foreach ($newUsers as $user) {
            if (!$collaborators->contains($user)) {
                $collaborators->add($user);
            }
        }

        if (!$collaborators->contains($issue->getAssignee())) {
            $collaborators->add($issue->getAssignee());
        }

        if (!$collaborators->contains($issue->getReporter())) {
            $collaborators->add($issue->getReporter());
        }
        
        $issue->setCollaborators($collaborators);

        $issue->setCode($issue->getCode().' - edited');
    }
}
