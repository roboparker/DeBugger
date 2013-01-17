<?php
namespace Debugger\Themes;
/**
 * Debugger @version 2.1 pre release
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
 *			The styles folder is removed since I have themes which can output the css styles
 *      jQuery				<http://jquery.com/>
 * 
 */
class Emacs extends Debugger\Theme{
	protected
			//background
			$BG								= 'black', 
			$HighlightedLineBG				= '#2a3133', 
			$HighlightedLineHoverBG			= '#3a4143',
			//gutter
			$GutterText						= '#d3d3d3',
			$GutterBG						= '#990000',
			$GutterBorder					= '#990000',
			$GutterHighlightedText			= 'black',
			$GutterHighlightedBG			= '#990000',
			//syntax
			$Plain							= '#d3d3d3',
			$Keyword						= 'aqua',
			$Constant						= '#ff9e7b',
			$Function						= '#81cef9',
			$Variable						= '#ffaa3e',
			$String							= '#ff9e7b',
			$Script							= 'aqua',
			$Number							= 'red',
			$Comment						= '#ff7d27';
}
?>