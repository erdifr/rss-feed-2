<?php

class pluginRSSFeed extends Plugin {

	public function init()
	{
		// Fields and default values for the database of this plugin
		$this->dbFields = array(
			'rssFeedItemLimit' => 10,
			'rssFeedCopyright' => 'CC By-SA 4.0',
			'rssFeedGenerator' => 'Bludit - Flat-File CMS',
			'rssFeedTTL' => 60
		);
	}

	// Method called on the settings of the plugin on the admin area
	public function form()
	{
		global $L;

		$html  = '<div class="alert alert-primary" role="alert">';
		$html .= $this->description();
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-url').'</label>';
		$html .= '<a href="'.DOMAIN_BASE.'rss2.xml">'.DOMAIN_BASE.'rss2.xml</a>';
		$html .= '<span class="tip">'.$L->get('rss-feed-url-tip').'</span>';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-item-limit').'</label>';
		$html .= '<input id="jsrssFeedItemLimit" name="rssFeedItemLimit" type="text" value="'.$this->getValue('rssFeedItemLimit').'">';
		$html .= '<span class="tip">'.$L->get('rss-feed-item-limit-tip').'</span>';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-copyright').'</label>';
		$html .= '<input id="jsrssFeedCopyright" name="rssFeedCopyright" type="text" value="'.$this->getValue('rssFeedCopyright').'">';
		$html .= '<span class="tip">'.$L->get('rss-feed-copyright-tip').'</span>';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-generator').'</label>';
		$html .= '<input id="jsrssFeedGenerator" name="rssFeedGenerator" type="text" value="'.$this->getValue('rssFeedGenerator').'">';
		$html .= '<span class="tip">'.$L->get('rss-feed-generator-tip').'</span>';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('rss-feed-ttl').'</label>';
		$html .= '<input id="jsrssFeedTTL" name="rssFeedTTL" type="text" value="'.$this->getValue('rssFeedTTL').'">';
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
		if (!empty($rssFeedCopyright)) {
			$xml .= '<copyright>'.$this->getValue('rssFeedCopyright').'</copyright>';
		}

		$xml .= '<lastBuildDate>'.date(DATE_RSS).'</lastBuildDate>';

		// Add generator to RSS Feed channel if enabled
		if (!empty($rssFeedGenerator)) {
			$xml .= '<generator>'.$this->getValue('rssFeedGenerator').'</generator>';
		}

		// Add ttl to RSS Feed channel if enabled
		if (!empty($rssFeedTTL)) {
			$xml .= '<ttl>'.$this->getValue('rssFeedTTL').'</ttl>';
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
		return $doc->save($this->workspace().'rss2.xml');
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

		return '<link rel="alternate" type="application/rss+xml" href="'.DOMAIN_BASE.'rss2.xml" title="'.$site->title().' - RSS Feed">'.PHP_EOL;
	}

	public function beforeAll()
	{
		$webhook = 'rss2.xml';
		if ($this->webhook($webhook)) {
			// Send XML header
			header('Content-type: text/xml');
			$doc = new DOMDocument();

			// Load XML
			libxml_disable_entity_loader(false);
			$doc->load($this->workspace().'rss2.xml');
			libxml_disable_entity_loader(true);

			// Print the XML
			echo $doc->saveXML();

			// Stop Bludit execution
			exit(0);
		}
	}
}
