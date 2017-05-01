<?php

trait RelationshipTrait
{

   protected $relationship_count = null;
   protected $relationship_name  = null;
   protected function getVardefsSiteOne($module)
   {
      $module_table       = strtolower($module);
      $simple_module_name = $this->getRelationshipModuleTable($module);
      //$dictionary["Lead"]["fields"][$this->relationship_name] =
      $result = array();

      $result[$this->relationship_name] = array(
         'name'         => $this->relationship_name,
         'type'         => 'link',
         'relationship' => $this->relationship_name,
         'source'       => 'non-db',
         'module'       => $module,
         'bean_name'    => $module,
         'side'         => 'right',
         'vname'        => strtoupper('LBL_' . $module . ''),
         'id_name'      => $this->getRelationshipIdName($module),
         'link-type'    => 'one'
      );
      //$dictionary["Lead"]["fields"]["{$simple_module_name}_name"] =
      $result['{$simple_module_name}_name'] = array(
         'name'       => '{$simple_module_name}_name',
         'type'       => 'relate',
         'source'     => 'non-db',
         'vname'      => strtoupper('LBL_' . $module . '_TITLE'),
         'save'       => true,
         'id_name'    => $this->getRelationshipIdName($module),
         'link'       => $this->relationship_name,
         'table'      => $module_table,
         'module'     => $module,
         'rname'      => 'name',
         'enforced'   => '',
         'dependency' => 'equal($lead_source,"distribution_partner")'
      );
      //$dictionary["Lead"]["fields"][$this->getRelationshipIdName($module)] =
      $result[$this->getRelationshipIdName($module)]=array(
         'name'            => $this->getRelationshipIdName($module),
         'type'            => 'id',
         'source'          => 'non-db',
         'vname'           => strtoupper('LBL_' . $module . '_ID'),
         'id_name'         => $this->getRelationshipIdName($module),
         'link'            => $this->relationship_name,
         'table'           => $module_table,
         'module'          => $module,
         'rname'           => 'id',
         'reportable'      => false,
         //'side'            => 'right',
         'massupdate'      => false,
         'duplicate_merge' => 'disabled',
         'hideacl'         => true
      );
      return $result;
   }

   protected function getVardefsSiteMany($module)
   {
      //$dictionary[$module_for]['fields'][$relationship_name] =
      return array( $this->relationship_name =>
         array(
         'name'         => $this->relationship_name,
         'type'         => 'link',
         'relationship' => $this->relationship_name,
         'source'       => 'non-db',
         'module'       => $module,
         'bean_name'    => $module,
         'vname'        => strtoupper('LBL_' . $module . '_TITLE'),
         'id_name'      => $this->getRelationshipIdName($module)
      ));
   }
   protected function getVardefsRelationship($module_1, $module_n)
   {
      $result = array(array(
         'lhs_module'        => $module_1,
         'lhs_table'         => $this->getRelationshipModuleTable($module_1),
         'lhs_key'           => $this->getRelationshipIdName($module_1),

         'rhs_module'        => $module_n,
         'rhs_table'         => $this->getRelationshipModuleTable($module_n),
         'rhs_key'           => 'id',
         'relationship_type' => 'one-to-many'
         //'relationship_role_column'       => 'parent_type',
         //'relationship_role_column_value' => 'Accounts'
      ));

      return $result;
   }

   public function getRelationshipIdName($module)
   {
      return strtolower($module) . '_id' . (!is_null($this->relationship_count) ? "_{$this->relationship_count}" : "");
   }
   public function getRelationshipModuleTable($module)
   {
      return strtolower($module);
   }
   public function getRelationshipLblSiteMany($module)
   {
      $result                                          = array();
      $result[strtoupper('LBL_' . $module . '_TITLE')] = $module;
      return $result;
   }
   public function getRelationshipLblSiteOne($module)
   {
      $result                                          = array();
      $result[strtoupper('LBL_' . $module . '')]       = "{$module} (Link) ";
      $result[strtoupper('LBL_' . $module . '_TITLE')] = $module;
      $result[strtoupper('LBL_' . $module . '_ID')]    = "{$module} (Id) ";
      return $result;
   }
   public function getRelationshipSubpanelBWC($module)
   {
      //$layout_defs["Accounts"]["subpanel_setup"]['ev_contactsemployment_accounts'] =
      return array(
         'order'             => 100,
         'module'            => $module,
         'subpanel_name'     => 'default',
         'sort_order'        => 'asc',
         'sort_by'           => 'id',
         'title_key'         => strtoupper('LBL_' . $module . '_TITLE'),
         'get_subpanel_data' => $this->relationship_name,
         'top_buttons'       => array(
            array('widget_class' => 'SubPanelTopButtonQuickCreate'),
            array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect')
         )
      );
   }
    public function getRelationshipSubpanel7($module)
   {
      //$layout_defs["Accounts"]["subpanel_setup"]['ev_contactsemployment_accounts'] =
      return ;
   }
   public function rc($module_a, $module_b, $type)
   {

      $this->relationship_name = strtolower("{$module_a}_{$module_b}").(!is_null($this->relationship_count) ? "_{$this->relationship_count}" : "");

      $result = array(
         $module_a => array(),
         $module_b => array()
      );
      switch ($type) {
         case '1n':
            // vardefs
            $vardefs[$module_a]['fields'] = $this->getVardefsSiteOne($module_b);
            $vardefs[$module_b]['fields'] = $this->getVardefsSiteMany($module_a);
            if (strpos($module_a, 'ev') === 0) {
               $vardefs[$module_a]['relationship'] = $this->getVardefsRelationship($module_a, $module_b);
            } elseif (strpos($module_b, 'ev') === 0) {
               $vardefs[$module_b]['relationship'] = $this->getVardefsRelationship($module_a, $module_b);
            } else {
               $vardefs[$module_a]['relationship'] = $this->getVardefsRelationship($module_a, $module_b);
            }
            $result[$module_a]['vardefs'] = $vardefs[$module_a];
            $result[$module_b]['vardefs'] = $vardefs[$module_b];
            //labels
            $labels[$module_a]           = $this->getRelationshipLblSiteOne($module_a);
            $labels[$module_b]           = $this->getRelationshipLblSiteMany($module_b);
            $result[$module_a]['labels'] = $labels[$module_a];
            $result[$module_b]['labels'] = $labels[$module_b];
            // Subpanel
            $subpanel[$module_b]['BWC']    = $this->getRelationshipSubpanelBWC($module_b);
            $subpanel[$module_b]['7']      = $this->getRelationshipSubpanel7($module_b);
            $result[$module_b]['subpanel'] = $subpanel[$module_b];
            break;

      }
      //$this->say("vardefs:");
      foreach ($result as $module => $module_params) {
         $this->say("----- {$module} ----");
         foreach ($module_params as $type => $params) {
            $this->say("--". strtoupper($type).":");
            $this->say(var_export($params, true));
         }
      }
      //return array('vardefs' => $vardefs, 'label' => $label_text);
   }

}
