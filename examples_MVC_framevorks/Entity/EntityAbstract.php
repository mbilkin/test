<?
namespace Entity;

use Silicone\Application;

class EntityAbstract {
    
    protected $app;
    
    public function __construct($objects=null, Application $app=null, $FKkey = array(), $clear = 0)
    {
        $this->app = $app;
        
        if (property_exists(get_class($this), 'cdate')) {
            $this->cdate = new \DateTime();
        }
        
        if (is_object($objects) && is_a($app,'Application')) {
            $arr = get_object_vars($objects);
            $ppc_key=false;
            foreach ($arr as $key => $val) 
                if (property_exists(get_class($this), $key)) {
                    $_method = 'set'.$key;
                    if (is_array($FKkey) && count($FKkey)>0 && in_array($key,  array_keys($FKkey))) {
                            $entity = $app->entityManager()->getRepository($FKkey[$key]['entity'])->findOneBy(array($key=>$FKkey[$key]['value']));
                            // Вариант с getReference 
                            //$entity = $app->entityManager()->getReference($FKkey[$key]['entity'], $FKkey[$key]['value']);
                            $this->$_method($entity);
                    } else
                        $this->$_method($val);
                }   
                $app->entityManager()->merge($this);
            if ($clear) {
                $app->entityManager()->flush();
                //$app->entityManager()->clear();
            }
        }
    }
}