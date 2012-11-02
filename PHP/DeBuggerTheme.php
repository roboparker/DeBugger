<?php
namespace DeBugger;
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
