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
class DeBuggerTheme {
/**
	 * Background color
	 * @var string
	 */
	public $Background;
	
	/**
	 * highlighted line background color
	 * @var string
	 */
	public $HighlightedBackground;
	
	/**
	 * file hover background color
	 * @var strin
	 */
	public $HoverBackground;
	
	/**
	 * line number color
	 * @var string
	 */
	public $Gutter;
	
	/**
	 * The gutters border color
	 * @var string
	 */
	public $GutterBorder;
	
	/**
	 * The gutter background color
	 * @var string
	 */
	public $GutterBackground;
	
	/**
	 * The gutter background color when the line is highlighted
	 * @var string
	 */
	public $GutterHighlightedBackground;
	
	/**
	 * highlighted line number color
	 * @var string
	 */
	public $GutterHighlighted;
	
	/**
	 * default/plain text color
	 * @var string
	 */
	public $Plain;
	
	/**
	 * keyword text color
	 * @var string
	 */
	public $Keyword;
	
	/**
	 * constants text color
	 * @var string
	 */
	public $Constant;
	
	/**
	 * function/method text color
	 * @var string
	 */
	public $Function;
	
	/**
	 * variable text color
	 * @var string
	 */
	public $Variable;
	
	/**
	 * string text color
	 * @var string
	 */
	public $String;
	
	/**
	 * number text color
	 * @var string
	 */
	public $Number;
	
	/**
	 * comments text color
	 * @var string
	 */
	public $Comments;
	
	/**
	 * script text color. This would be the php tags if $Html_script is true
	 * @var string
	 */
	public $Script;
	
	/**
	 * 
	 * @param string $background background color
	 * @param string $highlightedBackground highlighted line background color
	 * @param string $hoverBackground file list hover background color
	 * @param string $gutter gutter text color
	 * @param string $gutterBorder butter border color
	 * @param string $gutterBackground gutter background color
	 * @param string $gutterHighlighted gutter highlighted text colot
	 * @param string $gutterHighlightedBackground gutter highlighted background color
	 * @param string $plain plain text color
	 * @param string $keyword keyword color
	 * @param string $constant constant color
	 * @param string $function function color
	 * @param string $variable variable color
	 * @param string $string string color
	 * @param string $script script color
	 * @param string $number number color
	 * @param string $comments comments color
	 */
	public function __construct
		(
			$background						= '#001', 
			$highlightedBackground			= '#400', 
			$hoverBackground				= '#200',
			$gutter							= '#666',
			$gutterBorder					= '#400',
			$gutterBackground				= 'transparent',
			$gutterHighlighted				= '#FFF',
			$gutterHighlightedBackground	= '#400',
			$plain							= '#FFF',
			$keyword						= '#39F',
			$constant						= '#FC3',
			$function						= '#F0E386',
			$variable						= '#9CF',
			$string							= '#FFA0A0',
			$script							= '#CCC',
			$number							= '#C6F',
			$comments						= '#999'
		){
		$this->Background = $background;
		$this->Comments = $comments;
		$this->Constant = $constant;
		$this->Function = $function;
		$this->Gutter = $gutter;
		$this->GutterBorder = $gutterBorder;
		$this->GutterBackground = $gutterBackground;
		$this->GutterHighlighted = $gutterHighlighted;
		$this->GutterHighlightedBackground = $gutterHighlightedBackground;
		$this->HighlightedBackground = $highlightedBackground;
		$this->HoverBackground = $hoverBackground;
		$this->Keyword = $keyword;
		$this->Number = $number;
		$this->Plain = $plain;
		$this->Script = $script;
		$this->String = $string;
		$this->Variable = $variable;
	}
}

?>
