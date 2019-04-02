<?php

class pluginRSSFeed extends Plugin {

	public function init()
	{
		// Fields and default values for the database of this plugin
		$this->dbFields = array(
			'rssFeedFile' => 'feed.rss',
			'rssFeedItemLimit' => 10,
			'rssFeedCopyright' => '',
			'rssFeedGenerator' => 'Bludit - Flat-File CMS',
			'rssFeedTTL' => 60
		);
	}

	// Method called on the settings of the plugin on the admin area
	public function form()
	{
		global $L;

		// RSS Feed File
		$rssFeedFile = $this->getValue('rssFeedFile');
		// RSS Feed Copyright
		$rssFeedCopyright = $this->getValue('rssFeedCopyright');

		$html  = '<div class="alert alert-primary" role="alert">';
		$html .= $this->description();
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-url').'</label>';
		$html .= '<a href="'.DOMAIN_BASE.$rssFeedFile.'">'.DOMAIN_BASE.$rssFeedFile.'</a>';
		$html .= '<span class="tip">'.$L->get('rss-feed-url-tip').'</span>';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-item-limit').'</label>';
		$html .= '<input id="jsrssFeedItemLimit" name="rssFeedItemLimit" type="number" title="Valid input range: -1 - 100" min="-1" max="100" value="'.$this->getValue('rssFeedItemLimit').'">';
		$html .= '<span class="tip">'.$L->get('rss-feed-item-limit-tip').'</span>';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-copyright').'</label>';
		$html .= '<select id="jsrssFeedCopyright" name="rssFeedCopyright">';

		if (!empty($rssFeedCopyright)) {
			$html .= '<option value="'.$rssFeedCopyright.'">'.$rssFeedCopyright.'</option>';
		} else {
			$html .= '<option value="DISABLE">DISABLE</option>';
		}

		$html .= '<option value="CC BY 4.0">CC BY 4.0</option>';
		$html .= '<option value="CC BY-SA 4.0">CC BY-SA 4.0</option>';
		$html .= '<option value="CC BY-ND 4.0">CC BY-ND 4.0</option>';
		$html .= '<option value="CC BY-NC 4.0">CC BY-NC 4.0</option>';
		$html .= '<option value="CC BY-NC-SA 4.0">CC BY-NC-SA 4.0</option>';
		$html .= '<option value="CC BY-NC-ND 4.0">CC BY-NC-ND 4.0</option>';
		$html .= '<option value="DISABLE">DISABLE</option>';
		$html .= '</select>';

		if (!empty($rssFeedCopyright)) {
			$html .= '<span class="tip">'.$L->get('rss-feed-copyright-tip').$rssFeedCopyright.'</span>';
		} else {
			$html .= '<span class="tip">'.$L->get('rss-feed-copyright-tip').'DISABLE</span>';
		}
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-generator').'</label>';
		$html .= '<input id="jsrssFeedGenerator" name="rssFeedGenerator" pattern="[a-zA-ZÀ-ž0-9-_. !]+" title="Valid: !, -, _, ., a-z, A-Z, À-ž, 0-9" maxlength="50" type="text" value="'.$this->getValue('rssFeedGenerator').'">';
		$html .= '<span class="tip">'.$L->get('rss-feed-generator-tip').'</span>';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-ttl').'</label>';
		$html .= '<input id="jsrssFeedTTL" name="rssFeedTTL" type="number" title="Valid input range: 0 - 1440" min="0" max="1440" value="'.$this->getValue('rssFeedTTL').'">';
		$html .= '<span class="tip">'.$L->get('rss-feed-ttl-tip').'</span>';
		$html .= '</div>';

		return $html;
	}

