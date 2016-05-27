<?php


namespace IssuesBundle\Controller\Dashboard;

use Oro\Bundle\TestFrameworkBundle\Pages\Workflow;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @package IssuesBundle\Controller\Dashboard
 */
class DashboardController extends Controller
{

    /**
     * @Route(
     *      "/dashboard/widgets/issues/{widget}",
     *      name="issues.issues_by_status_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("IssuesBundle:Dashboard:issuesByStatusChart.html.twig")
     * @param $widget
     * @return array
     */
    public function issuesByStatusChartAction($widget)
    {
        $data = [];

        $steps = $this->getDoctrine()->getRepository('Oro\Bundle\WorkflowBundle\Entity\WorkflowStep')
            ->createQueryBuilder('step')
            ->join('Oro\Bundle\WorkflowBundle\Entity\WorkflowDefinition', 'definition')
            ->where('definition.name = :definitionName')
            ->setParameter('definitionName', 'issue_resolving')
            ->getQuery()
            ->getResult();

        /** @var WorkflowStep $step */
        foreach ($steps as $step) {
            $count = $this->getDoctrine()
                ->getRepository('IssuesBundle:Issue')
                ->createQueryBuilder('i')
                ->select('count(i.id) as issues_count')
                ->where('i.workflowStep = :step')
                ->setParameter('step', $step)
                ->getQuery()
                ->getSingleScalarResult();

            $data[] = [
                'label' => $step->getLabel(),
                'issues_count' => $count
            ];
        }

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);

        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($data)
            ->setOptions(
                [
                    'name' => 'bar_chart',
                    'data_schema' => [
                        'label' => ['field_name' => 'label'],
                        'value' => ['field_name' => 'issues_count']
                    ],
                    'settings' => ['xNoTicks' => count($data)],
                ]
            )
            ->getView();

        return $widgetAttr;
    }
}
