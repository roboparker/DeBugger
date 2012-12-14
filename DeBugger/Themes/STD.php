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
class STD {
	public 
			//background
			$BG							= '#001', 
			$HighlightedLineBG			= '#200', 
			$HighlightedLineHoverBG		= '#400',
			//gutter
			$GutterText						= '#666',
			$GutterBorder					= '#400',
			$GutterBG						= 'transparent',
			$GutterHighlightedText			= '#FFF',
			$GutterHighlightedBG			= '#200',
			//syntax
			$Plain							= '#FFF',
			$Keyword						= '#39F',
			$Constant						= '#FC3',
			$Function						= '#F0E386',
			$Variable						= '#9CF',
			$String							= '#FFA0A0',
			$Script							= '#CCC',
			$Number							= '#C6F',
			$Comment						= '#999';
	
	public function GetStyle(){
?>
	<style>
			/* syntax highlighter layout*/
			.syntaxhighlighter a,
			.syntaxhighlighter div,
			.syntaxhighlighter code,
			.syntaxhighlighter table,
			.syntaxhighlighter table td,
			.syntaxhighlighter table tr,
			.syntaxhighlighter table tbody,
			.syntaxhighlighter table thead,
			.syntaxhighlighter table caption,
			.syntaxhighlighter textarea {
				line-height: 1.3em;
				font-family: "Lucida Console", Monaco, monospace;
			}
			.syntaxhighlighter{
				position: relative;
				overflow: auto;
				background-color: <?= $this->BG; ?>;
			}
			.syntaxhighlighter table td.gutter .line {
				text-align: right;
				padding: 0 0.5em;
				min-width:1.5em;
			}
			.syntaxhighlighter table td.code {
				width: 100%;
			}
			.syntaxhighlighter table td.code{
				white-space: pre;
			}
			.syntaxhighlighter table td.code .line {
				padding: 0 0.2em;
			}
			.syntaxhighlighter.collapsed table {
				display: none;
			}
			.syntaxhighlighter.collapsed .toolbar {
				width: auto;
				padding: 0.1em 0.5em;
				position: static;
			}
			.syntaxhighlighter.collapsed .toolbar span a {
			  display: none;
			}
			.syntaxhighlighter.collapsed .toolbar span a.expandSource {
				display: inline;
			}
			.syntaxhighlighter .toolbar {
				position: absolute;
				right: 0px;
				top: 0px;
				width: 15px;
				height: 15px;
				font-size: 0.8em;
			}
			.syntaxhighlighter .toolbar a {
				color: <?= $this->Comment; ?>;
				display: block;
				padding-top: 0.1em;
				text-align: center;
				text-decoration: none;
			}
			.syntaxhighlighter .toolbar a.expandSource {
				display: none;
			}
			
			/* colors */
			.syntaxhighlighter .gutter {
				color: <?= $this->GutterText; ?>;
			}
			.syntaxhighlighter .gutter .line {
				border-right: 3px solid <?= $this->GutterBorder; ?>;
			}
			.syntaxhighlighter .gutter .line.highlighted {
				background-color: <?= $this->GutterHighlightedBG; ?>;
				color: <?= $this->GutterHighlightedText; ?>;
			}
			
			.syntaxhighlighter .highlighted{background-color: <?= $this->HighlightedLineBG; ?>;}
			.syntaxhighlighter .plain, .syntaxhighlighter .plain a {color: <?= $this->Plain; ?>;}
			.syntaxhighlighter .comments, .syntaxhighlighter .comments a {color: <?= $this->Comment; ?>;}
			.syntaxhighlighter .string, .syntaxhighlighter .string a {color: <?= $this->String; ?>;}
			.syntaxhighlighter .number{color: <?= $this->Number; ?>;}
			.syntaxhighlighter .keyword {color: <?= $this->Keyword; ?>;}
			.syntaxhighlighter .variable {color: <?= $this->Variable; ?>;}
			.syntaxhighlighter .functions {color: <?= $this->Function; ?>;}
			.syntaxhighlighter .constants {color: <?= $this->Constant; ?>;}
			.syntaxhighlighter .keyword {font-weight: bold;}
			.syntaxhighlighter .script {color: <?= $this->Script; ?>;}
		</style>
<?php
	}
}
?>