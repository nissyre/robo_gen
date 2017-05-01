<?php

require_once ('src/FieldsTrait.php');
require_once ('src/RelationshipTrait.php');

class RoboFile extends \Robo\Tasks {
    use FieldTrait;
    use RelationshipTrait;
}
