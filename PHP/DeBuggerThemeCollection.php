<?php
namespace DeBugger;
/**
 * Debugger @version 1.0
 * 
 * @author Robert M. Parker <webmaster@yamiko.org>
 * 
 * @license
 * <This is a class for debugging code. It has an error handler and a few other useful methods>
 * Copyright (C) <2012>  Robert M. Parker <webmaster@yamiko.org>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or    
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the    
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * uses:
 *		syntaxhighlighter 3.0.83 <http://alexgorbatchev.com/SyntaxHighlighter/> 
 *			I modified the php brush to add numbers, user functions and 
 *			new keywords, constants and php function to the definitions.
 *      jQuery				<http://jquery.com/>
 * 
 * @todo
 *		Exception handling
 *		Error logging
 *		Email webmaster
 */
require_once 'DeBuggerTheme.php';
class DeBuggerThemeCollection {
	/**
	 * Default Theme Name
	 * @var int
	 */
	const NAME_DEFAULT = 0;
	
	/**
	 * Syntaxhighlighter default theme
	 * @var int
	 */
	const NAME_SH = 1;
	
	/**
	 * Syntaxhighlighter DJango theme name
	 * @var int
	 */
	const NAME_DJANGO = 2;
	
	/**
	 * Syntaxhighlighter Eclipse theme name
	 */
	const NAME_ECLIPSE = 3;
	
	/**
	 * Syntaxhighlighter Emacs theme name
	 * @var int
	 */
	const NAME_EMACS = 4;
	
	/**
	 * Syntaxhighlighter FadeToGrey theme name
	 * @var int
	 */
	const NAME_FADE_TO_GREY = 5;
	
	/**
	 * Syntaxhighlighter MDUltra theme name
	 * @var int
	 */
	const NAME_MD_ULTRA = 6;
	
	/**
	 * Syntaxhighlighter Midnight theme name
	 * @var int
	 */
	const NAME_MIDNIGHT = 7;
	
	/**
	 * Syntaxhighlighter RDark theme name
	 * @var int
	 */
	const NAME_RDARK = 8;
	
	/**
	 * Stores theme objects. Key is the name.
	 * @var array
	 */
	private static $ThemeObjects = [];
	
