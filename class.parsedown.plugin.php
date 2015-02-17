<?php if (!defined('APPLICATION')) {
  exit();
}
/*
  Copyright 2008, 2009 Vanilla Forums Inc.
  This file is part of Garden.
  Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
  Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
  You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
  Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
 */

$PluginInfo['Parsedown'] = array(
  'Description' => 'Adapts The New BBCode Parser to work with Vanilla.',
  'Version' => '1.0.0',
  'RequiredApplications' => array('Vanilla' => '2.1.8p2'),
  'RequiredTheme' => FALSE,
  'RequiredPlugins' => FALSE,
  'HasLocale' => FALSE,
  'Author' => "GyD",
  'AuthorEmail' => 'contact@gyd.be',
  'AuthorUrl' => 'http://gyd.be'
);


Gdn::FactoryInstall('ParsedownFormatter', 'ParsedownPlugin', __FILE__, Gdn::FactorySingleton);
Gdn::FactoryInstall('ParsedownExtraFormatter', 'ParsedownPlugin', __FILE__, Gdn::FactorySingleton);

class ParsedownPlugin extends Gdn_Plugin {

  private $class;

  /// CONSTRUCTOR ///
  public function __construct() {
    parent::__construct();
  }

  /**
   * @return Parsedown|ParsedownExtra
   */
  private function parser() {
    static $formatter;

    if (NULL != $formatter) {
      return $formatter;
    }

    require_once __DIR__ . '/parsedown/Parsedown.php';

    switch (C('Garden.InputFormatter')) {
      case 'ParsedownExtra':
        $parser = 'ParsedownExtra';
        require_once __DIR__ . '/parsedown-extra/ParsedownExtra.php';
        break;
      case 'Parsedown':
      default:
        $parser = 'Parsedown';
        break;
    }

    $formatter = new $parser();
    return $formatter;

  }

  /**
   * @param $Result
   * @return mixed
   */
  public function Format($Result) {
    $Result = $this->parser()->text($Result);
    return $Result;
  }
}