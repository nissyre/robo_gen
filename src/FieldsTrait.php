<?php

trait FieldTrait {

    public function cf($name, $label, $type) {
        $lbl = strtoupper('LBL_' . $name);
        $vardefs = array(
           'name' => $name,
           'vname' => $lbl,
           'type' => $type,
           'audited' => false,
           'importable' => true,
           'reportable' => true,
           'massupdate' => false,
           'duplicate_merge' => 'enabled',
        );
        $this->say("vardefs:");
        switch ( $type ) {
            case 'text':
                $vardefs['rows'] = 6;
                $vardefs['cols'] = 80;
                break;
            case 'multienum':
                $list_name = $name . '_list';
                $vardefs['max_size'] = 255;
                $vardefs['len'] = 255;
                $vardefs['duplicate_on_record_copy'] = 'always';
                $vardefs['options'] = $list_name;
                $vardefs['isMultiSelect'] = true;
                break;
            case 'enum':
                $list_name = $name . '_list';
                $vardefs['max_size'] = 255;
                $vardefs['len'] = 255;
                $vardefs['duplicate_on_record_copy'] = 'always';
                $vardefs['options'] = $list_name;
                break;
        }
        $this->say('VARDEFS:');
		$this->say($name . " => ");
        $this->say(var_export($vardefs, true));
        $this->say('LANGUAGE:');
        $label_text = '$mod_strings[\'' . $lbl . '\'] => \'' . $label . '\';';
        $this->say($label_text);
        //return array('vardefs' => $vardefs, 'label' => $label_text);
    }

}
