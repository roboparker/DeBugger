<?php
namespace DeBugger\Themes;
/**
 * Debugger @version 2.0 pre release
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
class MDUltra extends \DeBugger\Themes\STD{
	public 
			//background
			$BG								= '#222222', 
			$HighlightedLineBG				= '#253e5a', 
			$HighlightedLineHoverBG			= '#354e6a',
			//gutter
			$GutterText						= '#38566f',
			$GutterBG						= 'transparent',
			$GutterBorder					= '#435a5f',
			$GutterHighlightedText			= '#222222',
			$GutterHighlightedBG			= '#435a5f',
			//syntax
			$Plain							= 'lime',
			$Keyword						= '#aaaaff',
			$Constant						= 'yellow',
			$Function						= '#ff8000',
			$Variable						= 'aqua',
			$String							= 'lime',
			$Script							= '#aaaaff',
			$Number							= 'red',
			$Comment						= '#428bdd';
}
?>