	/**
	 * stores settings for creating new themes. key is the name
	 * @var array
	 */
	private static $ThemeSettings = array(
		//default
		[	'background'					=> '#001', 
			'highlightedBackground'			=> '#400', 
			'hoverBackground'				=> '#200',
			'gutter'						=> '#666',
			'gutterBackground'				=> 'transparent',
			'gutterBorder'					=> '#400',
			'gutterHighlighted'				=> '#FFF',
			'gutterHighlightedBackground'	=> '#400',
			'plain'							=> '#FFF',
			'keyword'						=> '#39F',
			'constant'						=> '#FC3',
			'function'						=> '#F0E386',
			'variable'						=> '#9CF',
			'string'						=> '#FFA0A0',
			'script'						=> '#CCC',
			'number'						=> '#C6F',
			'comments'						=> '#999'],
		//syntax highlighter default
		[	'background'					=> 'white', 
			'highlightedBackground'			=> '#e0e0e0', 
			'hoverBackground'				=> '#e0e0e0',
			'gutter'						=> '#afafaf',
			'gutterBackground'				=> 'white',
			'gutterBorder'					=> '#6ce26c',
			'gutterHighlighted'				=> 'white',
			'gutterHighlightedBackground'	=> '#6ce26c',
			'plain'							=> 'black',
			'keyword'						=> '#069',
			'constant'						=> '#06C',
			'function'						=> '#ff1493',
			'variable'						=> '#aa7700',
			'string'						=> 'blue',
			'script'						=> '#069',
			'number'						=> 'red',
			'comments'						=> '#008200'],
		//Django
		[	'background'					=> '#0a2b1d', 
			'highlightedBackground'			=> '#233729', 
			'hoverBackground'				=> '#233729',
			'gutter'						=> '#497958',
			'gutterBackground'				=> '#41a83e',
			'gutterBorder'					=> '#41a83e',
			'gutterHighlighted'				=> '#0a2b1d',
			'gutterHighlightedBackground'	=> '#41a83e',
			'plain'							=> '#f8f8f8',
			'keyword'						=> '#96dd3b',
			'constant'						=> '#e0e8ff',
			'function'						=> '#ffaa3e',
			'variable'						=> '#ffaa3e',
			'string'						=> '#9df39f',
			'script'						=> '#96dd3b',
			'number'						=> 'red',
			'comments'						=> '#336442'],
		//Eclipse
		[	'background'					=> 'white', 
			'highlightedBackground'			=> '#c3defe', 
			'hoverBackground'				=> '#c3defe',
			'gutter'						=> '#787878',
			'gutterBackground'				=> '#d4d0c8',
			'gutterBorder'					=> '#d4d0c8',
			'gutterHighlighted'				=> 'white',
			'gutterHighlightedBackground'	=> '#d4d0c8',
			'plain'							=> 'black',
			'keyword'						=> '#7f0055',
			'constant'						=> '#0066cc',
			'function'						=> '#ff1493',
			'variable'						=> '#aa7700',
			'string'						=> '#2a00ff',
			'script'						=> '#7f0055',
			'number'						=> 'red',
			'comments'						=> '#3f5fbf'],
		//Emacs
		[	'background'					=> 'black', 
			'highlightedBackground'			=> '#2a3133', 
			'hoverBackground'				=> '#2a3133',
			'gutter'						=> '#d3d3d3',
			'gutterBackground'				=> '#990000',
			'gutterBorder'					=> '#990000',
			'gutterHighlighted'				=> 'black',
			'gutterHighlightedBackground'	=> '#990000',
			'plain'							=> '#d3d3d3',
			'keyword'						=> 'aqua',
			'constant'						=> '#ff9e7b',
			'function'						=> '#81cef9',
			'variable'						=> '#ffaa3e',
			'string'						=> '#ff9e7b',
			'script'						=> 'aqua',
			'number'						=> 'red',
			'comments'						=> '#ff7d27'],
		//Fade To Grey
		[	'background'					=> '#121212', 
			'highlightedBackground'			=> '#2c2c29', 
			'hoverBackground'				=> '#2c2c29',
			'gutter'						=> '#afafaf',
			'gutterBackground'				=> 'transparent',
			'gutterBorder'					=> '#3185b9',
			'gutterHighlighted'				=> '#121212',
			'gutterHighlightedBackground'	=> '#3185b9',
			'plain'							=> 'white',
			'keyword'						=> '#d01d33',
			'constant'						=> '#96daff',
			'function'						=> '#aaaaaa',
			'variable'						=> '#898989',
			'string'						=> '#e3e658',
			'script'						=> '#d01d33',
			'number'						=> 'red',
			'comments'						=> '#696854'],
		//MDUltra
		[	'background'					=> '#222222', 
			'highlightedBackground'			=> '#253e5a', 
			'hoverBackground'				=> '#253e5a',
			'gutter'						=> '#38566f',
			'gutterBackground'				=> 'transparent',
			'gutterBorder'					=> '#435a5f',
			'gutterHighlighted'				=> '#222222',
			'gutterHighlightedBackground'	=> '#435a5f',
			'plain'							=> 'lime',
			'keyword'						=> '#aaaaff',
			'constant'						=> 'yellow',
			'function'						=> '#ff8000',
			'variable'						=> 'aqua',
			'string'						=> 'lime',
			'script'						=> '#aaaaff',
			'number'						=> 'red',
			'comments'						=> '#428bdd'],
		//Midnight
		[	'background'					=> '#0f192a', 
			'highlightedBackground'			=> '#253e5a', 
			'hoverBackground'				=> '#253e5a',
			'gutter'						=> '#afafaf',
			'gutterBackground'				=> 'transparent',
			'gutterBorder'					=> '#435a5f',
			'gutterHighlighted'				=> '#0f192a',
			'gutterHighlightedBackground'	=> '#435a5f',
			'plain'							=> '#d1edff',
			'keyword'						=> '#b43d3d',
			'constant'						=> '#e0e8ff',
			'function'						=> '#ffaa3e',
			'variable'						=> '#ffaa3e',
			'string'						=> '#1dc116',
			'script'						=> '#b43d3d',
			'number'						=> 'yellow',
			'comments'						=> '#428bdd'],
		//RDark
		[	'background'					=> '#1b2426', 
			'highlightedBackground'			=> '#323e41', 
			'hoverBackground'				=> '#323e41',
			'gutter'						=> '#afafaf',
			'gutterBackground'				=> 'transparent',
			'gutterBorder'					=> '#435a5f',
			'gutterHighlighted'				=> '#1b2426',
			'gutterHighlightedBackground'	=> '#435a5f',
			'plain'							=> '#b9bdb6',
			'keyword'						=> '#5ba1cf',
			'constant'						=> '#e0e8ff',
			'function'						=> '#ffaa3e',
			'variable'						=> '#ffaa3e',
			'string'						=> '#5ce638',
			'script'						=> '#5ba1cf',
			'number'						=> 'yellow',
			'comments'						=> '#878a85']
	);
	