	private function createXML()
	{
		global $site;
		global $pages;
		global $url;

		// Amount of pages to show
		$rssFeedItemLimit = $this->getValue('rssFeedItemLimit');
		// RSS Feed Copyright
		$rssFeedCopyright = $this->getValue('rssFeedCopyright');
		// RSS Feed File
		$rssFeedFile = $this->getValue('rssFeedFile');
		// RSS Feed Generator
		$rssFeedGenerator = $this->getValue('rssFeedGenerator');
		// RSS Feed ttl
		$rssFeedTTL = $this->getValue('rssFeedTTL');

		// Get the list of published pages (sticky and static included)
		$list = $pages->getList(
			$pageNumber=1,
			$rssFeedItemLimit,
			$published=true,
			$static=true,
			$sticky=true,
			$draft=false,
			$scheduled=false
		);

		$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
		$xml .= '<rss version="2.0">';
		$xml .= '<channel>';
		$xml .= '<title>'.$site->title().'</title>';
		$xml .= '<link>'.$site->url().'</link>';
		$xml .= '<description>'.$site->description().'</description>';
		$xml .= '<language>'.Theme::lang().'</language>';

		// Add copyright to RSS Feed channel if enabled
		if (!empty($rssFeedCopyright) && ($rssFeedCopyright !== 'DISABLE')) {
			$xml .= '<copyright>'.$rssFeedCopyright.'</copyright>';
		}

		$xml .= '<lastBuildDate>'.date(DATE_RSS).'</lastBuildDate>';

		// Add generator to RSS Feed channel if enabled
		if (!empty($rssFeedGenerator)) {
			$xml .= '<generator>'.$rssFeedGenerator.'</generator>';
		}

		// Add ttl to RSS Feed channel if enabled
		if (!empty($rssFeedTTL) && ($rssFeedTTL !== 0)) {
			$xml .= '<ttl>'.$rssFeedTTL.'</ttl>';
		}

		// Get keys of pages
		foreach ($list as $pageKey) {
			try {
				// Create the page object from the page key
				$page = new Page($pageKey);
				$xml .= '<item>';
				$xml .= '<title>'.$page->title().'</title>';
				$xml .= '<link>'.$page->permalink().'</link>';
				$xml .= '<description>'.Sanitize::html($page->contentBreak()).'</description>';

				// Add category to RSS Feed item if enabled
				if (!empty($page->category())) {
					$xml .= '<category>'.$page->category().'</category>';
				}

				$xml .= '<pubDate>'.$page->date(DATE_RSS).'</pubDate>';
				$xml .= '<guid isPermaLink="false">'.$page->uuid().'</guid>';
				$xml .= '</item>';
			} catch (Exception $e) {
				// Continue
			}
		}

		$xml .= '</channel></rss>';

		// New DOM document
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		$doc->loadXML($xml);
		return $doc->save($this->workspace().$rssFeedFile);
	}

	public function install($position=0)
	{
		parent::install($position);
		return $this->createXML();
	}

	public function post()
	{
		parent::post();
		return $this->createXML();
	}

	public function afterPageCreate()
	{
		$this->createXML();
	}

	public function afterPageModify()
	{
		$this->createXML();
	}

	public function afterPageDelete()
	{
		$this->createXML();
	}

	public function siteHead()
	{
		global $site;

		// RSS Feed File
		$rssFeedFile = $this->getValue('rssFeedFile');

		return '<link rel="alternate" type="application/rss+xml" href="'.DOMAIN_BASE.$rssFeedFile.'" title="'.$site->title().' - RSS Feed">'.PHP_EOL;
	}

	public function beforeAll()
	{
		// RSS Feed File
		$rssFeedFile = $this->getValue('rssFeedFile');

		if ($this->webhook($rssFeedFile)) {
			// Send XML header
			header('Content-type: text/xml');
			$doc = new DOMDocument();

			// Load XML
			libxml_disable_entity_loader(false);
			$doc->load($this->workspace().$rssFeedFile);
			libxml_disable_entity_loader(true);

			// Print the XML
			echo $doc->saveXML();

			// Stop Bludit execution
			exit(0);
		}
	}
}
