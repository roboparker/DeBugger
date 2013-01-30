<?php
namespace Debugger;
abstract class Theme {
	public
			$PrependSelector			= '.syntaxhighlighter',
			//background
			$BG							= '#001', 
			$HighlightedLineBG			= '#200', 
			$HighlightedLineHoverBG		= '#400',
			//gutter
			$GutterText					= '#666',
			$GutterBorder				= '#400',
			$GutterBG					= 'transparent',
			$GutterHighlightedText		= '#FFF',
			$GutterHighlightedBG		= '#200',
			//syntax
			$Plain						= '#FFF',
			$Keyword					= '#39F',
			$Constant					= '#FC3',
			$Function					= '#F0E386',
			$Variable					= '#9CF',
			$String						= '#FFA0A0',
			$Script						= '#CCC',
			$Number						= '#C6F',
			$Comment					= '#999';
	
	public function GetStyle(){
		ob_start();
?>
	<style>
			/* syntax highlighter layout*/
			<?= $this->PrependSelector; ?> a,
			<?= $this->PrependSelector; ?> div,
			<?= $this->PrependSelector; ?> code,
			<?= $this->PrependSelector; ?> table,
			<?= $this->PrependSelector; ?> table td,
			<?= $this->PrependSelector; ?> table tr,
			<?= $this->PrependSelector; ?> table tbody,
			<?= $this->PrependSelector; ?> table thead,
			<?= $this->PrependSelector; ?> table caption,
			<?= $this->PrependSelector; ?> textarea {
				line-height: 1.3em;
				font-family: "Lucida Console", Monaco, monospace;
			}
			<?= $this->PrependSelector; ?>{
				position: relative;
				overflow: auto;
				background-color: <?= $this->BG; ?>;
			}
			<?= $this->PrependSelector; ?> table td.gutter .line {
				text-align: right;
				padding: 0 0.5em;
				min-width:1.5em;
			}
			<?= $this->PrependSelector; ?> table td.code {
				width: 100%;
			}
			<?= $this->PrependSelector; ?> table td.code{
				white-space: pre;
			}
			<?= $this->PrependSelector; ?> table td.code .line {
				padding: 0 0.2em;
			}
			<?= $this->PrependSelector; ?>.collapsed table {
				display: none;
			}
			<?= $this->PrependSelector; ?>.collapsed .toolbar {
				width: auto;
				padding: 0.1em 0.5em;
				position: static;
			}
			<?= $this->PrependSelector; ?>.collapsed .toolbar span a {
			  display: none;
			}
			<?= $this->PrependSelector; ?>.collapsed .toolbar span a.expandSource {
				display: inline;
			}
			<?= $this->PrependSelector; ?> .toolbar {
				position: absolute;
				right: 0px;
				top: 0px;
				width: 15px;
				height: 15px;
				font-size: 0.8em;
			}
			<?= $this->PrependSelector; ?> .toolbar a {
				color: <?= $this->Comment; ?>;
				display: block;
				padding-top: 0.1em;
				text-align: center;
				text-decoration: none;
			}
			<?= $this->PrependSelector; ?> .toolbar a.expandSource {
				display: none;
			}
			
			/* colors */
			<?= $this->PrependSelector; ?> .gutter {
				color: <?= $this->GutterText; ?>;
			}
			<?= $this->PrependSelector; ?> .gutter .line {
				border-right: 3px solid <?= $this->GutterBorder; ?>;
			}
			<?= $this->PrependSelector; ?> .gutter .line.highlighted {
				background-color: <?= $this->GutterHighlightedBG; ?>;
				color: <?= $this->GutterHighlightedText; ?>;
			}
			
			<?= $this->PrependSelector; ?> .highlighted{background-color: <?= $this->HighlightedLineBG; ?>;}
			<?= $this->PrependSelector; ?> .plain, <?= $this->PrependSelector; ?> .plain a {color: <?= $this->Plain; ?>;}
			<?= $this->PrependSelector; ?> .comments, <?= $this->PrependSelector; ?> .comments a {color: <?= $this->Comment; ?>;}
			<?= $this->PrependSelector; ?> .string, <?= $this->PrependSelector; ?> .string a {color: <?= $this->String; ?>;}
			<?= $this->PrependSelector; ?> .number{color: <?= $this->Number; ?>;}
			<?= $this->PrependSelector; ?> .keyword {color: <?= $this->Keyword; ?>;}
			<?= $this->PrependSelector; ?> .variable {color: <?= $this->Variable; ?>;}
			<?= $this->PrependSelector; ?> .functions {color: <?= $this->Function; ?>;}
			<?= $this->PrependSelector; ?> .constants {color: <?= $this->Constant; ?>;}
			<?= $this->PrependSelector; ?> .keyword {font-weight: bold;}
			<?= $this->PrependSelector; ?> .script {color: <?= $this->Script; ?>;}
		</style>
<?php
		return ob_get_clean();
	}
}

?>