	/**
	 * Get a DeBuggerTheme from the collection
	 * @param string $name name of the theme
	 * @return DeBuggerTheme returns a DeBuggerTheme instance
	 * @throws \Exception The name does not exist
	 */
	public static function &Get($name = 0){
		//if it already has been made return it
		if(isset(self::$ThemeObjects[$name]))
			return self::$ThemeObjects[$name];
		//it is not made are there settings for it?
		if(!isset(self::$ThemeSettings[$name]))
			throw new \Exception(__METHOD__ . ' @PARAM $name does not corresponds to an existing theme');
		//create and return the new theme
		return self::_CreateInstance($name, self::$ThemeSettings[$name]);
	}
	
	/**
	 * Sets, and creates a theme
	 * @param string $name name of the new theme
	 * @param array $settings associative array with the settings for creating a new theme arg => value. @see DeBuggerTheme->__construct()
	 * @return DeBuggerTheme returns a DeBuggerTheme instance
	 * @throws \Exception The name already exist and can not be used for a new theme
	 */
	public static function &SetCreate($name, $settings){
		//is there a theme with the name already?
		if(isset(self::$ThemeObjects[$name]) || isset(self::$ThemeSettings[$name]))
			throw new \Exception(__METHOD__ . ' @PARAM $name corresponds to an existing theme. use another name');
		//create and return the new theme
		return self::_CreateInstance($name, $settings);
	}
	
	public static function Set($name, DeBuggerTheme $theme){
		//is there a theme with the name already?
		if(isset(self::$ThemeObjects[$name]) || isset(self::$ThemeSettings[$name]))
			throw new \Exception(__METHOD__ . ' @PARAM $name corresponds to an existing theme. use another name');
		self::$ThemeObjects[$name] = $theme;
		self::$ThemeSettings[$name] = [];//empty array. the object is created so there is no need to store the settings
	}
	
	/**
	 * Sets, creates and returns a new DeBuggerTheme
	 * @param string $name name of the new theme
	 * @param array $settings associative array with the settings for creating a new theme arg => value. @see DeBuggerTheme->__construct()
	 * @return DeBuggerTheme returns a DeBuggerTheme instance
	 */
	private static function &_CreateInstance(&$name, &$settings){
		self::$ThemeObjects[$name] = new DeBuggerTheme(
			$settings['background'],
			$settings['highlightedBackground'],
			$settings['hoverBackground'],
			$settings['gutter'],
			$settings['gutterBorder'],
			$settings['gutterBackground'],
			$settings['gutterHighlighted'],
			$settings['gutterHighlightedBackground'],
			$settings['plain'],
			$settings['keyword'],
			$settings['constant'],
			$settings['function'],
			$settings['variable'],
			$settings['string'],
			$settings['script'],
			$settings['number'],
			$settings['comments']
		);
		return self::$ThemeObjects[$name];
	} 
}
?>
