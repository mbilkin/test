<?
namespace Controller;

use Silicone\Route;
use Silicone\Controller;
use Entity\Rule;
use Entity\Param;
use Entity\Schedule;
use Form\RuleFormType;
use Form\ParamFormType;
use Form\ScheduleFormType;
use Symfony\Component\HttpFoundation\Request;

class Strategy extends Controller {

    /**
     * @Route("/rule", name="list_rules")
     */
    public function indexAction(Request $request) {
        
        $response = $this->render('rules.twig', array(
            'entities' => $this->getRulesRepository()->findAll(),
            'title' => 'Стратегии',
            'create_title'=> 'Создание новой стратегии'
        ));
        return $response;
    }
    
    /**
     * @Route("/schedule", name="list_schedules")
     */
    public function indexSchedule(Request $request) {
        
        $response = $this->render('schedules.twig', array(
            'entities' => $this->getScheduleRepository()->findBy(array(),array('Priority' => 'DESC')),
            'title' => 'Расписание',
            'create_title'=> 'Создание нового времени'
        ));
        return $response;
    }

    /**
     *  @Route("/rule/{id}", name="rule_show")
     */
    public function showAction($id) {
        $template =  ($id=='new') ? 'ruleAdd.twig' : 'ruleEdit.twig';
        $rule = ($id=='new') ? new Rule() : $this->getRulesRepository()->find($id);
        
        if (!$rule) {
            exit;
            //throw $this->createNotFoundException('Unable to find Rule entity.');
        }
        $form = $this->app->formType(new RuleFormType(),$rule);
        
        if ($this->request->isMethod('POST') && $this->app->isGranted('ROLE_ADMIN')) {
            $form->bind($this->request);

            if ($form->isValid()) {
                /** @var $rule \Entity\Rule */
                $this->app->entityManager()->persist($rule);
                $this->app->entityManager()->flush();

                return $this->app->redirect($this->app->url('rule_show', array('id'=>$rule->getRuleID())));
            }
        }
        //var_dump($rule->getName());
        $response =  $this->render($template, array(
            'entity' => $rule,
            'params' => $rule->getParams(),
            'schedules' => $rule->getSchedules(),
            'form' => $form->createView()
        ));
        return $response;
        
    }
    
    
    /**
     *  @Route("/schedule/{id}/", name="schedule_show")
     */
    public function showSchedule($id) {
        $showRule=true;
        $template =  ($id=='new') ? 'scheduleAdd.twig' : 'scheduleEdit.twig';
        $ruleId = $this->request->query->get('ruleId');
        if (!empty($ruleId)) $showRule=false;
        if ($id=='new') {
            $schedule = new Schedule();
            $schedule->setBlocked(0);
        } else
            $schedule = $this->getScheduleRepository()->find($id);
        
        if (!$schedule) {
            exit;
            //throw $this->createNotFoundException('Unable to find Rule entity.');
        }
        
        if ($showRule) {
            
            $schedule->setintRuleIDs();
            
            // берем список стратегий
            $dql = "SELECT c.RuleID, c.name FROM Entity\Rule c ";
            $_rules = $this->app->entityManager()->createQuery($dql)
                                                 ->getScalarResult(); 
            $_ruleNames = $this->array_value_recursive('name',$_rules);
            $_ruleIds = $this->array_value_recursive('RuleID',$_rules);
            $rules = array_combine($_ruleIds,$_ruleNames);
        } else {
            $rules = array();
            $schedule->setRuleID($this->getRulesRepository()->find($ruleId));
        }    
        
        
        $form = $this->app->formType(new ScheduleFormType($rules, $showRule),$schedule);
        
        if ($this->request->isMethod('POST') && $this->app->isGranted('ROLE_ADMIN')) {
            $form->bind($this->request);
            
            if ($form->get('Start')->getData()>$form->get('Finish')->getData())
                        $form->addError(new \Symfony\Component\Form\FormError("дата Начала не может быть больше даты Конца"));
            
            if ($form->isValid()) {
                
                if ($showRule) {
                    $existRules = $schedule->getintRuleID();
                
                    $Rule = $this->app->entityManager()->getReference('Entity\\Rule',$existRules);
                    $schedule->setRuleID($Rule);
                }
                
                $this->app->entityManager()->persist($schedule);
                $this->app->entityManager()->flush();
                
                return ($showRule)  
                    ? $this->app->redirect($this->app->url('schedule_show', array('id'=>$schedule->getScheduleID())))
                    : $this->app->redirect($this->app->url('rule_show', array('id'=>$ruleId)));
            }
        }
        
        $response =  $this->render($template, array(
            'entity' => $schedule,
            'ruleId'=>(($showRule)?false:$ruleId),
            'form' => $form->createView()
        ));
        return $response;
    }
    
    
    
