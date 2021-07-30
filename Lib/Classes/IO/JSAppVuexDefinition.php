<?php 

namespace igk\JS\VueJS\IO;

use igk\JS\VueJS\Polyfill;
use IGKException;
use function igk_resources_gets as __ ;

class JSAppVuexDefinition extends JSAppDefinition{

    public function BuildDef($polyfill, $sciptObjectToUpdate) {
        $src = JSExpression::Stringify((object)$this->definitions);
        if ($polyfill->getVersion() == 3){        
            $sciptObjectToUpdate->initApp .= " console.debug('check : '); if (typeof _vuex !=='undefined'){ console.debug('not found'); };";
            $sciptObjectToUpdate->initApp .= "const _vuex = Vuex.createStore(".$src.");";
            $sciptObjectToUpdate->appScript .= "this.use(_vuex);";
        } else{      
            $sciptObjectToUpdate->initApp .= "const store = new Vuex.Store(".$src.");\n";
            $sciptObjectToUpdate->initApp .= "def.store = store; ";
            // $sciptObjectToUpdate->appScript .= "Vue.use(Vuex);"; 
        }
     }
    
}