    /**
     *  @Route("/param/{id}/{ruleId}/", name="param_show")
     */
    public function showParam($id,$ruleId) {
        $template =  ($id=='new') ? 'paramAdd.twig' : 'paramEdit.twig';
        if ($id=='new') {
            $param = new Param();
            $param->setRule($this->getRulesRepository()->find($ruleId));
        } else
            $param = $this->getParamsRepository()->find($id);
        
        if (!$param) {
            exit;
            //throw $this->createNotFoundException('Unable to find Rule entity.');
        }
        $form = $this->app->formType(new ParamFormType(),$param);
        
        if ($this->request->isMethod('POST') && $this->app->isGranted('ROLE_ADMIN')) {
            $form->bind($this->request);

            if ($form->isValid()) {
                /** @var $rule \Entity\Rule */
                $ruleId = $param->getRule()->getRuleID();
                $this->app->entityManager()->persist($param);
                $this->app->entityManager()->flush();
                
                return $this->app->redirect($this->app->url('rule_show', array('id'=>$ruleId)));
            }
        }
        
        $response =  $this->render($template, array(
            'entity' => $param,
            'ruleId' => $ruleId,
            'form' => $form->createView()
        ));
        return $response;
    }
    
    /**
     *
     * @Route("/param/{id}/delete", name="param_delete")
     */
    public function deleteParam($id) {
        $param = $this->getParamsRepository()->find($id);
        if ($param) {
            $rule = $param->getRule();
            if ($this->app->isGranted('ROLE_ADMIN')) {
                $this->app->entityManager()->remove($param);
                $this->app->entityManager()->flush();
            }
            return $this->app->redirect($this->app->url('rule_show', array('id'=>$rule->getRuleID())));
        }
        exit;
    }
    
     /**
     *
     * @Route("/rule/{id}/delete", name="rule_delete")
     */
    public function deleteAction($id) {
        $rule = $this->getRulesRepository()->find($id);
        if ($rule && $this->app->isGranted('ROLE_ADMIN')) {
            $this->app->entityManager()->remove($rule);
            $this->app->entityManager()->flush();
            //throw $this->createNotFoundException('Unable to find Rule entity.');
        }
        return $this->app->redirect($this->app->url('list_rules'));
    }
    
     /**
     *
     * @Route("/schedule/{id}/delete", name="schedule_delete")
     */
    public function deleteSchedule($id) {
        $schedule = $this->getScheduleRepository()->find($id);
        if ($schedule && $this->app->isGranted('ROLE_ADMIN')) {
            $this->app->entityManager()->remove($schedule);
            $this->app->entityManager()->flush();
            //throw $this->createNotFoundException('Unable to find Rule entity.');
        }
        return $this->app->redirect($this->app->url('list_schedules'));
    }
    
    private function getRulesRepository() {
        $em = $this->app->entityManager();
        return $em->getRepository('Entity\\Rule');
    }
    
    private function getParamsRepository() {
        $em = $this->app->entityManager();
        return $em->getRepository('Entity\\Param');
    }
    
    private function getPhraseRepository() {
        $em = $this->app->entityManager();
        return $em->getRepository('Entity\\Phrase');
    }
    
    private function getScheduleRepository() {
        $em = $this->app->entityManager();
        return $em->getRepository('Entity\\Schedule');
    }
    
    protected function array_value_recursive($key, array $arr){
        $val = array();
        array_walk_recursive($arr, function($v, $k) use($key, &$val){
            if($k == $key) array_push($val, $v);
        });
        return count($val) > 1 ? $val : array_pop($val);
    }
}
?>
